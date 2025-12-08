<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Консультации - Админпанель</title>
    <style>
        /* Используйте стили из index.blade.php */
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
        
        .admin-content {
            padding: 30px 0;
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
        .btn-sm { padding: 8px 16px; font-size: 13px; }
        
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
        
        .actions {
            display: flex;
            gap: 8px;
        }
        
        .form-inline {
            display: inline;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="admin-container">
            <nav class="admin-nav">
                <div class="admin-title">
                    <span></span>
                    <h1>Управление консультациями</h1>
                </div>
                <div class="admin-menu">
                    <a href="/admin" class="nav-link"> Дашборд</a>
                    <a href="/admin/consultations" class="nav-link active"> Консультации</a>
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
            
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Все консультации</div>
                    <div>
                        <a href="/admin/consultations/create" class="btn btn-success">➕ Добавить консультацию</a>
                        <a href="/admin" class="btn">← Назад</a>
                    </div>
                </div>
                
                @if($consultations->count() > 0)
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Формат</th>
                                    <th>Дата и время</th>
                                    <th>Места</th>
                                    <th>Записей</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consultations as $consultation)
                                    <tr>
                                        <td>
                                            <a href="/admin/consultation/{{ $consultation->id }}" style="color: var(--info); text-decoration: none; font-weight: 500;">
                                                {{ $consultation->title }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($consultation->type == 'individual')
                                                <span class="badge badge-info">Индивидуальная</span>
                                            @else
                                                <span class="badge badge-warning">Групповая</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($consultation->format == 'online')
                                                <span class="badge badge-success">Онлайн</span>
                                            @else
                                                <span class="badge badge-primary">Очно</span>
                                            @endif
                                        </td>
                                        <td>{{ $consultation->start_time->format('d.m.Y H:i') }}</td>
                                        <td>{{ $consultation->max_slots }}</td>
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
                                        <td>
                                            <div class="actions">
                                                <a href="/admin/consultation/{{ $consultation->id }}" class="btn btn-sm btn-primary">👁️</a>
                                                <a href="/admin/consultation/edit/{{ $consultation->id }}" class="btn btn-sm btn-warning">✏️</a>
                                                <form action="/admin/consultation/toggle/{{ $consultation->id }}" method="POST" class="form-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $consultation->is_active ? 'btn-danger' : 'btn-success' }}">
                                                        {{ $consultation->is_active ? '❌' : '✅' }}
                                                    </button>
                                                </form>
                                                <form action="/admin/consultation/delete/{{ $consultation->id }}" method="POST" class="form-inline" onsubmit="return confirm('Удалить консультацию? Все записи также будут удалены.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($consultations->hasPages())
                        <div class="pagination">
                            {{ $consultations->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon"></div>
                        <p>Нет консультаций</p>
                        <a href="/admin/consultations/create" class="btn btn-success" style="margin-top: 20px;">Создать первую консультацию</a>
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>