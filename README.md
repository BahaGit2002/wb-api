# WB API Integration

This Laravel project integrates with the Wildberries API to fetch and store various data including orders, sales, stocks, and incomes.

## Project Overview

The project is built using:
- Laravel (PHP Framework)
- MySQL Database
- RESTful API Architecture

## Database Structure

The project uses the following database tables:
- `orders` - Stores order information
- `sales` - Stores sales data
- `stocks` - Stores stock information
- `incomes` - Stores income data
- `users` - User management
- `cache` - API response caching
- `jobs` - Background job processing

## API Integration

The project integrates with the Wildberries API using the following credentials:
- Host: 109.73.206.144:6969
- API Key: E6kUTYrYwZq2tN4QEtyzsbEBk3ie

## Setup Instructions

1. Clone the repository:
```bash
git clone [repository-url]
cd wb-api
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment:
```bash
cp .env.example .env
```

4. Update the `.env` file with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Start the server:
```bash
php artisan serve
```

## Database Access

The database is hosted on a free hosting service with the following credentials:

- Host: [Your Database Host]
- Database Name: [Your Database Name]
- Username: [Your Username]
- Password: [Your Password]
- Port: 3306

## API Endpoints

The project implements the following API endpoints:

1. Orders API
   - Endpoint: `/api/orders`
   - Method: GET
   - Description: Fetches and stores order data

2. Sales API
   - Endpoint: `/api/sales`
   - Method: GET
   - Description: Fetches and stores sales data

3. Stocks API
   - Endpoint: `/api/stocks`
   - Method: GET
   - Description: Fetches and stores stock information

4. Incomes API
   - Endpoint: `/api/incomes`
   - Method: GET
   - Description: Fetches and stores income data

## Data Models

The project includes the following models:

1. Order
   - Handles order-related data
   - Fields: [List main fields]

2. Sale
   - Manages sales data
   - Fields: [List main fields]

3. Stock
   - Manages stock information
   - Fields: [List main fields]

4. Income
   - Handles income data
   - Fields: [List main fields]

## Background Jobs

The project uses Laravel's queue system to process API requests in the background, ensuring better performance and reliability.

## Caching

API responses are cached to improve performance and reduce API calls. The cache is stored in the `cache` table.

## Security

- API keys are stored in environment variables
- Database credentials are secured
- Input validation is implemented
- Rate limiting is applied to API endpoints

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License.

# Проект WB-API

Проект для получения данных с API Wildberries (stocks, incomes, sales, orders) и их сохранения в базе данных MySQL с использованием Laravel.

## Описание проекта
Проект автоматически запрашивает данные с API Wildberries через эндпоинты `/api/stocks`, `/api/incomes`, `/api/sales`, и `/api/orders`, сохраняя их в локальную базу данных. Данные загружаются с помощью консольной команды `app:fetch-api-data`, которая может быть запущена вручную или по расписанию. Проект настроен для работы с часовым поясом `Asia/Almaty` (+06).

- **Дата создания документации**: 05 июня 2025 года.
- **Текущее время**: 11:55 PM +06.
- **API-ключ**: Передаётся через параметр `key` в запросе (например, `E6kUTYrYwZq2tN4QEtyzsbEBk3ie`).

## Требования
- PHP >= 8.0
- Laravel >= 8.x
- MySQL
- Composer
- Cron (для планировщика)

## Инструкция по установке и настройке

### 1. Клонирование репозитория
```bash
git clone https://github.com/<ваш_пользователь>/wb-api.git
cd wb-api

2. Установка зависимостей
composer install

3. Настройка окружения

Скопируйте .env.example в .env:cp .env.example .env


Укажите параметры в .env:API_HOST=http://109.73.206.144:6969
API_KEY=E6kUTYrYwZq2tN4QEtyzsbEBk3ie
DB_CONNECTION=mysql
DB_HOST=<хост>
DB_PORT=3306
DB_DATABASE=wb_api_db
DB_USERNAME=<имя_пользователя>
DB_PASSWORD=<пароль>
APP_TIMEZONE=Asia/Almaty


Замените <хост>, <имя_пользователя>, <пароль> на ваши данные для подключения к базе данных.
APP_TIMEZONE=Asia/Almaty обеспечивает корректное время для планировщика (17:00 по местному времени).



4. Настройка базы данных

База данных может быть размещена на Aiven или GoogieHost.
Пример доступов:
Хост: <хост>
Порт: 3306
Имя базы: wb_api_db
Пользователь: <имя_пользователя>
Пароль: <пароль>


Выполните миграции для создания таблиц:php artisan migrate



5. Получение данных
Запустите команду для загрузки данных с API:
php artisan app:fetch-api-data --dateFrom=2025-01-01 --dateTo=2025-06-05


Опции --dateFrom и --dateTo задают диапазон дат для запроса. Если не указаны, по умолчанию используется период с прошлого месяца до текущей даты (например, с 2025-05-06 по 2025-06-05).

Таблицы базы данных
Проект использует следующие таблицы:

stocks (Остатки):
id, date, last_change_date, supplier_article, tech_size, barcode, quantity, is_supply, is_realization, quantity_full, warehouse_name, in_way_to_client, in_way_from_client, nm_id, subject, category, brand, sc_code, price, discount, created_at, updated_at


sales (Продажи):
id, g_number, date, last_change_date, supplier_article, tech_size, barcode, total_price, discount_percent, is_supply, is_realization, promo_code_discount, warehouse_name, country_name, oblast_okrug_name, region_name, income_id, sale_id (уникальный), odid, spp, for_pay, finished_price, price_with_disc, nm_id, subject, category, brand, is_storno, created_at, updated_at


incomes (Приходы):
id, income_id (уникальный), number, date, last_change_date, supplier_article, tech_size, barcode, quantity, total_price, date_close, warehouse_name, nm_id, created_at, updated_at


orders (Заказы):
id, g_number (уникальный), date, last_change_date, supplier_article, tech_size, barcode, total_price, discount_percent, warehouse_name, oblast, income_id, odid, nm_id, subject, category, brand, is_cancel, cancel_dt, created_at, updated_at



Планировщик
Для автоматической загрузки данных каждый день в 17:00 (по местному времени Asia/Almaty):

Планировщик уже настроен в app/Console/Kernel.php:protected function schedule(Schedule $schedule)
{
    $schedule->command('app:fetch-api-data')->dailyAt('17:00');
}


Настройте Cron для запуска планировщика Laravel каждую минуту:
Откройте Crontab:crontab -e


Добавьте строку:* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1


Замените /path-to-your-project на путь к вашему проекту (например, /var/www/html/wb-api).



Коллекция Postman
Для тестирования API используйте коллекцию Postman:

Импортируйте: https://www.postman.com/cy322666/workspace/app-api-test/overview
Настройка:
API-ключ передаётся через параметр key в запросе (например, key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie).
Authorization не требуется.
Пример параметров запроса:
dateFrom=2025-06-01
dateTo=2025-06-05
page=1
limit=100





Текущие данные

Stocks: На данный момент пусто (0 записей).
Sales: 1164 записи (по данным за период 2025-06-01 — 2025-06-05).
Incomes: 6 записей.
Orders: 1270 записей.

Логирование

Логи записываются в storage/logs/laravel.log.
Для просмотра:tail -f storage/logs/laravel.log


Логи содержат информацию о количестве загруженных записей и образцы данных (для incomes и orders).

Возможные проблемы и решения

Пустые эндпоинты:
Если данные для stocks, incomes, или orders отсутствуют, попробуйте изменить диапазон дат в запросе (например, dateFrom=2025-01-01).


Ошибки API:
Проверьте логи на наличие ошибок запросов.
Убедитесь, что API_KEY и API_HOST корректны в .env.


Проблемы с базой данных:
Убедитесь, что миграции применены:php artisan migrate


Проверьте настройки подключения в .env.


Планировщик не работает:
Убедитесь, что Cron настроен и работает:crontab -l


Проверьте, что часовой пояс в APP_TIMEZONE соответствует вашему (например, Asia/Almaty для +06).



Дополнительные заметки

Если структура данных API изменится (например, появятся новые поля), обновите миграции и модели:
Создайте новую миграцию:php artisan make:migration add_fields_to_<table>_table


Обновите $fillable в соответствующей модели.


Для оптимизации загрузки больших данных можно переписать updateOrCreate на пакетную вставку (insert), но это требует дополнительной обработки дубликатов.

Контакты
Если возникнут вопросы, обратитесь к разработчику: baktiarzaksylykov22@gmail.com.


