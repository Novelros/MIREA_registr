<!DOCTYPE html>
<html>
<head>
    <title>Консультации Админка</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; }
        th { background: #f5f5f5; }
        .btn { padding: 8px 16px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; }
        .btn-success { background: #27ae60; }
        .btn-warning { background: #f39c12; }
        .btn-danger { background: #e74c3c; }
        .actions { display: flex; gap: 5px; }
        .form-inline { display: inline; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
    <script>
        function confirmDelete(event, title) {
            return confirm(`Удалить консультацию "${title}"? Все связанные записи также будут удалены.`);
        }
    </script>
</head>
<body>
    <div style="text-align: right; margin-bottom: 20px;">
        <a href="{{ route('admin.consultations.create') }}" class="btn btn-success">
            ➕ Создать консультацию
        </a>
    </div>
    
    <div class="nav">
        <a href="/admin" class="btn">← Назад</a>
        <a href="/admin/registrations" class="btn"> Записи</a>
        <a href="/admin/users" class="btn">👥 Пользователи</a>
    </div>
    
    <h1> Все консультации</h1>
    
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
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Тип</th>
                <th>Дата</th>
                <th>Записей</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultations as $c)
                <tr>
                    <td><strong>#{{ $c->id }}</strong></td>
                    <td>
                        <a href="/admin/consultation/{{ $c->id }}" style="color: #3498db; font-weight: 500;">
                            {{ $c->title }}
                        </a>
                        @if($c->description)
                            <br><small style="color: #7f8c8d;">{{ Str::limit($c->description, 50) }}</small>
                        @endif
                    </td>
                    <td>
                        @if($c->type == 'individual')
                            <span style="color: #e74c3c; font-weight: bold;">Индив.</span>
                        @else
                            <span style="color: #2ecc71; font-weight: bold;">Групп.</span>
                        @endif
                        <br>
                        <small>
                            @if($c->format == 'online')
                                <span style="color: #3498db;">Онлайн</span>
                            @else
                                <span style="color: #f39c12;">Очно</span>
                            @endif
                        </small>
                    </td>
                    <td>
                        {{ $c->start_time->format('d.m.Y H:i') }}
                        @if($c->end_time)
                            <br><small>до {{ $c->end_time->format('H:i') }}</small>
                        @endif
                    </td>
                    <td>
                        <span style="background: {{ $c->registrations_count > 0 ? '#3498db' : '#95a5a6' }}; color: white; padding: 3px 8px; border-radius: 10px; font-size: 12px;">
                            {{ $c->registrations_count }}/{{ $c->max_slots }}
                        </span>
                    </td>
                    <td>
                        @if($c->is_active)
                            <span style="color: #27ae60; font-weight: bold;">✅ Активна</span>
                        @else
                            <span style="color: #e74c3c; font-weight: bold;">❌ Неактивна</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a href="/admin/consultation/{{ $c->id }}" class="btn" title="Просмотр">👁️</a>
                            <a href="/admin/consultation/edit/{{ $c->id }}" class="btn btn-warning" title="Редактировать">✏️</a>
                            <form action="/admin/consultation/toggle/{{ $c->id }}" method="POST" class="form-inline">
                                @csrf
                                <button type="submit" class="btn {{ $c->is_active ? 'btn-danger' : 'btn-success' }}" title="{{ $c->is_active ? 'Закрыть' : 'Открыть' }}">
                                    {{ $c->is_active ? '❌' : '✅' }}
                                </button>
                            </form>
                            <form action="/admin/consultation/delete/{{ $c->id }}" method="POST" class="form-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirmDelete(event, '{{ $c->title }}')" title="Удалить">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $consultations->links() }}
    
    @if($consultations->isEmpty())
        <div style="text-align: center; padding: 40px; color: #7f8c8d;">
            <h3>Консультаций нет</h3>
            <p>Создайте первую консультацию, нажав кнопку "Создать консультацию" выше.</p>
        </div>
    @endif
    
    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <h4> Статистика:</h4>
        <p>• Всего консультаций: {{ $consultations->total() }}</p>
        <p>• Активных: {{ $consultations->where('is_active', true)->count() }}</p>
        <p>• Индивидуальных: {{ $consultations->where('type', 'individual')->count() }}</p>
        <p>• Групповых: {{ $consultations->where('type', 'group')->count() }}</p>
    </div>
</body>
</html>