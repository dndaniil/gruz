# Грузовозофф - Система управления грузоперевозками

## Установка и запуск на Windows 10

### 1. Установка необходимого ПО
1. Скачайте и установите XAMPP с официального сайта:
   - Перейдите на https://www.apachefriends.org/
   - Скачайте XAMPP для Windows
   - Установите, следуя инструкциям установщика

2. Установите Git для Windows:
   - Скачайте с https://git-scm.com/download/win
   - Установите, используя стандартные настройки

### 2. Клонирование проекта
1. Откройте командную строку (CMD) от имени администратора
2. Перейдите в папку веб-сервера:
```cmd
cd C:\xampp\htdocs
git clone https://github.com/dndaniil/gruz.git
```

### 3. Запуск проекта
1. Запустите XAMPP Control Panel
2. Нажмите "Start" для Apache
3. Откройте в браузере: http://localhost/gruz

### 4. Доступ к серверу
- Доступ к phpMyAdmin: http://10.37.20.12/phpmyadmin/
  - Логин: ddzfskgv
  - Пароль: duB2mG
- Доступ к файлам через Samba: 
  1. Откройте Проводник Windows
  2. В адресной строке введите: \\10.37.20.12\ddzfskgv
  3. Используйте те же логин и пароль

### Возможные проблемы и решения
1. Если порт 80 занят (Apache не запускается):
   - Откройте XAMPP Control Panel
   - Нажмите Config -> Apache (httpd.conf)
   - Измените порт с 80 на 8080
   - Перезапустите Apache
   - Используйте http://localhost:8080/gruz

2. Если не работает подключение к серверу:
   - Проверьте подключение к сети
   - Убедитесь, что вы в той же сети, что и сервер
   - Попробуйте ping 10.37.20.12

## Информационная система для заказа грузоперевозок автомобильным транспортом.

## Требования

- PHP 7.4 или выше
- MySQL 5.7 или выше
- Apache/Nginx веб-сервер

## Установка

1. Клонируйте репозиторий в директорию вашего веб-сервера:
```bash
git clone https://github.com/yourusername/gruzovozoff.git
cd gruzovozoff
```

2. Создайте базу данных MySQL и импортируйте структуру из файла `database.sql`:
```bash
mysql -u root -p
CREATE DATABASE gruzovozoff;
exit;
mysql -u root -p gruzovozoff < database.sql
```

3. Настройте подключение к базе данных в файле `config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'gruzovozoff');
```

4. Настройте права доступа к директориям:
```bash
chmod 755 -R .
chmod 777 -R uploads # если будет добавлена загрузка файлов
```

5. Настройте виртуальный хост Apache/Nginx для указания на директорию проекта.

## Функционал

- Регистрация и авторизация пользователей
- Создание заявок на грузоперевозку
- Просмотр статуса заявок
- Добавление отзывов о выполненных перевозках
- Панель администратора для управления заявками

## Данные для входа администратора

- Логин: admin
- Пароль: gruzovik2024

## Структура проекта

```
gruzovozoff/
├── admin/
│   └── index.php         # Панель администратора
├── assets/
│   ├── css/
│   │   └── style.css     # Основные стили
│   └── js/
│       └── main.js       # JavaScript функции
├── config.php            # Конфигурация базы данных
├── database.sql          # Структура базы данных
├── index.php            # Главная страница
├── login.php            # Страница входа
├── logout.php           # Выход из системы
├── new-order.php        # Создание заявки
├── orders.php           # Просмотр заявок
├── register.php         # Регистрация
└── README.md            # Документация
```

## Безопасность

- Все пароли хешируются с использованием password_hash()
- Используются подготовленные запросы для защиты от SQL-инъекций
- Валидация всех входных данных
- Защита от XSS с помощью htmlspecialchars()
- Проверка прав доступа для всех операций 