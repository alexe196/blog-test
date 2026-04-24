# Blog Test Task

Простой блог на чистом PHP без фреймворков.

## Что использовалось

- PHP 8.1+
- MySQL
- PDO
- Smarty
- Composer autoload
- Apache
- Docker / Docker Compose
- SCSS

## Установка без Docker

Подходит для OpenServer или локального PHP-сервера.

### 1. Установить зависимости

```bash
composer install
```

### 2. Настроить подключение к базе

Файл:

```text
config/config.php
```

Для OpenServer обычно подходит:

```php
'host' => '127.0.0.1',
'port' => '3306',
'database' => 'blog_test',
'username' => 'root',
'password' => '',
```

### 3. Выполнить миграцию

```bash
php database/migrate.php
```

Миграция создаст базу данных `blog_test` и все таблицы.

### 4. Заполнить базу тестовыми данными

```bash
php database/seed.php
```

### 5. Запустить сайт

```bash
php -S localhost:8000 -t public
```

Открыть сайт:

```text
http://localhost:8000
```

---

## Запуск через Docker

### 1. Собрать и запустить контейнеры

```bash
docker compose up -d --build
```

### 2. Установить зависимости внутри контейнера

```bash
docker compose exec app php composer.phar install
```

Если Composer доступен как обычная команда:

```bash
docker compose exec app composer install
```

### 3. Выполнить миграцию

```bash
docker compose exec app php database/migrate.php
```

### 4. Заполнить базу тестовыми данными

```bash
docker compose exec app php database/seed.php
```

### 5. Открыть сайт

```text
http://localhost:8080
```

### 6. Открыть базу данных через phpMyAdmin

```text
http://localhost:8081
```

Данные для входа:

```text
Server: mysql
User: root
Password: root
Database: blog_test
```

---

## Подключение к Docker MySQL с компьютера

Если нужно подключиться к базе не через phpMyAdmin, а через клиент типа HeidiSQL, DBeaver или DataGrip:

```text
Host: 127.0.0.1
Port: 3307
User: root
Password: root
Database: blog_test
```

---

## Основные страницы сайта

```text
/                             Главная страница
/category/{id}/               Страница категории
/category/{id}/post/{id}      Страница статьи
```

Примеры:

```text
http://localhost:8000/
http://localhost:8080/category/1/post/1
```