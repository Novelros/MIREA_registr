<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать консультацию - Админпанель</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; padding: 20px; }
        
        .admin-header { 
            background: linear-gradient(135deg, #2c3e50, #4a6491); 
            color: white; 
            padding: 20px; 
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .admin-nav { 
            background: #34495e; 
            padding: 10px; 
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .admin-nav a { 
            color: white; 
            text-decoration: none; 
            margin-right: 15px; 
            padding: 8px 15px; 
            border-radius: 4px;
        }
        
        .admin-nav a:hover { 
            background: rgba(255,255,255,0.1); 
        }
        
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group { 
            margin-bottom: 20px; 
        }
        
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600;
            color: #2c3e50;
        }
        
        input, select, textarea { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 6px;
            font-size: 16px;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn { 
            padding: 12px 24px; 
            background: #3498db; 
            color: white; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover { 
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #229954; }
        
        .btn-secondary { background: #95a5a6; }
        .btn-secondary:hover { background: #7f8c8d; }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            transform: scale(1.3);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .error { 
            color: #e74c3c; 
            font-size: 14px; 
            margin-top: 5px; 
        }
        
        .alert { 
            padding: 15px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
        }
        
        .alert-error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <h1>➕ Создать новую консультацию</h1>
    </header>
    
    <nav class="admin-nav">
        <a href="/admin"> Дашборд</a>
        <a href="/admin/consultations"> Консультации</a>
        <a href="/admin/registrations"> Записи</a>
        <a href="/consultations" target="_blank">🌐 Сайт</a>
        <a href="/admin/consultations" class="btn-secondary" style="float: right;">← Назад</a>
    </nav>
    
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-error">
                <h4 style="margin-bottom: 10px;">❌ Ошибки валидации:</h4>
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
                <button type="submit" class="btn btn-success">✅ Создать консультацию</button>
                <a href="/admin/consultations" class="btn btn-secondary">❌ Отмена</a>
            </div>
        </form>
    </div>
    
    <script>
        // Автоматическое заполнение времени окончания
        document.getElementById('start_time').addEventListener('change', function() {
            const startTime = new Date(this.value);
            const endTime = new Date(startTime.getTime() + 60 * 60 * 1000); // +1 час
            
            // Форматируем дату для input[type="datetime-local"]
            const endTimeString = endTime.toISOString().slice(0, 16);
            
            const endTimeInput = document.getElementById('end_time');
            if (!endTimeInput.value) {
                endTimeInput.value = endTimeString;
            }
        });
        
        // Автоматическая установка количества мест для индивидуальной консультации
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