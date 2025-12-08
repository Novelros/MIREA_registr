<!DOCTYPE html>
<html>
<head>
    <title>Запись на консультацию</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 0 auto; padding: 20px; }
        h1 { color: #2c3e50; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { background: #3498db; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .error { color: #e74c3c; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
    <h1>📝 Запись на консультацию</h1>
    <h3>{{ $consultation->title }}</h3>
    <p><strong>Время:</strong> {{ $consultation->start_time->format('d.m.Y H:i') }}</p>
    <p><strong>Осталось мест:</strong> {{ $consultation->availableSlots() }}</p>
    
    <form method="POST" action="/registration">
        @csrf
        <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">
        
        <div class="form-group">
            <label>Имя *</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}" required>
            @error('first_name') <div class="error">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
            <label>Фамилия *</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}" required>
            @error('last_name') <div class="error">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
            <label>Телефон *</label>
            <input type="tel" name="phone" value="{{ old('phone') }}" required>
            @error('phone') <div class="error">{{ $message }}</div> @enderror
        </div>
        
        <button type="submit" class="btn">Записаться</button>
        <a href="/consultations" style="margin-left: 10px;">Отмена</a>
    </form>
</body>
</html>