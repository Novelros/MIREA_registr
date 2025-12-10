<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $consultation->title }} - Админпанель</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" alt="Герб РТУ МИРЭА" class="logo">
            <div class="header-content">
                <h1>Детали консультации</h1>
                <p class="subtitle">{{ $consultation->title }}</p>
            </div>
        </header>
        <div style="margin-bottom: 20px;">
            <a href="/admin/consultations" class="btn">Назад к списку</a>
            <a href="/admin" class="btn">Дашборд</a>
        </div>
        
        <div class="card admin-card">
            <h1>{{ $consultation->title }}</h1>
            <p><strong>Тип:</strong> {{ $consultation->type == 'individual' ? 'Индивидуальная' : 'Групповая' }}</p>
            <p><strong>Формат:</strong> {{ $consultation->format == 'online' ? 'Онлайн' : 'Очно' }}</p>
            <p><strong>Дата и время:</strong> {{ $consultation->start_time->format('d.m.Y H:i') }}</p>
            <p><strong>Максимум мест:</strong> {{ $consultation->max_slots }}</p>
            <p><strong>Записано:</strong> {{ $consultation->registered_count }}</p>
            <p><strong>Статус:</strong> {{ $consultation->is_active ? '✅ Активна' : '❌ Неактивна' }}</p>
        </div>
        
        <div class="card admin-card">
            <h2>Записи на эту консультацию ({{ $consultation->registrations->count() }})</h2>
            
            @if($consultation->registrations->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Фамилия</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Дата записи</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultation->registrations as $r)
                                <tr>
                                    <td>{{ $r->first_name }}</td>
                                    <td>{{ $r->last_name }}</td>
                                    <td>{{ $r->email }}</td>
                                    <td>{{ $r->phone }}</td>
                                    <td>{{ $r->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>На эту консультацию еще никто не записался.</p>
            @endif
        </div>
    </div>
</body>
</html>