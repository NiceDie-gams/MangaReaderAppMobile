@extends('layouts.app')

@section('content')
<div id = "chapter-image-container" class="rounded bg-black cursor-pointer">
    <img
        id="chapter-image"
        data-chapter-id="{{ $chapter->id }}"
        data-title-slug="{{ $title->slug }}"
        data-page="{{ $firstPage?->page_number ?? 1 }}"
        src="{{ $firstPage?->image_path }}"
        class="mx-auto max-h-[90vh] object-contain"
        alt="chapter page"
    >
</div>
@endsection
