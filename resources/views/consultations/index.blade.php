<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доступные консультации - РТУ МИРЭА</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/consultations.css') }}">
</head>
<body>
    <div class="consultations-container">
        <header class="consultations-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" 
                 alt="Герб РТУ МИРЭА" 
                 class="logo"
                 onerror="this.src='https://via.placeholder.com/70x70/2c3e50/ffffff?text=MIREA'">
            <div class="header-content-consultations">
                <h1>Доступные консультации</h1>
                <p class="subtitle">Система записи на консультации</p>
                <p class="additional-text">РТУ МИРЭА</p>
            </div>
        </header>
        
        <div class="nav-links-consultations">
            @if(Auth::check())
                <a href="/my-registrations" class="btn-consultations btn-success">Мои записи</a>
                @if(Auth::user()->isAdmin())
                    <a href="/admin" class="btn-consultations btn-danger">Админпанель</a>
                @endif
                <a href="{{ route('logout') }}" class="btn-consultations btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Выйти
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-consultations btn-warning">Войти в систему</a>
            @endif
            <a href="/" class="btn-consultations btn-secondary">На главную</a>
        </div>
        
        @if(session('error'))
            <div class="consultation-notification notification-error">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="consultation-notification notification-success">
                {{ session('success') }}
            </div>
        @endif
        
        @foreach($consultations as $consultation)
            <div class="consultation-card">
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
                        <span class="consultation-badge {{ $consultation->hasAvailableSlots() ? 'badge-success' : 'badge-danger' }}">
                            {{ $consultation->availableSlots() }} / {{ $consultation->max_slots }}
                        </span>
                    </div>
                </div>
                
                <div class="consultation-actions">
                    @if(Auth::check())
                        @if($consultation->hasAvailableSlots())
                            <a href="/registration/{{ $consultation->id }}" class="btn-consultations btn-primary">Записаться</a>
                        @else
                            <button class="btn-consultations btn-disabled" disabled>Нет свободных мест</button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-consultations btn-warning">Войти для записи</a>
                    @endif
                </div>
            </div>
        @endforeach
        
        @if($consultations->isEmpty())
            <div class="empty-consultations">
                <h3>Нет доступных консультаций</h3>
                <p>На данный момент нет доступных консультаций.</p>
                <p>Пожалуйста, проверьте позже.</p>
            </div>
        @endif
    </div>
</body>
</html>