<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement($this->getRandomCategory()),
        ];
    }

    protected $categories = [
        'Action',
        'Adventure',
        'Comedy',
        'Drama',
        'Horror',
        'Romance',
        'Sci-Fi',
        'Fantasy',
        'Thriller',
        'Mystery',
        'Animation',
        'Documentary',
        'Family',
        'Crime',
        'Musical',
        'Historical',
        'War',
        'Western',
        'Biography',
        'Sport',
        'Adventure-Comedy',
        'Film-Noir',
        'Superhero',
        'Slasher',
        'Romantic-Comedy',
        'Psychological',
        'Experimental',
        'Indie',
        'Cult',
        'Teen',
        'Post-Apocalyptic',
        'Zombie',
        'True Crime'
    ];

    private function getRandomCategory(): array
    {
        $existingCategories = Category::pluck('name')->toArray();
        $availableCategories = array_filter($this->categories, fn ($category) => !in_array($category, $existingCategories));

        if (empty($availableCategories)) {
            throw new \Exception('No available categories left to assign.');
        }

        return $availableCategories;
    }
}
