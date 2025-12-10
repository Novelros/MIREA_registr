<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запись на консультацию - РТУ МИРЭА</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registration.css') }}">
</head>
<body>
    <div class="registration-container">
        <header class="registration-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" 
                 alt="Герб РТУ МИРЭА" 
                 class="logo"
                 onerror="this.src='https://via.placeholder.com/70x70/2c3e50/ffffff?text=MIREA'">
            <div class="registration-header-content">
                <h1>Запись на консультацию</h1>
                <p class="subtitle">Система записи на консультации РТУ МИРЭА</p>
            </div>
        </header>
        
        <div class="registration-nav-links">
            <a href="/consultations" class="registration-btn btn-secondary">Все консультации</a>
            <a href="/my-registrations" class="registration-btn btn-success">Мои записи</a>
            @if(Auth::user()->isAdmin())
                <a href="/admin" class="registration-btn btn-danger">Админпанель</a>
            @endif
            <a href="{{ route('logout') }}" class="registration-btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Выйти
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        
        @if(Auth::check())
            <div class="registration-message message-success">
                ✅ Вы авторизованы как <strong>{{ Auth::user()->email }}</strong>. Email будет автоматически использован для записи.
            </div>
        @endif
        
        <div class="consultation-info-container">
            <h2>{{ $consultation->title }}</h2>
            <div class="consultation-details-info">
                <div class="detail-item-info">
                    <strong>Время:</strong> 
                    @if($consultation->start_time instanceof \Carbon\Carbon)
                        {{ $consultation->start_time->format('d.m.Y H:i') }}
                    @else
                        {{ date('d.m.Y H:i', strtotime($consultation->start_time)) }}
                    @endif
                </div>
                <div class="detail-item-info">
                    <strong>Осталось мест:</strong> {{ $consultation->availableSlots() }}
                </div>
                <div class="detail-item-info">
                    <strong>Тип:</strong> {{ $consultation->type == 'individual' ? 'Индивидуальная' : 'Групповая' }}
                </div>
                <div class="detail-item-info">
                    <strong>Формат:</strong> {{ $consultation->format == 'online' ? 'Онлайн' : 'Очно' }}
                </div>
            </div>
        </div>
        
        @if ($errors->any())
            <div class="registration-message message-error">
                <h4 style="margin-top: 0;">Ошибки валидации:</h4>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="registration-form-container">
            <form method="POST" action="/registration" id="registrationForm">
                @csrf
                <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">
                
                <div class="form-group">
                    <label for="first_name">Имя *</label>
                    <input type="text" id="first_name" name="first_name" 
                           value="{{ old('first_name', $userData['first_name'] ?? '') }}" 
                           pattern="[a-zA-Zа-яА-ЯёЁ\s\-]+"
                           title="Только буквы, пробелы и дефисы"
                           required>
                    <div class="form-hint">Только буквы, пробелы и дефисы</div>
                    @error('first_name') <div class="validation-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="last_name">Фамилия *</label>
                    <input type="text" id="last_name" name="last_name" 
                           value="{{ old('last_name', $userData['last_name'] ?? '') }}" 
                           pattern="[a-zA-Zа-яА-ЯёЁ\s\-]+"
                           title="Только буквы, пробелы и дефисы"
                           required>
                    <div class="form-hint">Только буквы, пробелы и дефисы</div>
                    @error('last_name') <div class="validation-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    @if(Auth::check())
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" readonly required>
                        <div class="form-hint">Email взят из вашего профиля</div>
                    @else
                        <input type="email" id="email" name="email" value="{{ old('email', $userData['email'] ?? '') }}" required>
                    @endif
                    @error('email') <div class="validation-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label for="phone">Телефон *</label>
                    <input type="tel" id="phone" name="phone"
                           placeholder="+7(___)___-__-__"
                           value="{{ old('phone', $userData['phone'] ?? '') }}" 
                           required
                           data-pattern="\+7\(\d{3}\)\d{3}-\d{2}-\d{2}">
                    <div class="form-hint">Формат: +7(912)345-67-89</div>
                    @error('phone') <div class="validation-error">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-note">
                    Поля, отмеченные * обязательны для заполнения
                </div>
                
                <div style="display: flex; gap: 15px; margin-top: 25px;">
                    <button type="submit" class="registration-btn btn-primary">Записаться на консультацию</button>
                    <a href="/consultations" class="registration-btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
        
        @if(!Auth::check())
            <div class="registration-message message-info">
                <p style="margin: 0;">
                    <strong>💡 Совет:</strong> 
                    <a href="{{ route('login') }}">Войдите в систему</a>, чтобы ваш email автоматически заполнялся в форме.
                </p>
            </div>
        @endif
    </div>
    
    <script>
        // Маска для телефона
        document.getElementById('phone').addEventListener('input', function(e) {
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
        document.getElementById('phone').addEventListener('keydown', function(e) {
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
        document.getElementById('phone').addEventListener('focus', function(e) {
            if (!this.value) {
                this.value = '+7(';
                this.setSelectionRange(3, 3);
            }
        });
        
        // Предотвращаем отправку формы
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('phone');
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