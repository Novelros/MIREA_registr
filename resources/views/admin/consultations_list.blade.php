<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Консультации - Админпанель</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script>
        function confirmDelete(event, title) {
            return confirm(`Удалить консультацию "${title}"? Все связанные записи также будут удалены.`);
        }
    </script>
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" alt="Герб РТУ МИРЭА" class="logo">
            <div class="header-content">
                <h1>Управление консультациями</h1>
                <p class="user-greeting">Вы вошли как: {{ Auth::user()->name }}</p>
                <p class="additional-text">Создание и редактирование консультаций</p>
            </div>
        </header>
        
        <nav class="admin-nav">
            <a href="/admin" class="btn">Назад</a>
            <a href="/admin/registrations" class="btn">Записи</a>
            <a href="/admin/users" class="btn">Пользователи</a>
        </nav>
        
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
        
        <div class="admin-table-container">
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
                                    <span class="badge badge-danger">Индив.</span>
                                @else
                                    <span class="badge badge-success">Групп.</span>
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
                                <span class="badge {{ $c->registrations_count > 0 ? 'badge-info' : 'badge-secondary' }}">
                                    {{ $c->registrations_count }}/{{ $c->max_slots }}
                                </span>
                            </td>
                            <td>
                                @if($c->is_active)
                                    <span>✅ Активна</span>
                                @else
                                    <span>❌Неактивна</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/consultation/{{ $c->id }}" class="btn btn-action btn-view" title="Просмотр">👁️</a>
                                    <a href="/admin/consultation/edit/{{ $c->id }}" class="btn btn-action btn-edit" title="Редактировать">✏️</a>
                                    <form action="/admin/consultation/toggle/{{ $c->id }}" method="POST" class="form-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-action {{ $c->is_active ? 'btn-danger' : 'btn-success' }}" title="{{ $c->is_active ? 'Закрыть' : 'Открыть' }}">
                                            {{ $c->is_active ? '❌' : '✅' }}
                                        </button>
                                    </form>
                                    <form action="/admin/consultation/delete/{{ $c->id }}" method="POST" class="form-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete" onclick="return confirmDelete(event, '{{ $c->title }}')" title="Удалить">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $consultations->links() }}
        
        @if($consultations->isEmpty())
            <div class="admin-empty-state">
                <h3>Консультаций нет</h3>
                <p>Создайте первую консультацию, нажав кнопку "Создать консультацию" выше.</p>
            </div>
        @endif
        
        <div class="card admin-card">
            <h4>Статистика:</h4>
            <p>• Всего консультаций: {{ $consultations->total() }}</p>
            <p>• Активных: {{ $consultations->where('is_active', true)->count() }}</p>
            <p>• Индивидуальных: {{ $consultations->where('type', 'individual')->count() }}</p>
            <p>• Групповых: {{ $consultations->where('type', 'group')->count() }}</p>
        </div>
    </div>
</body>
</html>