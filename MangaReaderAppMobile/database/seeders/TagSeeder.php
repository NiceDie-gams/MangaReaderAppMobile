<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Action', 'Drama', 'Romance', 'Fantasy', 'Sci-Fi', 'Comedy'] as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}
