<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $parentId = rand(0, 1);
        return [
            'name' => fake()->name(),
            'path' => fake()->url(),
            'storage_path' => fake()->url(),
            'parent_id' => $parentId ? 1 : null,
            'lft' => $parentId ? 2 : 1,
            'rgt' => $parentId ? 3 : 2,
            'depth' => 0,
            'is_folder' => rand(0, 1),
            'size' => fake()->numberBetween(1000, 1000000),
            'mime' => fake()->mimeType(),
            'uploaded_on_cloud' => rand(0, 1),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
