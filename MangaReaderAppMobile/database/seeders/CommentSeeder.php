<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        Title::all()->each(function (Title $title) use ($users): void {
            for ($i = 0; $i < rand(1, 4); $i++) {
                Comment::create([
                    'user_id' => $users->random()->id,
                    'title_id' => $title->id,
                    'content' => fake()->sentence(12),
                ]);
            }
        });
    }
}
