<!DOCTYPE html>
<html>
<head>
    <title>Запись успешна</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 0 auto; padding: 20px; text-align: center; }
        .success { background: #2ecc71; color: white; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .info { background: #ecf0f1; padding: 15px; border-radius: 5px; text-align: left; }
    </style>
</head>
<body>
    <div class="success">
        <h2>✅ Запись успешно создана!</h2>
    </div>
    
    <div class="info">
        <h3>Детали записи:</h3>
        <p><strong>Консультация:</strong> {{ $registration->consultation->title }}</p>
        <p><strong>Дата и время:</strong> {{ $registration->consultation->start_time->format('d.m.Y H:i') }}</p>
        <p><strong>Студент:</strong> {{ $registration->first_name }} {{ $registration->last_name }}</p>
        <p><strong>Email:</strong> {{ $registration->email }}</p>
        <p><strong>Телефон:</strong> {{ $registration->phone }}</p>
    </div>
    
    <p><a href="/consultations">Вернуться к списку консультаций</a></p>
</body>
</html>