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

    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            $response['success'] = true;
            $response['redirect'] = $user['is_admin'] ? 'admin/index.php' : 'index.php';
        } else {
            $response['message'] = 'Неверный пароль';
        }
    } else {
        $response['message'] = 'Пользователь не найден';
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
    <title>Вход - Грузовозофф</title>
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
            <h2>Вход в систему</h2>
            <form id="loginForm" method="POST">
                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" id="login" name="login" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
            <p style="margin-top: 1rem;">
                Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
            </p>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
    <script>
        handleFormSubmit('loginForm', 'login.php');
    </script>
</body>
</html> 