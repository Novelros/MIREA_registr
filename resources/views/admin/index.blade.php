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
                <p class="additional-text">Панель управления системой консультаций</p>
            </div>
        </header>
                <div class="admin-menu">
                    <a href="/admin" class="nav-link active">Дашборд</a>
                    <a href="/admin/consultations" class="nav-link">Консультации</a>
                    <a href="/admin/registrations" class="nav-link">Записи</a>
                    <a href="/admin/users" class="nav-link">Пользователи</a>
                    <a href="/consultations" class="nav-link" target="_blank">Сайт</a>
                    <a href="{{ route('logout') }}" class="nav-link logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выйти</a>
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
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            <h2 class="welcome-title">Добро пожаловать, {{ Auth::user()->name }}!</h2>
            
            <div class="admin-stats">
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number">{{ $totalConsultations }}</div>
                    <div class="stat-label">Всего консультаций</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number">{{ $activeConsultations }}</div>
                    <div class="stat-label">Активных</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number">{{ $totalRegistrations }}</div>
                    <div class="stat-label">Всего записей</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-number">{{ $totalUsers }}</div>
                    <div class="stat-label">Пользователей</div>
                </div>
            </div>
            
            <div class="admin-grid">
                <div class="admin-card">
                    <div class="card-header">
                        <div class="card-title">Последние консультации</div>
                        <a href="/admin/consultations/create" class="btn btn-success">Добавить</a>
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
                                            <a href="/admin/consultation/{{ $consultation->id }}">
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
                        <div class="admin-empty-state">
                            <div class="empty-icon"></div>
                            <p>Нет консультаций</p>
                        </div>
                    @endif
                </div>
                
                <div class="admin-card">
                    <div class="card-header">
                        <div class="card-title">Последние записи</div>
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
                        <div class="admin-empty-state">
                            <div class="empty-icon"></div>
                            <p>Нет записей</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="admin-card">
                <div class="card-header">
                    <div class="card-title">Заполняемость консультаций</div>
                </div>
                
                <div class="chart-container">
                    @foreach($consultations as $consultation)
                        @php
                            $percent = $consultation->max_slots > 0 
                                ? min(100, round(($consultation->registrations_count / $consultation->max_slots) * 100))
                                : 0;
                        @endphp
                        <div class="chart-bar">
                            <div class="chart-label">
                                {{ Str::limit($consultation->title, 35) }}
                                <small>
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
                        <div class="admin-empty-state">
                            <div class="empty-icon"></div>
                            <p>Нет данных для отображения</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>