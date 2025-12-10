<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>РТУ МИРЭА - Консультации</title>
    
    <!-- Подключаем CSS файлы -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Meta теги для SEO -->
    <meta name="description" content="Система записи на консультации для абитуриентов РТУ МИРЭА">
    <meta name="keywords" content="РТУ МИРЭА, консультации, запись, абитуриенты, университет">
</head>
<body>
    <!-- Шапка с логотипом -->
    <header class="header">
        <img src="{{ asset('img/MIREA_Gerb_Colour.png') }}" 
             alt="Логотип РТУ МИРЭА" 
             class="logo"
             onerror="this.src='https://via.placeholder.com/200x120/2c3e50/ffffff?text=MIREA+Logo';">
        
        <h1>РТУ МИРЭА - Система записи на консультации</h1>
        <p>Запись на индивидуальные и групповые консультации для абитуриентов</p>
    </header>
    
    <!-- Основной контент -->
    <main class="container">
        @if(Auth::check())
            <!-- Пользователь авторизован -->
            <div class="auth-box">
                <h2> Добро пожаловать, {{ Auth::user()->name }}!</h2>
                <p class="text-center">Вы вошли как: 
                    <strong class="{{ Auth::user()->isAdmin() ? 'badge badge-danger' : 'badge badge-success' }}">
                        {{ Auth::user()->isAdmin() ? 'Администратор' : 'Студент' }}
                    </strong>
                </p>
                
                <div class="auth-buttons">
                    @if(Auth::user()->isAdmin())
                        <a href="/admin" class="btn btn-admin">
                            <span role="img" aria-label="admin"></span> Админпанель
                        </a>
                    @endif
                    
                    <a href="/consultations" class="btn">
                        <span role="img" aria-label="calendar"></span> Консультации
                    </a>
                    
                    <a href="/my-registrations" class="btn btn-student">
                        <span role="img" aria-label="list"></span> Мои записи
                    </a>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="text-center mt-3">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <span role="img" aria-label="logout">🚪</span> Выйти
                    </button>
                </form>
            </div>
        @else
            <!-- Гость -->
            <div class="auth-box">
                <h2> Вход в систему</h2>
                
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn">
                        <span role="img" aria-label="login"></span> Войти в систему
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-student">
                        <span role="img" aria-label="register"></span> Регистрация студента
                    </a>
                </div>
                
                <p class="text-center mt-3">
                    <a href="/consultations" class="btn btn-secondary">
                        <span role="img" aria-label="view"></span> Просмотр консультаций
                    </a>
                </p>
            </div>
        @endif
        
        <!-- Тестовые аккаунты -->
        <div class="test-accounts">
            <h3> Тестовые аккаунты</h3>
            
            <div class="account-list">
                <div class="account-card fade-in">
                    <h4>Администратор</h4>
                    <p><strong>Логин:</strong> admin@mirea.ru</p>
                    <p><strong>Пароль:</strong> 1111</p>
                    <p>Полный доступ ко всем функциям системы</p>
                </div>
                
                <div class="account-card fade-in" style="animation-delay: 0.1s;">
                    <h4>Студент 1</h4>
                    <p><strong>Логин:</strong> student1@mirea.ru</p>
                    <p><strong>Пароль:</strong> student1</p>
                    <p>Просмотр и запись на консультации</p>
                </div>
                
                <div class="account-card fade-in" style="animation-delay: 0.2s;">
                    <h4>Студент 2</h4>
                    <p><strong>Логин:</strong> student2@mirea.ru</p>
                    <p><strong>Пароль:</strong> student2</p>
                    <p>Просмотр и запись на консультации</p>
                </div>
                
                <div class="account-card fade-in" style="animation-delay: 0.3s;">
                    <h4>Студент 3</h4>
                    <p><strong>Логин:</strong> student3@mirea.ru</p>
                    <p><strong>Пароль:</strong> student3</p>
                    <p>Просмотр и запись на консультации</p>
                </div>
            </div>
            
            <div class="test-note">
                <p><strong>💡 Примечание:</strong> Вы можете использовать эти аккаунты для тестирования системы или зарегистрировать свой собственный.</p>
            </div>
        </div>
    </main>
    
    <!-- Футер -->
    <footer class="main-footer">
        <div class="container">
            <p><strong>📞 Контакты приемной комиссии:</strong> +7 (499) 600-80-80</p>
            <p><strong>📍 Адрес:</strong> Москва, Проспект Вернадского, 78</p>
            <p class="copyright">© 2025 РТУ МИРЭА. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>