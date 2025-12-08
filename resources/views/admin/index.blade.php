<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админпанель - РТУ МИРЭА</title>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3498db;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; color: #333; line-height: 1.6; }
        
        .admin-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 25px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .admin-title {
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .admin-menu {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            font-weight: 600;
        }
        
        .nav-link.logout {
            background: var(--danger);
        }
        
        .nav-link.logout:hover {
            background: #c0392b;
        }
        
        .admin-content {
            padding: 30px 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary);
            margin: 10px 0;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light);
        }
        
        .card-title {
            font-size: 22px;
            color: var(--primary);
            font-weight: 600;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: var(--info);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .btn-success { background: var(--success); }
        .btn-danger { background: var(--danger); }
        .btn-warning { background: var(--warning); }
        .btn-primary { background: var(--primary); }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--light);
        }
        
        th {
            background: var(--light);
            color: var(--dark);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }
        
        tr:hover {
            background: #f9f9f9;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #95a5a6;
        }
        
        .empty-state-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .pagination a, .pagination span {
            padding: 10px 18px;
            border: 1px solid var(--light);
            border-radius: 6px;
            text-decoration: none;
            color: var(--dark);
        }
        
        .pagination a:hover {
            background: var(--info);
            color: white;
            border-color: var(--info);
        }
        
        .pagination .active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .consultation-chart {
            margin-top: 30px;
        }
        
        .chart-bar {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .chart-label {
            width: 200px;
            font-weight: 500;
        }
        
        .chart-progress {
            flex: 1;
            height: 25px;
            background: var(--light);
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }
        
        .chart-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--info), var(--success));
            border-radius: 12px;
            transition: width 1s ease;
        }
        
        .chart-percent {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 600;
            color: var(--dark);
        }
        
        @media (max-width: 768px) {
            .admin-nav {
                flex-direction: column;
                gap: 20px;
            }
            
            .admin-menu {
                justify-content: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .chart-label {
                width: 150px;
            }
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="admin-container">
            <nav class="admin-nav">
                <div class="admin-title">
                    <span>👨‍💼</span>
                    <h1>Админпанель - Консультации РТУ МИРЭА</h1>
                </div>
                <div class="admin-menu">
                    <a href="/admin" class="nav-link active"> Дашборд</a>
                    <a href="/admin/consultations" class="nav-link"> Консультации</a>
                    <a href="/admin/registrations" class="nav-link"> Записи</a>
                    <a href="/admin/users" class="nav-link">👥 Пользователи</a>
                    <a href="/consultations" class="nav-link" target="_blank">🌐 Сайт</a>
                    <a href="{{ route('logout') }}" class="nav-link logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Выйти</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>
        </div>
    </header>
    
    <main class="admin-content">
        <div class="admin-container">
            @if(session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    ❌ {{ session('error') }}
                </div>
            @endif
            
            <h2 style="margin-bottom: 30px; color: var(--primary);">Добро пожаловать, {{ Auth::user()->name }}!</h2>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number">{{ $totalConsultations }}</div>
                    <div class="stat-label">Всего консультаций</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-number">{{ $activeConsultations }}</div>
                    <div class="stat-label">Активных</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number">{{ $totalRegistrations }}</div>
                    <div class="stat-label">Всего записей</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number">{{ $totalUsers }}</div>
                    <div class="stat-label">Пользователей</div>
                </div>
            </div>
            
            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"> Последние консультации</div>
                        <a href="/admin/consultations/create" class="btn btn-success">➕ Добавить</a>
                    </div>
                    
                    @if($recentConsultations->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Дата</th>
                                    <th>Записей</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentConsultations as $consultation)
                                    <tr>
                                        <td>
                                            <a href="/admin/consultation/{{ $consultation->id }}" style="color: var(--info); text-decoration: none;">
                                                {{ Str::limit($consultation->title, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ $consultation->start_time->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <span class="badge {{ $consultation->registrations_count > 0 ? 'badge-info' : 'badge-warning' }}">
                                                {{ $consultation->registrations_count }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $consultation->is_active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $consultation->is_active ? 'Активна' : 'Неактивна' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon"></div>
                            <p>Нет консультаций</p>
                        </div>
                    @endif
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"> Последние записи</div>
                        <a href="/admin/registrations" class="btn btn-primary">Все записи →</a>
                    </div>
                    
                    @if($recentRegistrations->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Студент</th>
                                    <th>Консультация</th>
                                    <th>Дата</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRegistrations as $registration)
                                    <tr>
                                        <td>{{ $registration->first_name }} {{ $registration->last_name }}</td>
                                        <td>{{ Str::limit($registration->consultation->title, 25) }}</td>
                                        <td>{{ $registration->created_at->format('d.m.Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon"></div>
                            <p>Нет записей</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="card-title"> Заполняемость консультаций</div>
                </div>
                
                <div class="consultation-chart">
                    @foreach($consultations as $consultation)
                        @php
                            $percent = $consultation->max_slots > 0 
                                ? min(100, round(($consultation->registrations_count / $consultation->max_slots) * 100))
                                : 0;
                        @endphp
                        <div class="chart-bar">
                            <div class="chart-label">
                                {{ Str::limit($consultation->title, 35) }}
                                <small style="color: #7f8c8d; display: block;">
                                    {{ $consultation->start_time->format('d.m') }}
                                </small>
                            </div>
                            <div class="chart-progress">
                                <div class="chart-fill" style="width: {{ $percent }}%"></div>
                                <div class="chart-percent">
                                    {{ $consultation->registrations_count }}/{{ $consultation->max_slots }} ({{ $percent }}%)
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($consultations->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon"></div>
                            <p>Нет данных для отображения</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>