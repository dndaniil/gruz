<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Проверка логина
    if (strlen($login) < 6) {
        $response['message'] = 'Логин должен содержать минимум 6 символов';
        echo json_encode($response);
        exit;
    }

    // Проверка существования логина
    $stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $response['message'] = 'Такой логин уже существует';
        echo json_encode($response);
        exit;
    }

    // Хеширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Добавление пользователя
    $stmt = $conn->prepare("INSERT INTO users (login, password, full_name, phone, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $login, $hashed_password, $full_name, $phone, $email);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['redirect'] = 'login.php';
    } else {
        $response['message'] = 'Ошибка при регистрации';
    }

    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Грузовозофф</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <nav class="nav container">
            <div class="nav-logo">
                <a href="index.php" style="color: white; text-decoration: none;">Грузовозофф</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="form-container">
            <h2>Регистрация</h2>
            <form id="registerForm" method="POST">
                <div class="form-group">
                    <label for="login">Логин (минимум 6 символов)</label>
                    <input type="text" id="login" name="login" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль (минимум 6 символов)</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="full_name">ФИО</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="tel" id="phone" name="phone" placeholder="+7(XXX)-XXX-XX-XX" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>
            <p style="margin-top: 1rem;">
                Уже есть аккаунт? <a href="login.php">Войти</a>
            </p>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
    <script>
        handleFormSubmit('registerForm', 'register.php');
    </script>
</body>
</html> 