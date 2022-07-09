<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->domainWord(),
            'domain' => fake()->domainName(),
            'uuid' => fake()->uuid(),
            'settings' => [
                'foo' => 'bar',
            ],
        ];
    }
}
