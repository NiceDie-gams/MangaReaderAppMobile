<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MangaReader') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <header class="bg-white border-b border-slate-200">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="text-xl font-bold">MangaReader</a>
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Главная</a>
                @auth
                    <a href="{{ route('users.show', auth()->user()) }}" class="hover:text-blue-600">Профиль</a>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="rounded bg-slate-800 px-3 py-1 text-white">Выход</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="rounded bg-slate-800 px-3 py-1 text-white">Вход</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6">
        @yield('content')
    </main>

    <div id="global-loader" class="fixed right-4 bottom-4 hidden rounded bg-slate-900 px-3 py-2 text-sm text-white">Загрузка...</div>
    <div id="global-toast" class="fixed left-1/2 top-4 hidden -translate-x-1/2 rounded bg-rose-600 px-4 py-2 text-sm text-white"></div>
</body>
</html>
