<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Title;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TitleController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Title::query()->with('tags');
        $search = $request->string('search')->toString();
        $includeTags = array_filter((array) $request->input('include_tags', []));
        $excludeTags = array_filter((array) $request->input('exclude_tags', []));

        if ($search !== '') {
            $query->where('title', 'like', "%{$search}%");
        }

        foreach ($includeTags as $tagId) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
        }

        if ($excludeTags) {
            $query->whereDoesntHave('tags', fn ($q) => $q->whereIn('tags.id', $excludeTags));
        }

        $titles = $query->latest()->paginate(12)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($titles);
        }

        return view('home', [
            'titles' => $titles,
            'tags' => Tag::orderBy('name')->get(),
            'selectedIncludeTags' => $includeTags,
            'selectedExcludeTags' => $excludeTags,
            'search' => $search,
        ]);
    }

    public function show(Title $title): View
    {
        $title->load(['chapters', 'tags', 'comments.user']);
        $isFavorite = auth()->check() && auth()->user()->favoriteTitles()->where('title_id', $title->id)->exists();

        return view('title-show', compact('title', 'isFavorite'));
    }
}
