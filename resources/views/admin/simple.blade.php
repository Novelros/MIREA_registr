<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админпанель - РТУ МИРЭА</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" alt="Герб РТУ МИРЭА" class="logo">
            <div class="header-content">
                <h1>Админпанель Консультаций</h1>
                <p class="subtitle">РТУ МИРЭА</p>
                <p class="user-greeting">Добро пожаловать, {{ Auth::user()->name }}!</p>
            </div>
        </header>
        
        <nav class="admin-nav">
            <a href="/admin" class="active">Статистика</a>
            <a href="/admin/consultations">Консультации</a>
            <a href="/admin/registrations">Все записи</a>
            <a href="/consultations" target="_blank">Сайт</a>
            <a href="{{ route('logout') }}" class="nav-link logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
        
        <div class="admin-stats">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['consultations'] }}</div>
                <div class="stat-label">Консультаций</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['active_consultations'] }}</div>
                <div class="stat-label">Активных</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['registrations'] }}</div>
                <div class="stat-label">Всего записей</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['users'] }}</div>
                <div class="stat-label">Пользователей</div>
            </div>
        </div>
        
        <div class="admin-grid">
            <div class="admin-card">
                <div class="card-header">
                    <div class="card-title">Последние консультации</div>
                    <a href="/admin/consultations" class="btn btn-primary">Все консультации →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Дата</th>
                            <th>Записей</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consultations as $c)
                            <tr>
                                <td><a href="/admin/consultation/{{ $c->id }}">{{ $c->title }}</a></td>
                                <td>{{ $c->start_time->format('d.m.Y') }}</td>
                                <td><span class="badge {{ $c->registrations_count > 0 ? 'badge-success' : 'badge-danger' }}">{{ $c->registrations_count }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="admin-card">
                <div class="card-header">
                    <div class="card-title">Последние записи</div>
                    <a href="/admin/registrations" class="btn btn-primary">Все записи →</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Студент</th>
                            <th>Консультация</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRegistrations as $r)
                            <tr>
                                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                                <td>{{ Str::limit($r->consultation->title, 25) }}</td>
                                <td>{{ $r->created_at->format('d.m.Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>