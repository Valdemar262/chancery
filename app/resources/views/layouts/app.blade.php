<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Chancery' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex gap-6">
                <a href="/" class="text-lg font-semibold text-gray-800">Chancery</a>
                <a href="/statements" class="text-sm text-gray-600 hover:text-gray-900 self-center">Заявки</a>
            </div>
            @if($showAuth ?? true)
                <div class="flex gap-4 items-center">
                    <span id="nav-user" class="hidden text-sm text-gray-600"></span>
                    <button type="button" id="logout-btn" class="hidden text-sm text-red-600 hover:text-red-700 font-medium">Выйти</button>
                    <a href="/login" id="nav-login" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Войти</a>
                </div>
            @endif
        </div>
    </nav>
    <main class="max-w-4xl mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>
</html>
