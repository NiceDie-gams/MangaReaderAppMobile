<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TitleController;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/titles', [TitleController::class, 'index']);
Route::get('/chapter/{chapter}/page/{page}', [ChapterController::class, 'page']);
Route::get('/title/{title}/comments', [CommentController::class, 'index']);

Route::get('/chapter/{chapter}/navigate/{direction}', function (Chapter $chapter, string $direction, Request $request) {
    $operator = $direction === 'prev' ? '<' : '>';
    $order = $direction === 'prev' ? 'desc' : 'asc';
    $target = $chapter->title->chapters()->where('chapter_number', $operator, $chapter->chapter_number)->orderBy('chapter_number', $order)->first();

    if (! $target) {
        return response()->json(['redirect_to_title' => true]);
    }

    $page = $direction === 'prev'
        ? $target->pages()->orderByDesc('page_number')->value('page_number')
        : $target->pages()->orderBy('page_number')->value('page_number');

    return response()->json([
        'chapter_id' => $target->id,
        'page_number' => $page,
    ]);
});
