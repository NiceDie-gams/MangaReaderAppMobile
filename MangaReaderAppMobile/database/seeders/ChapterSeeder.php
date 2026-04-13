<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\ChapterPage;
use App\Models\Title;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        Title::all()->each(function (Title $title): void {
            $chaptersCount = rand(3, 5);
            for ($chapterNumber = 1; $chapterNumber <= $chaptersCount; $chapterNumber++) {
                $chapter = Chapter::create([
                    'title_id' => $title->id,
                    'chapter_number' => $chapterNumber,
                    'title' => 'Chapter '.$chapterNumber,
                ]);

                $pagesCount = rand(3, 5);
                for ($page = 1; $page <= $pagesCount; $page++) {
                    ChapterPage::create([
                        'chapter_id' => $chapter->id,
                        'page_number' => $page,
                        'image_path' => "https://picsum.photos/seed/{$title->id}{$chapterNumber}{$page}/900/1300",
                    ]);
                }
            }
        });
    }
}
