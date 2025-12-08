<!DOCTYPE html>
<html>
<head>
    <title>{{ $consultation->title }} - Админка</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .card { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; }
        th { background: #f5f5f5; }
        .btn { padding: 8px 16px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
    </style>
</head>
<body>
    <a href="/admin/consultations" class="btn">← Назад к списку</a>
    <a href="/admin" class="btn"> Дашборд</a>
    
    <div class="card">
        <h1>{{ $consultation->title }}</h1>
        <p><strong>Тип:</strong> {{ $consultation->type == 'individual' ? 'Индивидуальная' : 'Групповая' }}</p>
        <p><strong>Формат:</strong> {{ $consultation->format == 'online' ? 'Онлайн' : 'Очно' }}</p>
        <p><strong>Дата и время:</strong> {{ $consultation->start_time->format('d.m.Y H:i') }}</p>
        <p><strong>Максимум мест:</strong> {{ $consultation->max_slots }}</p>
        <p><strong>Записано:</strong> {{ $consultation->registered_count }}</p>
        <p><strong>Статус:</strong> {{ $consultation->is_active ? '✅ Активна' : '❌ Неактивна' }}</p>
    </div>
    
    <div class="card">
        <h2> Записи на эту консультацию ({{ $consultation->registrations->count() }})</h2>
        
        @if($consultation->registrations->count() > 0)
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
        @else
            <p>На эту консультацию еще никто не записался.</p>
        @endif
    </div>
</body>
</html>