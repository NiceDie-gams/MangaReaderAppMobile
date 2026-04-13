<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Title;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ChapterController extends Controller
{
    public function show(Title $title, Chapter $chapter): View
    {
        abort_unless($chapter->title_id === $title->id, 404);
        $chapter->load('pages');
        $firstPage = $chapter->pages->first();

        return view('chapter-show', compact('title', 'chapter', 'firstPage'));
    }

    public function page(Chapter $chapter, int $page): JsonResponse
    {
        $chapter->load(['pages', 'title.chapters']);
        $current = $chapter->pages->firstWhere('page_number', $page);
        abort_unless($current, 404);

        $pages = $chapter->pages->values();
        $index = $pages->search(fn ($p) => $p->id === $current->id);

        $prevChapter = $chapter->title->chapters()->where('chapter_number', '<', $chapter->chapter_number)->orderByDesc('chapter_number')->first();
        $nextChapter = $chapter->title->chapters()->where('chapter_number', '>', $chapter->chapter_number)->orderBy('chapter_number')->first();

        return response()->json([
            'image_path' => $current->image_path,
            'page_number' => $current->page_number,
            'has_prev_page' => $index > 0,
            'has_next_page' => $index < ($pages->count() - 1),
            'prev_chapter_id' => $prevChapter?->id,
            'next_chapter_id' => $nextChapter?->id,
            'title_slug' => $chapter->title->slug,
            'chapter_number' => $chapter->chapter_number,
        ]);
    }
}
