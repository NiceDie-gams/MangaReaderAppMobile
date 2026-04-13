@extends('layouts.app')

@section('content')
<div class="grid gap-6 md:grid-cols-[250px_1fr]">
    <aside class="rounded bg-white p-4 shadow">
        <button id="filters-toggle" class="mb-3 w-full rounded bg-slate-200 px-3 py-2 md:hidden">Фильтры</button>
        <form id="filter-form" class="space-y-3">
            <input id="search-input" name="search" value="{{ $search }}" placeholder="Поиск..." class="w-full rounded border px-3 py-2">
            <div id="filters-panel" class="space-y-3">
                <div>
                    <p class="text-sm font-semibold">Включить теги</p>
                    @foreach($tags as $tag)
                        <label class="block text-sm"><input type="checkbox" name="include_tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, $selectedIncludeTags))> {{ $tag->name }}</label>
                    @endforeach
                </div>
                <div>
                    <p class="text-sm font-semibold">Исключить теги</p>
                    @foreach($tags as $tag)
                        <label class="block text-sm"><input type="checkbox" name="exclude_tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, $selectedExcludeTags))> {{ $tag->name }}</label>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <button class="rounded bg-blue-600 px-3 py-2 text-sm text-white" id="apply-filters">Применить</button>
                    <button class="rounded bg-slate-300 px-3 py-2 text-sm" id="clear-filters" type="button">Очистить</button>
                </div>
            </div>
        </form>
    </aside>

    <section>
        <div class="titles-grid grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
            @foreach($titles as $title)
                <a href="{{ route('titles.show', $title) }}" class="overflow-hidden rounded bg-white shadow">
                    <img src="{{ $title->cover_image }}" class="h-56 w-full object-cover" alt="{{ $title->title }}">
                    <div class="p-2 text-sm font-medium">{{ $title->title }}</div>
                </a>
            @endforeach
        </div>
        <div class="mt-4">{{ $titles->links() }}</div>
    </section>
</div>
@endsection
