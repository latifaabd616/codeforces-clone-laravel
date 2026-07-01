<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Level>
 */
class LevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $icons = [
            'level-beginner.png',
            'level-intermediate.png',
            'level-advanced.png',
            'level-expert.png',
            'level-master.png'
        ];
        
        return [
            'Title' => $this->faker->unique()->words(2, true), // عناوين فريدة
            'RequiredXP' => $this->faker->numberBetween(100, 10000),
            'Icon' => $this->faker->randomElement($icons),
            // timestamps تضاف تلقائياً
        ];
    }
    
}
