<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record>
 */
class RecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'rid' => fake()->uuid(),
            'site_id' => fake()->randomElement([3, 2]),
            'anonymous_id' => fake()->randomElement([
                '58fae803969f8257e0acbb6d702360e25bfe42fa163a7e2b31adb578d041c3c0',
                '026135e319ed7c0f813dd0a2ca9aaa4edfb59f48edc3cbb38ca2a6f8dd85e13b',
                '81394707aaae019cc7b9f2dd60acc9e8c2a39e643550215a8a36b7603f09ec77',
                '7sjhd830fjaf8257e0acbb6d702360e25bfe42fa163a7e2b31adbjs836dkao8',
            ]),
            'is_first_visit' => fake()->randomElement([0, 1]),
            'type' => fake()->randomElement(['page']),
            'title' => fake()->randomElement(['Home', 'Contact', 'Lorem ispum', fake()->sentence(3)]),
            'url' => fake()->randomElement([env('APP_URL').'/', env('APP_URL').'/contact', env('APP_URL').'/lorem', fake()->url()]),
            'path' => fake()->randomElement(['/', '/contact', '/lorem', '/login']),
            'fragment' => fake()->optional()->randomElement(['#element']),
            'query' => fake()->optional()->randomElement(['?q=search']),
            'host' => env('APP_URL'),
            'user_agent' => fake()->randomElement(['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.115 Safari/537.36', fake()->userAgent()]),
            'browser' => fake()->randomElement(['Chrome', 'Firefox', 'Safari']),
            'browser_version' => fake()->randomElement(['102.0.5005.115', '101.2.2000.346', '95.2']),
            'platform' => fake()->randomElement(['OS X', 'Windows', 'Linux']),
            'platform_version' => fake()->randomElement(['10_15_7', '10_13_2', '8']),
            'device' => fake()->randomElement(['desktop', 'tablet', 'mobile']),
            'language' => fake()->randomElement(['fr', 'de', 'en', fake()->languageCode()]),
            'referrer' => fake()->randomElement([null, env('APP_URL').'/lorem', 'https://google.com', 'https://twitter.com']),
            'timezone' => fake()->randomElement(['Europe/Zurich', 'Europe/Paris', 'America/New_York', 'Europe/Berlin', fake()->timezone()]),
            'country' => fake()->randomElement(['CH', 'FR', 'USA', 'DE', fake()->countryCode()]),
            'screen' => fake()->randomElement([
                ['width' => 1680, 'height' => 1050, 'color_depth' => 24],
                ['width' => 1920, 'height' => 1200, 'color_depth' => 24],
            ]),
            'window' => fake()->randomElement([
                ['width' => 720, 'height' => 963],
                ['width' => 840, 'height' => 1680],
            ]),
            'utm' => null,
            'duration' => fake()->numberBetween(1, 200),
            'scroll' => fake()->randomElement([0, fake()->numberBetween(1, 100)]),
            'ts' => fake()->unixTime(),
            'created_at' => fake()->dateTimeBetween('-12 months', '+1 day')->format('Y-m-d H:i:s'),
        ];
    }
}
