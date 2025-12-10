<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все записи - Админпанель</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" alt="Герб РТУ МИРЭА" class="logo">
            <div class="header-content">
                <h1>Все записи на консультации</h1>
                <p class="subtitle">Управление записями студентов</p>
                <p class="additional-text">Всего записей: {{ $registrations->total() }}</p>
            </div>
        </header>
        
        <nav class="admin-nav">
            <a href="/admin">Статистика</a>
            <a href="/admin/registrations" class="active">Все записи</a>
            <a href="/consultations" target="_blank">Сайт</a>
            <a href="/admin" class="btn btn-secondary">Назад</a>
        </nav>
        
        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="admin-card">
                <h3>Всего записей: {{ $registrations->total() }}</h3>
                <p>Показано: {{ $registrations->count() }} из {{ $registrations->total() }}</p>
            </div>
            
            @if($registrations->count() > 0)
                <div class="admin-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Студент</th>
                                <th>Консультация</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Дата записи</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                                <tr>
                                    <td>{{ $registration->id }}</td>
                                    <td>
                                        <strong>{{ $registration->first_name }} {{ $registration->last_name }}</strong>
                                    </td>
                                    <td>
                                        <a href="/admin/consultation/{{ $registration->consultation_id }}">
                                            {{ $registration->consultation->title }}
                                        </a>
                                        <div class="consultation-info">
                                            {{ $registration->consultation->start_time->format('d.m.Y H:i') }}
                                        </div>
                                    </td>
                                    <td>{{ $registration->email }}</td>
                                    <td>{{ $registration->phone }}</td>
                                    <td>{{ $registration->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/admin/consultation/{{ $registration->consultation_id }}" class="btn btn-action btn-view">
                                                Консультация
                                            </a>
                                            <form action="/admin/registration/delete/{{ $registration->id }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Удалить запись?')">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($registrations->hasPages())
                    <div class="pagination">
                        {{ $registrations->links() }}
                    </div>
                @endif
            @else
                <div class="admin-empty-state">
                    <div class="empty-icon"></div>
                    <p>Нет записей в базе данных.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>