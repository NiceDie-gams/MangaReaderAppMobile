<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Title;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TitleSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tag::all();

        for ($i = 1; $i <= 12; $i++) {
            $title = Title::create([
                'title' => "Manga {$i}",
                'slug' => Str::slug("Manga {$i}").'-'.$i,
                'description' => fake()->paragraph(3),
                'cover_image' => "https://picsum.photos/seed/manga{$i}/500/700",
            ]);
            $title->tags()->sync($tags->random(rand(1, 3))->pluck('id')->toArray());
        }
    }
}
