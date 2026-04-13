@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-2xl font-semibold">Регистрация</h1>
        <form method="POST" action="{{ route('auth.register.submit') }}" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Имя" class="w-full rounded border px-3 py-2" required>
            <input type="email" name="email" placeholder="Email" class="w-full rounded border px-3 py-2" required>
            <input type="password" name="password" placeholder="Пароль" class="w-full rounded border px-3 py-2" required>
            <input type="password" name="password_confirmation" placeholder="Повторите пароль" class="w-full rounded border px-3 py-2" required>
            <button type="submit" class="w-full rounded bg-blue-600 px-3 py-2 text-white">Зарегистрироваться</button>
        </form>
    </div>
@endsection
