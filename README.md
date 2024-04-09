## Версия php 8.2.17
## Настройка среды
1. composer install
2. импортировать db.sql из корня проекта в Базу Данных
3. настроить .env для БД
4. Запуск локального сервера (php artisan serve)

## Описание
1. ### Авторизация
- В качестве авторизации используется tymon/jwt-auth
- access-token держится всего час, после надо отправить запрос на refresh
- refersh url(post) - {host}/api/refresh в ответ идёт тоже самое что при авторизации
2. ### Страницы где требуется токен
- {host}/api/logout
- {host}/api/user
- {host}/api/booking/{code}/seat
- {host}/api/user/booking
3. ### Список аккаунтов
- 89588441320 : legen2a777 (admin, под админку ничего нету)
- 89588441321 : legen2a777
- 12345678901 : legen2a777
