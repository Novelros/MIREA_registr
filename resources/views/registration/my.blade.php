<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои записи - РТУ МИРЭА</title>
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
                <h1>Мои записи на консультации</h1>
                <p class="subtitle">Система записи на консультации РТУ МИРЭА</p>
            </div>
        </header>
        
        <div class="registration-message message-info">
            <p style="margin: 0;">
                <strong>Добро пожаловать, {{ $user->name }}!</strong> Ваш email: {{ $user->email }}
            </p>
        </div>
        
        <div class="registration-nav-links">
            <a href="/consultations" class="registration-btn btn-primary">Все консультации</a>
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
        
        <div class="registrations-list-container">
            <h2 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 22px; font-weight: 600; padding-bottom: 15px; border-bottom: 2px solid #3498db;">
                Мои записи:
            </h2>
            
            @foreach($registrations as $registration)
                <div class="registration-item">
                    <h3>{{ $registration->consultation->title }}</h3>
                    
                    <div class="registration-item-details">
                        <div class="detail-item-info">
                            <strong>Тип:</strong> {{ $registration->consultation->type == 'individual' ? 'Индивидуальная' : 'Групповая' }}
                        </div>
                        <div class="detail-item-info">
                            <strong>Формат:</strong> {{ $registration->consultation->format == 'online' ? 'Онлайн' : 'Очно' }}
                        </div>
                        <div class="detail-item-info">
                            <strong>Дата и время:</strong> 
                            @if($registration->consultation->start_time instanceof \Carbon\Carbon)
                                {{ $registration->consultation->start_time->format('d.m.Y H:i') }}
                            @else
                                {{ date('d.m.Y H:i', strtotime($registration->consultation->start_time)) }}
                            @endif
                        </div>
                        <div class="detail-item-info">
                            <strong>Записан как:</strong> {{ $registration->first_name }} {{ $registration->last_name }}
                        </div>
                        <div class="detail-item-info">
                            <strong>Email для связи:</strong> {{ $registration->email }}
                        </div>
                        <div class="detail-item-info">
                            <strong>Телефон:</strong> {{ $registration->phone }}
                        </div>
                        <div class="detail-item-info">
                            <strong>Дата записи:</strong> {{ $registration->created_at->format('d.m.Y H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if($registrations->isEmpty())
                <div class="empty-registrations">
                    <h3>У вас нет записей на консультации</h3>
                    <p>Вы еще не записались ни на одну консультацию.</p>
                    <a href="/consultations" class="registration-btn btn-primary">Записаться на консультацию</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>