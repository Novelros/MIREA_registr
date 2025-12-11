<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $consultation->title }} - Админпанель</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" alt="Герб РТУ МИРЭА" class="logo">
            <div class="header-content">
                <h1>Детали консультации</h1>
                <p class="subtitle">{{ $consultation->title }}</p>
            </div>
        </header>
        
        <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="/admin/consultations" class="btn">Назад к списку</a>
            <a href="/admin" class="btn">Дашборд</a>
            @if($consultation->registrations->count() > 0)
                <a href="/admin/consultation/{{ $consultation->id }}/export" class="btn btn-success">
                    📥 Скачать список участников (CSV)
                </a>
            @endif
        </div>
        
        <div class="card admin-card">
            <h1>{{ $consultation->title }}</h1>
            <div class="consultation-info-grid">
                <div class="info-item">
                    <strong>Тип:</strong> {{ $consultation->type == 'individual' ? 'Индивидуальная' : 'Групповая' }}
                </div>
                <div class="info-item">
                    <strong>Формат:</strong> {{ $consultation->format == 'online' ? 'Онлайн' : 'Очно' }}
                </div>
                <div class="info-item">
                    <strong>Дата и время:</strong> {{ $consultation->start_time->format('d.m.Y H:i') }}
                </div>
                <div class="info-item">
                    <strong>Максимум мест:</strong> {{ $consultation->max_slots }}
                </div>
                <div class="info-item">
                    <strong>Записано:</strong> 
                    <span class="badge {{ $consultation->registrations_count > 0 ? 'badge-info' : 'badge-warning' }}">
                        {{ $consultation->registrations_count }}
                    </span>
                </div>
                <div class="info-item">
                    <strong>Статус:</strong> 
                    <span class="consultation-status {{ $consultation->is_active ? 'active' : 'inactive' }}">
                        {{ $consultation->is_active ? '✅ Активна' : '❌ Неактивна' }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card admin-card">
            <div class="table-header">
                <h2>Записи на эту консультацию ({{ $consultation->registrations->count() }})</h2>
                @if($consultation->registrations->count() > 0)
                    <div class="table-actions">
                        <a href="/admin/consultation/{{ $consultation->id }}/export" class="btn btn-success">
                            📥 Скачать CSV
                        </a>
                    </div>
                @endif
            </div>
            
            @if($consultation->registrations->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Фамилия</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Дата записи</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultation->registrations as $r)
                                <tr>
                                    <td><strong>#{{ $r->id }}</strong></td>
                                    <td>{{ $r->first_name }}</td>
                                    <td>{{ $r->last_name }}</td>
                                    <td>
                                        <a href="mailto:{{ $r->email }}" class="email-link">
                                            {{ $r->email }}
                                        </a>
                                    </td>
                                    <td>{{ $r->phone }}</td>
                                    <td>{{ $r->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="mailto:{{ $r->email }}" class="btn-action btn-view" title="Написать email">
                                                📧
                                            </a>
                                            <form action="/admin/registration/delete/{{ $r->id }}" method="POST" 
                                                  onsubmit="return confirm('Удалить запись студента {{ $r->first_name }} {{ $r->last_name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="Удалить запись">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="admin-empty-state">
                    <div class="empty-icon"></div>
                    <p>На эту консультацию еще никто не записался.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>