<!DOCTYPE html>
<html>
<head>
    <title>Пользователи - Админка</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; }
        th { background: #f5f5f5; }
        .btn { padding: 8px 16px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; }
        .badge { padding: 4px 8px; border-radius: 10px; font-size: 12px; }
        .badge.admin { background: #e74c3c; color: white; }
        .badge.student { background: #2ecc71; color: white; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="/admin" class="btn">← Назад</a>
        <a href="/admin/consultations" class="btn"> Консультации</a>
        <a href="/admin/registrations" class="btn"> Записи</a>
    </div>
    
    <h1>👥 Все пользователи</h1>
    
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
                        <span class="badge {{ $user->role == 'admin' ? 'admin' : 'student' }}">
                            {{ $user->role == 'admin' ? 'Админ' : 'Студент' }}
                        </span>
                    </td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $users->links() }}
</body>
</html>