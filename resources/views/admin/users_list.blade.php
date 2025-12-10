<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользователи - Админпанель</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" alt="Герб РТУ МИРЭА" class="logo">
            <div class="header-content">
                <h1>Пользователи системы</h1>
                <p class="subtitle">Администраторы и студенты</p>
                <p class="additional-text">Всего пользователей: {{ $users->count() }}</p>
            </div>
        </header>
        <div style="margin-bottom: 20px;">
            <a href="/admin" class="btn">Назад</a>
            <a href="/admin/consultations" class="btn">Консультации</a>
            <a href="/admin/registrations" class="btn">Записи</a>
        </div>        
        <h1>Все пользователи</h1>
        
        @if($users->count() > 0)
            <div class="admin-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Телефон</th>
                            <th>Дата регистрации</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role == 'admin' ? 'badge-admin' : 'badge-student' }}">
                                        {{ $user->role == 'admin' ? 'Админ' : 'Студент' }}
                                    </span>
                                </td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $users->links() }}
        @else
            <div class="admin-empty-state">
                <div class="empty-icon"></div>
                <p>Нет пользователей в системе</p>
            </div>
        @endif
    </div>
</body>
</html>