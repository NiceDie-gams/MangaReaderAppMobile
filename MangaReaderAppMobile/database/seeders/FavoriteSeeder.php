<?php

namespace Database\Seeders;

use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $titles = Title::all();
        User::all()->each(function (User $user) use ($titles): void {
            $user->favoriteTitles()->syncWithoutDetaching(
                $titles->random(rand(2, 4))->pluck('id')->toArray()
            );
        });
    }
}
