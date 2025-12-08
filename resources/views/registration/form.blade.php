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
        input[readonly] { background: #f5f5f5; color: #666; }
        .btn { background: #3498db; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .error { color: #e74c3c; font-size: 14px; margin-top: 5px; }
        .text-danger { color: #e74c3c; }
        .info-message { background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .small-text { font-size: 12px; color: #666; margin-top: 3px; }
        .form-note { font-size: 13px; color: #7f8c8d; font-style: italic; }
    </style>
</head>
<body>
    <h1> Запись на консультацию</h1>
    
    @if(Auth::check())
        <div class="info-message">
            ✅ Вы авторизованы как <strong>{{ Auth::user()->email }}</strong>. Email будет автоматически использован для записи.
        </div>
    @endif
    
    <h3>{{ $consultation->title }}</h3>
    <p><strong>Время:</strong> 
        @if($consultation->start_time instanceof \Carbon\Carbon)
            {{ $consultation->start_time->format('d.m.Y H:i') }}
        @else
            {{ date('d.m.Y H:i', strtotime($consultation->start_time)) }}
        @endif
    </p>
    <p><strong>Осталось мест:</strong> {{ $consultation->availableSlots() }}</p>
    
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="/registration" id="registrationForm">
        @csrf
        <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">
        
        <div class="form-group">
            <label>Имя *</label>
            <input type="text" name="first_name" 
                   value="{{ old('first_name', $userData['first_name'] ?? '') }}" 
                   pattern="[a-zA-Zа-яА-ЯёЁ\s\-]+"
                   title="Только буквы, пробелы и дефисы"
                   required>
            <div class="small-text">Только буквы, пробелы и дефисы</div>
            @error('first_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
            <label>Фамилия *</label>
            <input type="text" name="last_name" 
                   value="{{ old('last_name', $userData['last_name'] ?? '') }}" 
                   pattern="[a-zA-Zа-яА-ЯёЁ\s\-]+"
                   title="Только буквы, пробелы и дефисы"
                   required>
            <div class="small-text">Только буквы, пробелы и дефисы</div>
            @error('last_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            @if(Auth::check())
                <input type="email" name="email" value="{{ Auth::user()->email }}" readonly required>
                <small style="color: #666;">Email взят из вашего профиля</small>
            @else
                <input type="email" name="email" value="{{ old('email', $userData['email'] ?? '') }}" required>
            @endif
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group">
            <label>Телефон *</label>
            <input type="tel" name="phone" 
                   id="phoneInput"
                   placeholder="+7(___)___-__-__"
                   value="{{ old('phone', $userData['phone'] ?? '') }}" 
                   required
                   data-pattern="\+7\(\d{3}\)\d{3}-\d{2}-\d{2}">
            <div class="small-text">Формат: +7(912)345-67-89</div>
            @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        
        <div class="form-group form-note">
            Поля, отмеченные * обязательны для заполнения
        </div>
        
        <button type="submit" class="btn">Записаться</button>
        <a href="/consultations" style="margin-left: 10px;">Отмена</a>
    </form>
    
    @if(!Auth::check())
        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
            <p style="margin: 0; font-size: 14px;">
                <strong>💡 Совет:</strong> 
                <a href="{{ route('login') }}">Войдите в систему</a>, чтобы ваш email автоматически заполнялся в форме.
            </p>
        </div>
    @endif

    <script>
        // Маска для телефона
        document.getElementById('phoneInput').addEventListener('input', function(e) {
            let input = e.target;
            let value = input.value.replace(/\D/g, ''); 
            
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            // Форматируем только если есть цифры после кода страны
            if (value.length > 1) {
                let formatted = '+7(';
                
                formatted += value.substring(1, 4);
                
                formatted += ')';
                if (value.length > 4) {
                    formatted += value.substring(4, 7);
                }
                formatted += '-';
                if (value.length > 7) {
                    formatted += value.substring(7, 9);
                }
                formatted += '-';
                if (value.length > 9) {
                    formatted += value.substring(9, 11);
                }
                
                input.value = formatted;
            } else if (value.length === 1) {
                input.value = '+7';
            }
        });
        
        // Разрешаем только цифры и управляющие клавиши
        document.getElementById('phoneInput').addEventListener('keydown', function(e) {
            const allowedKeys = [
                8,  // backspace
                9,  // tab
                13, // enter
                27, // escape
                37, // стрелка влево
                38, // стрелка вверх
                39, // стрелка вправо
                40, // стрелка вниз
                46, // delete
                110 // точка (на numpad)
            ];
            
            if ((e.ctrlKey || e.metaKey) && [65, 67, 86, 88].includes(e.keyCode)) {
                return;
            }
            
            // Запрещаем ввод
            if (!allowedKeys.includes(e.keyCode) && 
                (e.keyCode < 48 || e.keyCode > 57) && 
                (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        
        // Фокус на поле телефона, если пустое, показываем шаблон
        document.getElementById('phoneInput').addEventListener('focus', function(e) {
            if (!this.value) {
                this.value = '+7(';
                this.setSelectionRange(3, 3);
            }
        });
        
        // Предотвращаем отправку формы
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('phoneInput');
            const phonePattern = phoneInput.getAttribute('data-pattern');
            const regex = new RegExp('^' + phonePattern + '$');
            
            if (!regex.test(phoneInput.value)) {
                alert('Пожалуйста, введите телефон в формате +7(XXX)XXX-XX-XX');
                phoneInput.focus();
                e.preventDefault();
            }
        });
    </script>
</body>
</html>