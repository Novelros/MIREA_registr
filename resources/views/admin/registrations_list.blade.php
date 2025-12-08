<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все записи - Админпанель</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; }
        
        .admin-header { 
            background: linear-gradient(135deg, #2c3e50, #4a6491); 
            color: white; 
            padding: 20px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .admin-nav { 
            background: #34495e; 
            padding: 10px; 
            margin-bottom: 20px;
        }
        
        .admin-nav a { 
            color: white; 
            text-decoration: none; 
            margin-right: 20px; 
            padding: 8px 15px; 
            border-radius: 4px;
        }
        
        .admin-nav a:hover { 
            background: rgba(255,255,255,0.1); 
        }
        
        .container { 
            max-width: 1400px; 
            margin: 0 auto; 
            padding: 20px; 
        }
        
        .stats-card { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            margin-bottom: 30px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: white; 
            border-radius: 10px; 
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        th, td { 
            padding: 15px; 
            text-align: left; 
            border-bottom: 1px solid #eee; 
        }
        
        th { 
            background: #2c3e50; 
            color: white; 
            font-weight: bold; 
        }
        
        tr:hover { 
            background: #f9f9f9; 
        }
        
        .btn { 
            padding: 8px 16px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 2px;
        }
        
        .btn-back { background: #95a5a6; color: white; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-view { background: #3498db; color: white; }
        .btn-email { background: #9b59b6; color: white; }
        .btn:hover { opacity: 0.9; }
        
        .pagination { 
            display: flex; 
            justify-content: center; 
            margin-top: 30px; 
            gap: 10px;
        }
        
        .pagination a, .pagination span { 
            padding: 8px 15px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            text-decoration: none;
            color: #333;
        }
        
        .pagination a:hover { 
            background: #2c3e50; 
            color: white; 
            border-color: #2c3e50;
        }
        
        .pagination .active { 
            background: #2c3e50; 
            color: white; 
            border-color: #2c3e50;
        }
        
        .alert { 
            padding: 15px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
        }
        
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .actions-cell {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .form-inline {
            display: inline;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            color: #7f8c8d;
        }
        
        .copy-email {
            background: none;
            border: none;
            color: #3498db;
            cursor: pointer;
            padding: 0;
            margin-left: 5px;
            font-size: 12px;
        }
        
        .copy-email:hover {
            text-decoration: underline;
        }
        
        .copy-success {
            color: #27ae60;
            font-size: 12px;
            margin-left: 5px;
            display: none;
        }
    </style>
    <script>
        function copyEmail(email) {
            navigator.clipboard.writeText(email).then(function() {
                const successElement = event.target.nextElementSibling;
                successElement.style.display = 'inline';
                setTimeout(function() {
                    successElement.style.display = 'none';
                }, 2000);
            });
        }
        
        function confirmDelete(event) {
            const studentName = event.target.closest('tr').querySelector('td:nth-child(2) strong').textContent;
            const consultationName = event.target.closest('tr').querySelector('td:nth-child(3) a').textContent;
            
            return confirm(`Удалить запись студента "${studentName}" на консультацию "${consultationName}"?`);
        }
    </script>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1> Все записи на консультации</h1>
            <p>Управление записями студентов</p>
        </div>
    </header>
    
    <nav class="admin-nav">
        <div class="container">
            <a href="/admin"> Статистика</a>
            <a href="/admin/consultations"> Консультации</a>
            <a href="/admin/registrations" style="background: rgba(255,255,255,0.2);"> Все записи</a>
            <a href="/admin/users">👥 Пользователи</a>
            <a href="/consultations" target="_blank">🌐 Сайт</a>
            <a href="/admin" class="btn-back">← Назад</a>
        </div>
    </nav>
    
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif
        
        <div class="stats-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="margin-bottom: 5px;">Всего записей: {{ $registrations->total() }}</h3>
                    <p style="color: #7f8c8d; margin: 0;">Показано: {{ $registrations->count() }} из {{ $registrations->total() }}</p>
                </div>
                <div>
                    <a href="/admin/consultations" class="btn" style="background: #3498db;"> Консультации</a>
                    <a href="/admin" class="btn" style="background: #95a5a6;"> Дашборд</a>
                </div>
            </div>
        </div>
        
        @if($registrations->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Студент</th>
                        <th>Консультация</th>
                        <th>Контакт</th>
                        <th width="150">Дата записи</th>
                        <th width="200">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $registration)
                        <tr>
                            <td><strong>#{{ $registration->id }}</strong></td>
                            <td>
                                <strong>{{ $registration->first_name }} {{ $registration->last_name }}</strong>
                            </td>
                            <td>
                                <a href="/admin/consultation/{{ $registration->consultation_id }}" style="color: #3498db; font-weight: 500;">
                                    {{ $registration->consultation->title }}
                                </a>
                                <div style="font-size: 12px; color: #7f8c8d; margin-top: 3px;">
                                    {{ $registration->consultation->start_time->format('d.m.Y H:i') }}
                                    • 
                                    @if($registration->consultation->type == 'individual')
                                        <span style="color: #e74c3c;">Индивидуальная</span>
                                    @else
                                        <span style="color: #2ecc71;">Групповая</span>
                                    @endif
                                    •
                                    @if($registration->consultation->format == 'online')
                                        <span style="color: #3498db;">Онлайн</span>
                                    @else
                                        <span style="color: #f39c12;">Очно</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="margin-bottom: 5px;">
                                    <strong>Email:</strong> 
                                    {{ $registration->email }}
                                    <button class="copy-email" onclick="copyEmail('{{ $registration->email }}')" title="Скопировать email">
                                        
                                    </button>
                                    <span class="copy-success">✓ Скопировано</span>
                                </div>
                                <div>
                                    <strong>Телефон:</strong> {{ $registration->phone }}
                                </div>
                            </td>
                            <td>
                                {{ $registration->created_at->format('d.m.Y') }}
                                <div style="font-size: 12px; color: #7f8c8d;">
                                    {{ $registration->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="/admin/consultation/{{ $registration->consultation_id }}" class="btn btn-view" title="Просмотр консультации">
                                         Консультация
                                    </a>
                                    <a href="mailto:{{ $registration->email }}" class="btn btn-email" title="Написать email">
                                        📧 Email
                                    </a>
                                    <form action="/admin/registration/delete/{{ $registration->id }}" method="POST" class="form-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete" onclick="return confirmDelete(event)" title="Удалить запись">
                                            🗑️ Удалить
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($registrations->hasPages())
                <div class="pagination">
                    {{ $registrations->links() }}
                </div>
            @endif
            
            <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 5px; border: 1px solid #ffeaa7;">
                <p style="margin: 0; color: #856404;">
                    <strong>💡 Информация:</strong> Удаление записи также уменьшит счетчик записей в консультации
                </p>
            </div>
        @else
            <div class="empty-state">
                <div style="font-size: 60px; margin-bottom: 20px;">📭</div>
                <h3 style="color: #95a5a6; margin-bottom: 10px;">Записей нет</h3>
                <p style="margin-bottom: 20px;">В системе еще нет записей на консультации.</p>
                <a href="/admin/consultations" class="btn" style="background: #3498db;">Посмотреть консультации</a>
                <a href="/admin" class="btn" style="background: #95a5a6; margin-left: 10px;">Вернуться в админку</a>
            </div>
        @endif
    </div>
</body>
</html>