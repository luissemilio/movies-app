<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ActorGender;
use App\Models\Actor;
use Illuminate\Http\UploadedFile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Actor>
 */
class ActorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(ActorGender::cases());
        return [
            'name' => fake()->name(gender: $gender->value),
            'description' => fake()->sentence(),
            'gender' => $gender
        ];
    }

    public function configure()
    {
        return  $this->afterCreating(function (Actor $actor): void {
            $rand = random_int(1, 10);
            $image = asset("images/avatars/actors/{$actor->gender->value}/{$rand}.png");
            $actor->addMediaFromUrl($image)
                ->usingFileName(uniqid('actor_') . '.png')
                ->toMediaCollection('image');
        });
    }
}
