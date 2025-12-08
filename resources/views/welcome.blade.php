<!DOCTYPE html>
<html>
<head>
    <title>РТУ МИРЭА - Консультации</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 800px; 
            margin: 0 auto; 
            padding: 20px; 
            background-color: #f5f5f5;
        }
        .header { 
            background: linear-gradient(135deg, #2c3e50, #4a6491); 
            color: white; 
            padding: 30px; 
            border-radius: 10px; 
            text-align: center; 
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .logo {
            max-height: 120px;
            max-width: 100%;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .auth-box { 
            background: white; 
            border: 1px solid #ddd; 
            border-radius: 10px; 
            padding: 30px; 
            margin: 20px 0; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        .btn { 
            background: #3498db; 
            color: white; 
            padding: 12px 25px; 
            text-decoration: none; 
            border-radius: 5px; 
            display: inline-block; 
            margin: 10px; 
            font-size: 16px; 
            border: none; 
            cursor: pointer; 
            transition: background-color 0.3s;
        }
        .btn:hover { 
            background: #2980b9; 
            text-decoration: none;
        }
        .btn-admin { 
            background: #e74c3c; 
        }
        .btn-admin:hover { 
            background: #c0392b; 
        }
        .btn-student { 
            background: #27ae60; 
        }
        .btn-student:hover { 
            background: #229954; 
        }
        .test-accounts { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 10px; 
            margin-top: 30px; 
            border: 1px solid #dee2e6; 
        }
        .account-list { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); 
            gap: 15px; 
            margin-top: 15px; 
        }
        .account-card { 
            background: white; 
            padding: 15px; 
            border-radius: 5px; 
            border: 1px solid #ddd;
            transition: transform 0.2s;
        }
        .account-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            margin-top: 10px;
            margin-bottom: 15px;
        }
        p {
            line-height: 1.6;
        }
    </style>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <div class="header">
        <img src="/img/MIREA_Gerb_Colour.png" 
             alt="Логотип РТУ МИРЭА" 
             class="logo">
        
        <h1>РТУ МИРЭА - Система записи на консультации</h1>
        <p>Запись на индивидуальные и групповые консультации для абитуриентов</p>
    </div>
    
    @if(Auth::check())
        <div class="auth-box" style="text-align: center;">
            <h2 style="color: #2c3e50; margin-bottom: 20px;">Добро пожаловать, {{ Auth::user()->name }}!</h2>
            <p style="font-size: 18px; margin-bottom: 25px;">
                Вы вошли как: <strong style="color: #3498db;">{{ Auth::user()->isAdmin() ? 'Администратор' : 'Студент' }}</strong>
            </p>
            
            <div style="margin: 30px 0;">
                @if(Auth::user()->isAdmin())
                    <a href="/admin" class="btn btn-admin">Перейти в админпанель</a>
                @endif
                
                <a href="/consultations" class="btn">Посмотреть консультации</a>
                <a href="/my-registrations" class="btn btn-student">Мои записи</a>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
                @csrf
                <button type="submit" class="btn" style="background: #ff0000ff;">Выйти</button>
            </form>
        </div>
    @else
        <div class="auth-box">
            <h2 style="text-align: center; color: #2c3e50; margin-bottom: 30px;">Вход в систему</h2>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('login') }}" class="btn" style="width: 200px;">Войти</a>
                <a href="{{ route('register') }}" class="btn btn-student" style="width: 200px;">Регистрация</a>
            </div>
            
            <p style="text-align: center; margin-top: 30px;">
                <a href="/consultations" class="btn" style="background: #95a5a6;">Посмотреть консультации без входа</a>
            </p>
        </div>
    @endif
    
    <div class="test-accounts">
        <h3 style="color: #2c3e50; margin-bottom: 20px;">Тестовые аккаунты:</h3>
        
        <div class="account-list">
            <div class="account-card">
                <h4 style="color: #e74c3c; margin-top: 0;">Администратор</h4>
                <p><strong>Логин:</strong> admin@mirea.ru</p>
                <p><strong>Пароль:</strong> 1111</p>
                <p style="color: #666; font-style: italic;">Полный доступ к системе</p>
            </div>
            
            <div class="account-card">
                <h4 style="color: #27ae60; margin-top: 0;">Студент 1</h4>
                <p><strong>Логин:</strong> student1@mirea.ru</p>
                <p><strong>Пароль:</strong> student1</p>
                <p style="color: #666; font-style: italic;">Просмотр и запись на консультации</p>
            </div>
            
            <div class="account-card">
                <h4 style="color: #27ae60; margin-top: 0;">Студент 2</h4>
                <p><strong>Логин:</strong> student2@mirea.ru</p>
                <p><strong>Пароль:</strong> student2</p>
                <p style="color: #666; font-style: italic;">Просмотр и запись на консультации</p>
            </div>
            
            <div class="account-card">
                <h4 style="color: #27ae60; margin-top: 0;">Студент 3</h4>
                <p><strong>Логин:</strong> student3@mirea.ru</p>
                <p><strong>Пароль:</strong> student3</p>
                <p style="color: #666; font-style: italic;">Просмотр и запись на консультации</p>
            </div>
        </div>
        
        <p style="margin-top: 20px; font-size: 14px; color: #666; padding: 10px; background: #e9ecef; border-radius: 5px;">
            <strong>Примечание:</strong> Вы можете использовать эти аккаунты для тестирования системы или зарегистрировать свой собственный.
        </p>
    </div>
    
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #7f8c8d; font-size: 14px;">
        <p><strong>Контакты приемной комиссии:</strong> +7 (495) 123-45-67</p>
        <p><strong>Адрес:</strong> Москва, Проспект Вернадского, 78</p>
        <p style="margin-top: 15px; font-size: 12px;">© 2025 РТУ МИРЭА. Все права защищены.</p>
    </div>
</body>
</html>