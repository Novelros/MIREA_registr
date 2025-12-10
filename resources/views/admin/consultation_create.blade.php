<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать консультацию - Админпанель</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" alt="Герб РТУ МИРЭА" class="logo">
            <div class="header-content">
                <h1>Создание новой консультации</h1>
                <p class="user-greeting">Заполните форму для создания консультации</p>
            </div>
        </header>
        
        <nav class="admin-nav">
            <a href="/admin">Дашборд</a>
            <a href="/admin/consultations">Консультации</a>
            <a href="/admin/registrations">Записи</a>
            <a href="/consultations" target="_blank">Сайт</a>
            <a href="/admin/consultations" class="btn btn-secondary" style="float: right;">Назад</a>
        </nav>
        
        <div class="container admin-form-container">
            @if ($errors->any())
                <div class="alert alert-error">
                    <h4 style="margin-bottom: 10px;">Ошибки валидации:</h4>
                    <ul style="margin-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.consultations.store') }}">
                @csrf
                
                <div class="form-group">
                    <label for="title">Название консультации *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Например: Консультация по программированию">
                </div>
                
                <div class="form-group">
                    <label for="description">Описание (необязательно)</label>
                    <textarea id="description" name="description" placeholder="Детальное описание консультации...">{{ old('description') }}</textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="type">Тип консультации *</label>
                        <select id="type" name="type" required>
                            <option value="">Выберите тип</option>
                            <option value="individual" {{ old('type') == 'individual' ? 'selected' : '' }}>Индивидуальная</option>
                            <option value="group" {{ old('type') == 'group' ? 'selected' : '' }}>Групповая</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="format">Формат проведения *</label>
                        <select id="format" name="format" required>
                            <option value="">Выберите формат</option>
                            <option value="online" {{ old('format') == 'online' ? 'selected' : '' }}>Онлайн</option>
                            <option value="offline" {{ old('format') == 'offline' ? 'selected' : '' }}>Очно</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="start_time">Дата и время начала *</label>
                        <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="end_time">Дата и время окончания (необязательно)</label>
                        <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="max_slots">Максимальное количество мест *</label>
                        <input type="number" id="max_slots" name="max_slots" value="{{ old('max_slots', 1) }}" min="1" required>
                        <small style="color: #7f8c8d; margin-top: 5px; display: block;">
                            Для индивидуальных консультаций установите 1
                        </small>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" style="margin: 0;">Сделать консультацию активной (доступной для записи)</label>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit" class="btn btn-success">Создать консультацию</button>
                    <a href="/admin/consultations" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById('start_time').addEventListener('change', function() {
            const startTime = new Date(this.value);
            const endTime = new Date(startTime.getTime() + 60 * 60 * 1000);
            
            const endTimeString = endTime.toISOString().slice(0, 16);
            
            const endTimeInput = document.getElementById('end_time');
            if (!endTimeInput.value) {
                endTimeInput.value = endTimeString;
            }
        });
        
        document.getElementById('type').addEventListener('change', function() {
            const maxSlotsInput = document.getElementById('max_slots');
            if (this.value === 'individual') {
                maxSlotsInput.value = 1;
                maxSlotsInput.min = 1;
                maxSlotsInput.max = 1;
            } else {
                maxSlotsInput.min = 1;
                maxSlotsInput.max = 100;
                if (maxSlotsInput.value === '1') {
                    maxSlotsInput.value = 5;
                }
            }
        });
    </script>
</body>
</html>