<!DOCTYPE html>
<html>
<head>
    <title>Редактировать консультацию - Админка</title>
    <style>
        body { font-family: Arial; padding: 20px; max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .checkbox { width: auto; }
    </style>
</head>
<body>
    <h1>✏️ Редактировать консультацию</h1>
    
    <form method="POST" action="{{ route('admin.consultation.update', $consultation->id) }}">
        @csrf
        
        <div class="form-group">
            <label>Название консультации *</label>
            <input type="text" name="title" value="{{ $consultation->title }}" required>
        </div>
        
        <div class="form-group">
            <label>Тип *</label>
            <select name="type" required>
                <option value="individual" {{ $consultation->type == 'individual' ? 'selected' : '' }}>Индивидуальная</option>
                <option value="group" {{ $consultation->type == 'group' ? 'selected' : '' }}>Групповая</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Формат *</label>
            <select name="format" required>
                <option value="online" {{ $consultation->format == 'online' ? 'selected' : '' }}>Онлайн</option>
                <option value="offline" {{ $consultation->format == 'offline' ? 'selected' : '' }}>Очно</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Дата и время *</label>
            <input type="datetime-local" name="start_time" value="{{ $consultation->start_time->format('Y-m-d\TH:i') }}" required>
        </div>
        
        <div class="form-group">
            <label>Количество мест *</label>
            <input type="number" name="max_slots" value="{{ $consultation->max_slots }}" min="1" required>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" class="checkbox" {{ $consultation->is_active ? 'checked' : '' }}>
                Активна (доступна для записи)
            </label>
        </div>
        
        <button type="submit" class="btn">Сохранить изменения</button>
        <a href="/admin/consultations" class="btn" style="background: #95a5a6; margin-left: 10px;">Отмена</a>
    </form>
</body>
</html>