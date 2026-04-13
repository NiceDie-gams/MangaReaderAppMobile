<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Title;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Title $title): JsonResponse
    {
        $comments = $title->comments()->with('user:id,name')->get();

        return response()->json($comments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title_id' => ['required', 'exists:titles,id'],
            'content' => ['required', 'string', 'max:1500'],
        ]);
        
        $comment = Comment::create([
            'user_id' => auth()->id(),
            'title_id' => $validated['title_id'],
            'content' => $validated['content'],
        ])->load('user:id,name');

        return response()->json($comment, 201);
    }
}
