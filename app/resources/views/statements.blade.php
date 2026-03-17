@extends('layouts.app')

@section('content')
<div>
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Мои заявки</h1>
    <div id="loading" class="text-gray-500">Загрузка...</div>
    <div id="statements-list" class="space-y-4 hidden">
        <!-- Statements will be rendered here -->
    </div>
    <div id="empty-state" class="hidden text-center py-12 text-gray-500">
        Нет заявок
    </div>
</div>
@endsection
