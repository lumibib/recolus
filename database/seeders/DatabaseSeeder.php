<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\Site::factory(2)->create();

        \App\Models\Site::factory()->create([
            'name' => env('APP_NAME'),
            'uuid' => '0182a590-0b43-33bd-87eb-329033e88820',
            'domain' => env('APP_URL'),
            'settings' => null,
        ]);

        \App\Models\Record::factory(1000)->create();
        \App\Models\Record::factory(1000)->create();
        \App\Models\Record::factory(1000)->create();
        \App\Models\Record::factory(1000)->create();
        \App\Models\Record::factory(1000)->create();
    }
}
