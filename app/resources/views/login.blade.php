@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Вход</h1>
    <div id="login-error" class="hidden mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm"></div>
    <form id="login-form" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
            <input type="password" id="password" name="password" required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
        </div>
        <button type="submit" id="login-btn"
            class="w-full py-2 px-4 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            Войти
        </button>
    </form>
</div>
@endsection
