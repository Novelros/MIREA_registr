<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все записи - Админпанель</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
    <div class="admin-container">
        <header class="admin-header">
            <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" alt="Герб РТУ МИРЭА" class="logo">
            <div class="header-content">
                <h1>Все записи на консультации</h1>
                <p class="subtitle">Управление записями студентов</p>
                <p class="additional-text">Всего записей: {{ $registrations->total() }}</p>
            </div>
        </header>
        
        <nav class="admin-nav">
            <a href="/admin">Статистика</a>
            <a href="/admin/consultations">Консультации</a>
            <a href="/admin/registrations" class="active">Все записи</a>
            <a href="/admin/users">Пользователи</a>
            <a href="/consultations" target="_blank">Сайт</a>
            <a href="/admin" class="btn btn-secondary">Назад</a>
        </nav>
        
        <div class="admin-content">
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
            
            <div class="admin-card">
                <div class="card-header">
                    <div>
                        <h3>Всего записей: {{ $registrations->total() }}</h3>
                        <p>Показано: {{ $registrations->count() }} из {{ $registrations->total() }}</p>
                    </div>
                    <div class="table-actions">
                        <a href="/admin/consultations" class="btn btn-primary">Консультации</a>
                        <a href="/admin" class="btn btn-secondary">Дашборд</a>
                    </div>
                </div>
            </div>
            
            @if($registrations->count() > 0)
                <div class="admin-table-container">
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
                                        <a href="/admin/consultation/{{ $registration->consultation_id }}">
                                            {{ $registration->consultation->title }}
                                        </a>
                                        <div class="consultation-info">
                                            {{ $registration->consultation->start_time->format('d.m.Y H:i') }}
                                            • 
                                            @if($registration->consultation->type == 'individual')
                                                <span class="text-danger">Индивидуальная</span>
                                            @else
                                                <span class="text-success">Групповая</span>
                                            @endif
                                            •
                                            @if($registration->consultation->format == 'online')
                                                <span class="text-primary">Онлайн</span>
                                            @else
                                                <span class="text-warning">Очно</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Email:</strong> 
                                            {{ $registration->email }}
                                            <button class="copy-email" onclick="copyEmail('{{ $registration->email }}')" title="Скопировать email"></button>
                                            <span class="copy-success">Скопировано</span>
                                        </div>
                                        <div>
                                            <strong>Телефон:</strong> {{ $registration->phone }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ $registration->created_at->format('d.m.Y') }}
                                        <div>
                                            {{ $registration->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="/admin/consultation/{{ $registration->consultation_id }}" class="btn btn-action btn-view" title="Просмотр консультации">
                                                Консультация
                                            </a>
                                            <a href="mailto:{{ $registration->email }}" class="btn btn-action btn-email" title="Написать email">
                                                Email
                                            </a>
                                            <form action="/admin/registration/delete/{{ $registration->id }}" method="POST" class="form-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-action btn-delete" onclick="return confirmDelete(event)" title="Удалить запись">
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
                
                @if($registrations->hasPages())
                    <div class="pagination">
                        {{ $registrations->links() }}
                    </div>
                @endif
                
                <div class="alert alert-warning">
                    <strong>Информация:</strong> Удаление записи также уменьшит счетчик записей в консультации
                </div>
            @else
                <div class="admin-empty-state">
                    <div class="empty-icon"></div>
                    <h3>Записей нет</h3>
                    <p>В системе еще нет записей на консультации.</p>
                    <div style="margin-top: 20px;">
                        <a href="/admin/consultations" class="btn btn-primary">Посмотреть консультации</a>
                        <a href="/admin" class="btn btn-secondary">Вернуться в админку</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>