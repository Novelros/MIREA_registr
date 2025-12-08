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
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1> Все записи на консультации</h1>
        </div>
    </header>
    
    <nav class="admin-nav">
        <div class="container">
            <a href="/admin"> Статистика</a>
            <a href="/admin/registrations" style="background: rgba(255,255,255,0.2);"> Все записи</a>
            <a href="/consultations" target="_blank">🌐 Сайт</a>
            <a href="/admin" class="btn-back">← Назад</a>
        </div>
    </nav>
    
    <div class="container">
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
        
        <div class="stats-card">
            <h3>Всего записей: {{ $registrations->total() }}</h3>
            <p>Показано: {{ $registrations->count() }} из {{ $registrations->total() }}</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Студент</th>
                    <th>Консультация</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Дата записи</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $registration)
                    <tr>
                        <td>{{ $registration->id }}</td>
                        <td>
                            <strong>{{ $registration->first_name }} {{ $registration->last_name }}</strong>
                        </td>
                        <td>
                            <a href="/admin/consultation/{{ $registration->consultation_id }}" style="color: #3498db;">
                                {{ $registration->consultation->title }}
                            </a><br>
                            <small>{{ $registration->consultation->start_time->format('d.m.Y H:i') }}</small>
                        </td>
                        <td>{{ $registration->email }}</td>
                        <td>{{ $registration->phone }}</td>
                        <td>{{ $registration->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="/admin/consultation/{{ $registration->consultation_id }}" class="btn btn-view">
                                Консультация
                            </a>
                            <form action="/admin/registration/delete/{{ $registration->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Удалить запись?')">
                                    Удалить
                                </button>
                            </form>
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
        
        @if($registrations->isEmpty())
            <p style="text-align: center; color: #7f8c8d; padding: 40px; background: white; border-radius: 10px;">
                Нет записей в базе данных.
            </p>
        @endif
    </div>
</body>
</html>