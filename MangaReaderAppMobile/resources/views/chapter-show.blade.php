@extends('layouts.app')

@section('content')
<div class="rounded bg-black p-2">
    <img
        id="chapter-image"
        data-chapter-id="{{ $chapter->id }}"
        data-title-slug="{{ $title->slug }}"
        data-page="{{ $firstPage?->page_number ?? 1 }}"
        src="{{ $firstPage?->image_path }}"
        class="mx-auto max-h-[85vh] cursor-pointer object-contain"
        alt="chapter page"
    >
</div>
@endsection
