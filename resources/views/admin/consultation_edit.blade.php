<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать консультацию - Админпанель</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" alt="Герб РТУ МИРЭА" class="logo">
            <div class="header-content">
                <h1>Редактирование консультации</h1>
                <p class="subtitle">{{ $consultation->title }}</p>
            </div>
        </header>
            
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
                    <label class="checkbox-group">
                        <input type="checkbox" name="is_active" {{ $consultation->is_active ? 'checked' : '' }}>
                        Активна (доступна для записи)
                    </label>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" class="btn">Сохранить изменения</button>
                    <a href="/admin/consultations" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>