<!DOCTYPE html>
<html>
<head>
    <title>Доступные консультации</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #2c3e50; }
        .consultation { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .btn { background: #3498db; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .btn.disabled { background: #95a5a6; cursor: not-allowed; }
        .badge { background: #2ecc71; color: white; padding: 3px 8px; border-radius: 10px; font-size: 12px; }
        .badge.full { background: #e74c3c; }
    </style>
</head>
<body>
    <h1>📅 Доступные консультации</h1>
    
    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif
    
    @foreach($consultations as $consultation)
        <div class="consultation">
            <h3>{{ $consultation->title }}</h3>
            <p><strong>Тип:</strong> {{ $consultation->type == 'individual' ? 'Индивидуальная' : 'Групповая' }}</p>
            <p><strong>Формат:</strong> {{ $consultation->format == 'online' ? 'Онлайн' : 'Очно' }}</p>
            <p><strong>Время:</strong> {{ $consultation->start_time->format('d.m.Y H:i') }}</p>
            <p><strong>Свободных мест:</strong> 
                <span class="badge {{ $consultation->hasAvailableSlots() ? '' : 'full' }}">
                    {{ $consultation->availableSlots() }} / {{ $consultation->max_slots }}
                </span>
            </p>
            
            @if($consultation->hasAvailableSlots())
                <a href="/registration/{{ $consultation->id }}" class="btn">Записаться</a>
            @else
                <button class="btn disabled" disabled>Нет мест</button>
            @endif
        </div>
    @endforeach
    
    @if($consultations->isEmpty())
        <p>На данный момент нет доступных консультаций.</p>
    @endif
    
    <p><a href="/">← На главную</a></p>
</body>
</html>