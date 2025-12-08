<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои записи - РТУ МИРЭА</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .registrations-list { margin: 20px 0; }
        .registration-item { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .btn { background: #3498db; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .btn-admin { background: #e74c3c; }
        .nav-links { margin: 20px 0; }
        .nav-links a { margin-right: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>👤 Мои записи на консультации</h1>
        <p>Добро пожаловать, {{ $user->name }}!</p>
        <p>Email: {{ $user->email }}</p>
    </div>
    
    <div class="nav-links">
        <a href="/consultations" class="btn"> Все консультации</a>
        @if(Auth::user()->isAdmin())
            <a href="/admin" class="btn btn-admin"> Админпанель</a>
        @endif
        <a href="{{ route('logout') }}" class="btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            🔴 Выйти
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    
    <h2> Мои записи:</h2>
    
    <div class="registrations-list">
        @foreach($registrations as $registration)
            <div class="registration-item">
                <h3>{{ $registration->consultation->title }}</h3>
                <p><strong>Тип:</strong> {{ $registration->consultation->type == 'individual' ? 'Индивидуальная' : 'Групповая' }}</p>
                <p><strong>Формат:</strong> {{ $registration->consultation->format == 'online' ? 'Онлайн' : 'Очно' }}</p>
                <p><strong>Дата и время:</strong> 
                    @if($registration->consultation->start_time instanceof \Carbon\Carbon)
                        {{ $registration->consultation->start_time->format('d.m.Y H:i') }}
                    @else
                        {{ date('d.m.Y H:i', strtotime($registration->consultation->start_time)) }}
                    @endif
                </p>
                <p><strong>Записан как:</strong> {{ $registration->first_name }} {{ $registration->last_name }}</p>
                <p><strong>Email для связи:</strong> {{ $registration->email }}</p>
                <p><strong>Телефон:</strong> {{ $registration->phone }}</p>
                <p><strong>Дата записи:</strong> {{ $registration->created_at->format('d.m.Y H:i') }}</p>
            </div>
        @endforeach
        
        @if($registrations->isEmpty())
            <p>У вас нет записей на консультации.</p>
            <a href="/consultations" class="btn">Записаться на консультацию</a>
        @endif
    </div>
</body>
</html>