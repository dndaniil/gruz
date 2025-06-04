@echo off
echo Starting Грузовозофф...

REM Проверяем, установлен ли XAMPP
if not exist "C:\xampp\apache_start.bat" (
    echo XAMPP не установлен! 
    echo Пожалуйста, установите XAMPP с сайта https://www.apachefriends.org/
    pause
    exit
)

REM Запускаем Apache
echo Запуск Apache...
start C:\xampp\apache_start.bat

REM Открываем браузер с нашим сайтом
echo Открываем сайт...
timeout 2
start http://localhost/gruz

echo.
echo Для остановки сервера закройте это окно и XAMPP Control Panel
pause 