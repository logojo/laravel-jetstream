<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' =>  $this->faker->sentence(),
            'content' => $this->faker->text(),
            'image' => $this->faker->imageUrl(640, 480, 'animals', true)
            //'image' => $this->faker->image('public/storage/posts/', 640, 480, false)
        ];
    }
}
