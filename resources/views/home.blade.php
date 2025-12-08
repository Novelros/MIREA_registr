<!DOCTYPE html>
<html>
<head>
    <title>Личный кабинет - РТУ МИРЭА</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .btn { background: #3498db; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 5px; }
        .btn:hover { background: #2980b9; }
        .btn-admin { background: #e74c3c; }
    </style>
</head>
<body>
    <div class="header">
        <h1>👤 Личный кабинет</h1>
        <p>Добро пожаловать, {{ Auth::user()->name }}!</p>
        <p>Ваша роль: <strong>{{ Auth::user()->isAdmin() ? 'Администратор' : 'Студент' }}</strong></p>
    </div>
    
    <h2>Быстрые ссылки:</h2>
    
    <div>
        <a href="/consultations" class="btn"> Все консультации</a>
        <a href="/my-registrations" class="btn"> Мои записи</a>
        
        @if(Auth::user()->isAdmin())
            <a href="/admin" class="btn btn-admin"> Админпанель</a>
        @endif
        
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn" style="background: #95a5a6;">🔴 Выйти</button>
        </form>
    </div>
    
    <div style="margin-top: 30px; background: #f9f9f9; padding: 20px; border-radius: 5px;">
        <h3> Ваша статистика:</h3>
        <p>Email: {{ Auth::user()->email }}</p>
        <p>Телефон: {{ Auth::user()->phone }}</p>
        <p>Дата регистрации: {{ Auth::user()->created_at->format('d.m.Y') }}</p>
    </div>
</body>
</html>