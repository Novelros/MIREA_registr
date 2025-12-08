# MIREA Registr — сервис записи на консультации

Небольшой сервис для приёмной комиссии РТУ МИРЭА, который позволяет управлять консультациями и записями абитуриентов, контролировать количество мест и предоставляет REST API для внешних сервисов.

## Стек и версии

- PHP: 8.4+
- Laravel: 10.x
- Composer: 2.x
- СУБД: MySQL 8 / MariaDB 10.5+
- Node.js: 18+ (для сборки фронтенда, если используется Vite/Blade-шаблоны)

Основные зависимости (composer):

- laravel/framework (ядро фреймворка)
- fideloper/proxy или встроенный laravel/ssl (если настроен HTTPS)
- laravel/sanctum или laravel/passport (если используется токенная авторизация для API)
- laravel/tinker (отладка)
- Dev-зависимости: phpunit/phpunit, nunomaduro/collision и т.п.

Основные зависимости (npm):

- laravel-vite-plugin
- vite
- tailwindcss`/`bootstrap (по необходимости)
- alpinejs или другой минималистичный JS‑фреймворк для форм.

<br>

## Структура проекта

```txt
Проект имеет типичную для Laravel структуру.
MIREA_registr/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ConsultationController.php
│   │   │   ├── RegistrationController.php
│   │   │   └── Api/
│   │   │       └── ConsultationApiController.php
│   │   ├── Requests/
│   │   │   ├── StoreConsultationRequest.php
│   │   │   └── StoreRegistrationRequest.php
│   │   └── Middleware/...
│   ├── Models/
│   │   ├── Consultation.php
│   │   └── Registration.php
│   └── Providers/...
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   │   ├── xxxx_xx_xx_create_consultations_table.php
│   │   └── xxxx_xx_xx_create_registrations_table.php
│   ├── seeders/
│   │   └── DatabaseSeeder.php
├── public/
├── resources/
│   ├── views/
│   │   ├── consultations/
│   │   │   ├── index.blade.php
│   │   │   └── show.blade.php
│   │   └── registrations/
│   │       └── create.blade.php
│   └── js/, css/...
├── routes/
│   ├── web.php
│   └── api.php
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md

```

### Модели и связи

- Consultation
  - поля: id, title, description, starts_at, capacity, location, is_online, created_at, updated_at
  - связи:
    - hasMany(Registration::class) — список записей на консультацию
- Registration
  - поля: id, consultation_id, full_name, email, phone, status, created_at, updated_at
  - связи:
    - belongsTo(Consultation::class) — консультация, на которую записан абитуриент.

### Основные ограничения (реализованы в модели/сервисе/валидаторе)

- Нельзя создать больше записей, чем capacity у соответствующей Consultation.
- У одного абитуриента не может быть больше одной активной записи:
  - уникальность по полю email в пределах активных регистраций (`status = 'active'` или через уникальный индекс `consultation_id + email`).

<br>

## Установка и запуск

### 1. Клонирование репозитория
```bash
git clone https://github.com/Novelros/MIREA_registr.git
cd MIREA_registr
```
### 2. Установка PHP‑зависимостей`

```bash
composer install
```

### 3. Настройка окружения

Создать файл .env на основе .env.example:
```bash
cp .env.example .env
```
В .env указать настройки БД и приложения, например:

```txt
APP_NAME="MIREA Registr"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mirea_registr
DB_USERNAME=root
DB_PASSWORD=secret
```

Сгенерировать ключ приложения:
php artisan key:generate

### 4. Миграции и сиды

Создать БД (если ещё нет) и выполнить миграции:
```bash
php artisan migrate
```

### 5. Установка фронтенд‑зависимостей (опционально)

```bash
npm install
npm run build
```

### 6. Запуск приложения

```bash
php artisan serve
```

Приложение будет доступно по адресу: http://127.0.0.1:8000.

## Функциональность

### Форма записи на консультацию (web-интерфейс)
Доступна страница со списком консультаций с отображением доступных слотов и индикатором оставшихся мест.

Основные экраны:

- Список консультаций (`GET /consultations`)
  - отображаются дата/время, формат (очно/онлайн), количество свободных мест
- Страница регистрации (`GET /consultations/{id}` или `/registrations/create?consultation_id=...`)
  - форма с выбором консультации и вводом контактных данных (`full_name`, email, `phone`)
- Подтверждение регистрации (`POST /registrations`)
  - при успешной регистрации показывается сообщение об успехе; при ошибках валидации выводятся сообщения рядом с полями.[2]

### REST API для внешних сервисов

API реализовано в формате JSON и сгруппировано в routes/api.php.

#### Примеры некоторых эндпоинтов

| Метод | URL                            | Описание                          |
|-------|--------------------------------|-----------------------------------|
| GET   | /api/consultations          | Список консультаций (с пагинацией и фильтрами) |
| GET   | /api/consultations/{id}     | Детальная информация о консультации |
| GET   | /api/consultations/{id}/registrations | Список записей на консультацию |
| POST  | /api/consultations/{id}/registrations | Создание записи абитуриента     |

Ответы в формате JSON, по умолчанию используются HTTP‑коды:

- 200 — успешный запрос (список или объект)
- 201 — успешное создание записи
- 400/422 — ошибки валидации
- 404 — не найдено
- 409 — конфликт (например, нет свободных мест или пользователь уже записан).

#### Примеры запросов

1. Получить список консультаций:

```bash
curl -X GET http://localhost:8000/api/consultations
```

Пример ответа (сокращенно):
```txt
{
  "data": [
    {
      "id": 1,
      "title": "Индивидуальная консультация",
      "starts_at": "2025-06-01T10:00:00",
      "capacity": 2,
      "free_slots": 1,
      "is_online": false
    }
  ],
  "links": {...},
  "meta": {...}
}
```

2. Создать запись на консультацию:
```bash
curl -X POST http://localhost:8000/api/consultations/1/registrations \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Иван Иванов",
    "email": "ivan@example.com",
    "phone": "+7 900 000 00 00"
  }'
```

Пример ответа:
```txt
{
  "id": 10,
  "consultation_id": 1,
  "full_name": "Иван Иванов",
  "email": "ivan@example.com",
  "status": "active"
}
```

## Валидация и FormRequest

Валидация входных данных вынесена в классы FormRequest в app/Http/Requests.

### Пример правил для регистрации (`StoreRegistrationRequest`)

- full_name — required|string|max:255
- email — required|email|max:255
- phone — nullable|string|max:32
- Дополнительные бизнес‑правила:
  - проверка, что у консультации есть свободные места;
  - проверка уникальности активной записи для данного email.

Ошибки валидации возвращаются в формате JSON для API и автоматически отображаются во вьюхах для web‑форм.

## Пагинация и фильтры

Список консультаций (`GET /api/consultations`) поддерживает:

- Пагинацию: параметр page, по умолчанию per_page = 15.
- Фильтры:
  - date — конкретная дата консультации
  - is_online — фильтр по формату (очное/онлайн)
  - has_free_slots — только консультации, где есть свободные места.

Пример запроса:
```bash
curl "http://localhost:8000/api/consultations?date=2025-06-01&has_free_slots=1"
```
