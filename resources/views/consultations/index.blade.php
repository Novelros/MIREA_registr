<!DOCTYPE html>
<html>
<head>
    <title>Доступные консультации</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 1000px; 
            margin: 0 auto; 
            padding: 20px; 
            background-color: #f8f9fa;
        }
        .header { 
            display: flex;
            align-items: center;
            gap: 20px;
            background: linear-gradient(135deg, #2c3e50, #4a6491); 
            color: white; 
            padding: 20px; 
            border-radius: 10px; 
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .logo {
            height: 60px;
            width: auto;
            display: block;
            filter: brightness(0) invert(1); /* Делаем лого белым на темном фоне */
        }
        .header-text {
            flex-grow: 1;
        }
        .header-text h1 {
            margin: 0 0 5px 0;
            font-size: 22px;
        }
        .consultation { 
            background: white;
            border: 1px solid #ddd; 
            padding: 20px; 
            margin: 15px 0; 
            border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .consultation:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn { 
            background: #3498db; 
            color: white; 
            padding: 10px 20px; 
            text-decoration: none; 
            border-radius: 4px; 
            display: inline-block;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }
        .btn:hover { 
            background: #2980b9; 
            text-decoration: none;
        }
        .btn.disabled { 
            background: #95a5a6; 
            cursor: not-allowed; 
        }
        .badge { 
            background: #2ecc71; 
            color: white; 
            padding: 4px 10px; 
            border-radius: 12px; 
            font-size: 12px; 
            font-weight: bold;
            display: inline-block;
        }
        .badge.full { 
            background: #e74c3c; 
        }
        .error { 
            background: #ffeaea; 
            color: #e74c3c; 
            padding: 12px; 
            border-radius: 5px; 
            margin: 15px 0; 
            border-left: 4px solid #e74c3c;
        }
        .info { 
            background: #e8f4f8; 
            color: #0c5460; 
            padding: 12px; 
            border-radius: 5px; 
            margin: 15px 0; 
            border-left: 4px solid #3498db;
        }
        .nav-links { 
            margin: 25px 0; 
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .consultation h3 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .consultation-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin: 15px 0;
        }
        .detail-item {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .consultation-actions {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
            // Проверяем логотип в разных местах
            $logoPath = '';
            $paths = [
                public_path('img/MIREA_Gerb_Colour.png'),
                base_path('img/MIREA_Gerb_Colour.png'),
                base_path('public/img/MIREA_Gerb_Colour.png'),
            ];
            
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    $logoPath = str_replace(public_path(), '', $path);
                    break;
                }
            }
        @endphp
        
        @if($logoPath)
            <img src="/img/MIREA_Gerb_Colour.png" 
             alt="Логотип РТУ МИРЭА" 
             class="logo">
        @else
            <!-- Запасной вариант -->
            <div style="width: 60px; height: 60px; background: white; border-radius: 5px; 
                      display: flex; align-items: center; justify-content: center; color: #2c3e50; font-weight: bold;">
                РТУ
            </div>
        @endif
        
        <div class="header-text">
            <h1>Доступные консультации</h1>
            <p>Система записи на консультации РТУ МИРЭА</p>
        </div>
    </div>
    
    <div class="nav-links">
        @if(Auth::check())
            <a href="/my-registrations" class="btn" style="background: #27ae60;">Мои записи</a>
            @if(Auth::user()->isAdmin())
                <a href="/admin" class="btn" style="background: #e74c3c;">Админпанель</a>
            @endif
            <a href="{{ route('logout') }}" class="btn" style="background: #ff0000ff;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Выйти
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <div class="info">
                Для записи на консультации необходимо <a href="{{ route('login') }}">войти в систему</a>.
            </div>
        @endif
        <a href="/" class="btn" style="background: #7f8c8d;">На главную</a>
    </div>
    
    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif
    
    @if(session('success'))
        <div class="info">{{ session('success') }}</div>
    @endif
    
    @foreach($consultations as $consultation)
        <div class="consultation">
            <h3>{{ $consultation->title }}</h3>
            
            <div class="consultation-details">
                <div class="detail-item">
                    <strong>Тип:</strong> {{ $consultation->type == 'individual' ? 'Индивидуальная' : 'Групповая' }}
                </div>
                <div class="detail-item">
                    <strong>Формат:</strong> {{ $consultation->format == 'online' ? 'Онлайн' : 'Очно' }}
                </div>
                <div class="detail-item">
                    <strong>Время:</strong> {{ $consultation->start_time->format('d.m.Y H:i') }}
                </div>
                <div class="detail-item">
                    <strong>Свободных мест:</strong> 
                    <span class="badge {{ $consultation->hasAvailableSlots() ? '' : 'full' }}">
                        {{ $consultation->availableSlots() }} / {{ $consultation->max_slots }}
                    </span>
                </div>
            </div>
            
            <div class="consultation-actions">
                @if(Auth::check())
                    @if($consultation->hasAvailableSlots())
                        <a href="/registration/{{ $consultation->id }}" class="btn">Записаться</a>
                    @else
                        <button class="btn disabled" disabled>Нет мест</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn" style="background: #f39c12;">Войти для записи</a>
                @endif
            </div>
        </div>
    @endforeach
    
    @if($consultations->isEmpty())
        <div class="info" style="text-align: center; padding: 40px;">
            <p style="font-size: 16px; margin: 0;">На данный момент нет доступных консультаций.</p>
            <p style="margin-top: 10px;">Пожалуйста, проверьте позже.</p>
        </div>
    @endif
</body>
</html>