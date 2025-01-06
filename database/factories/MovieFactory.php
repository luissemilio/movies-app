<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\RatingMovie;
use App\Models\{Movie, Actor, Category, Room};

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $releaseDate = fake()->dateTimeBetween('-1 week', '+1 month');

        return [
            'title' => fake()->unique()->sentence(3),
            'description' => fake()->paragraphs(asText: true),
            'release_date' => $releaseDate->format('Y-m-d'),
            'expiration_date' => $releaseDate->modify('+' . fake()->numberBetween(8, 15) . ' days')->format('Y-m-d'),
            'duration' => fake()->numberBetween(100, 160),
            'rating' => fake()->randomElement(RatingMovie::cases()),
            'category_id' => Category::inRandomOrder()->value('id'),
        ];
    }

    public function configure()
    {
        return  $this->afterCreating(function (Movie $movie): void {
            $this->syncShowtimes($movie);
            $this->uploadImages($movie);
        });
    }

    private function uploadImages(Movie $movie): void
    {
        $rand = random_int(1, 15);
        $image = asset("images/films/{$rand}.png");
        $movie->addMediaFromUrl($image)
            ->usingFileName(uniqid('movie_') . '.png')
            ->toMediaCollection('cover');

        $randomNumbers = collect(range(1, 15))->reject(fn ($number) => $number === $rand)->random(2);

        foreach ($randomNumbers as $number) {
            $image = asset("images/films/{$number}.png");
            $movie->addMediaFromUrl($image)
            ->usingFileName(uniqid('image_') . '.png')
            ->toMediaCollection('images');
        }
    }

    private function syncShowtimes(Movie $movie): void
    {
        $actors = Actor::inRandomOrder()->take(rand(10, 15))->pluck('id')->all();
        $movie->actors()->attach($actors);
        $startDate = \Carbon\Carbon::parse($movie->release_date);
        $endDate = \Carbon\Carbon::parse($movie->expiration_date);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $showtimesCount = rand(1, 3);
            $startTime = \Carbon\Carbon::createFromTime(rand(8, 10));
            $endTime = $startTime->copy()->addMinutes($movie->duration);

            for ($i = 0; $i < $showtimesCount; $i++) {
                if ($i > 0) {
                    $startTime->addHours(3);
                    $endTime = $startTime->copy()->addMinutes($movie->duration);
                }

                $movie->showtimes()->create([
                    'movie_id' => $movie->id,
                    'room_id' => Room::inRandomOrder()->value('id'),
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $endTime->format('H:i:s'),
                ]);
            }
        }
    }
}
