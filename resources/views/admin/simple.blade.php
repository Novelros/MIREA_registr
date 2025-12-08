<!DOCTYPE html>
<html>
<head>
    <title>Админка - РТУ МИРЭА</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 20px; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .nav { background: #34495e; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; border-radius: 4px; }
        .nav a:hover { background: rgba(255,255,255,0.1); }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 5px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-number { font-size: 36px; font-weight: bold; color: #2c3e50; }
        .stat-label { color: #7f8c8d; margin-top: 10px; }
        .card { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9f9f9; }
        .btn { padding: 8px 16px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .badge { padding: 4px 8px; border-radius: 10px; font-size: 12px; }
        .badge.success { background: #2ecc71; color: white; }
        .badge.danger { background: #e74c3c; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>👨‍💼 Админпанель Консультации РТУ МИРЭА</h1>
        <p>Добро пожаловать, {{ Auth::user()->name }}!</p>
    </div>
    
    <div class="nav">
        <a href="/admin"> Статистика</a>
        <a href="/admin/consultations"> Консультации</a>
        <a href="/admin/registrations"> Все записи</a>
        <a href="/consultations" target="_blank">🌐 Сайт</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="background: #e74c3c;">🚪 Выйти</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    
    <div class="stats">
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
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="card">
            <h3> Последние консультации</h3>
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
                            <td><span class="badge {{ $c->registrations_count > 0 ? 'success' : 'danger' }}">{{ $c->registrations_count }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="/admin/consultations" class="btn" style="margin-top: 15px;">Все консультации →</a>
        </div>
        
        <div class="card">
            <h3> Последние записи</h3>
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
            <a href="/admin/registrations" class="btn" style="margin-top: 15px;">Все записи →</a>
        </div>
    </div>
</body>
</html>