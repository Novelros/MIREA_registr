<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Консультации - Админпанель</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
                <div class="admin-menu">
                    <a href="/admin" class="nav-link">Дашборд</a>
                    <a href="/admin/consultations" class="nav-link active">Консультации</a>
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
            
            <div class="card admin-table-container">
                <div class="table-header">
                    <div class="card-title">Все консультации</div>
                    <div class="table-actions">
                        <a href="/admin/consultations/create" class="btn btn-success">Добавить консультацию</a>
                        <a href="/admin" class="btn btn-secondary">Назад</a>
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
                                            <a href="/admin/consultation/{{ $consultation->id }}">
                                                {{ $consultation->title }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($consultation->type == 'individual')
                                                <span class="badge badge-danger">Индивидуальная</span>
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
                                            <span class="consultation-status {{ $consultation->is_active ? 'active' : 'inactive' }}">
                                                {{ $consultation->is_active ? '✅ Активна' : '❌ Неактивна' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="/admin/consultation/{{ $consultation->id }}" class="btn btn-action btn-view">👁️</a>
                                                <a href="/admin/consultation/edit/{{ $consultation->id }}" class="btn btn-action btn-edit">✏️</a>
                                                <form action="/admin/consultation/toggle/{{ $consultation->id }}" method="POST" class="form-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-action {{ $consultation->is_active ? 'btn-danger' : 'btn-success' }}">
                                                        {{ $consultation->is_active ? '❌' : '✅' }}
                                                    </button>
                                                </form>
                                                <form action="/admin/consultation/delete/{{ $consultation->id }}" method="POST" class="form-inline" onsubmit="return confirm('Удалить консультацию? Все записи также будут удалены.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-action btn-delete">🗑️</button>
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
                    <div class="admin-empty-state">
                        <div class="empty-icon"></div>
                        <p>Нет консультаций</p>
                        <a href="/admin/consultations/create" class="btn btn-success" style="margin-top: 20px;">Создать первую консультацию</a>
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>