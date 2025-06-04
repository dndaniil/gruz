<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Получение типов грузов
$cargo_types = [];
$result = $conn->query("SELECT id, name FROM cargo_types ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $cargo_types[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    $user_id = $_SESSION['user_id'];
    $cargo_type_id = $_POST['cargo_type_id'];
    $delivery_date = $_POST['delivery_date'];
    $weight = floatval($_POST['weight']);
    $dimensions = $_POST['dimensions'];
    $pickup_address = $_POST['pickup_address'];
    $delivery_address = $_POST['delivery_address'];

    $stmt = $conn->prepare("INSERT INTO orders (user_id, cargo_type_id, delivery_date, weight, dimensions, pickup_address, delivery_address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdss", $user_id, $cargo_type_id, $delivery_date, $weight, $dimensions, $pickup_address, $delivery_address);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['redirect'] = 'orders.php';
    } else {
        $response['message'] = 'Ошибка при создании заявки';
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
    <title>Новая заявка - Грузовозофф</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <nav class="nav container">
            <div class="nav-logo">
                <a href="index.php" style="color: white; text-decoration: none;">Грузовозофф</a>
            </div>
            <div class="nav-links">
                <a href="orders.php">Мои заявки</a>
                <a href="logout.php">Выйти</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="form-container">
            <h2>Создание новой заявки</h2>
            <form id="newOrderForm" method="POST">
                <div class="form-group">
                    <label for="cargo_type_id">Тип груза</label>
                    <select id="cargo_type_id" name="cargo_type_id" required>
                        <option value="">Выберите тип груза</option>
                        <?php foreach ($cargo_types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type['id']); ?>">
                                <?php echo htmlspecialchars($type['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="delivery_date">Дата и время перевозки</label>
                    <input type="datetime-local" id="delivery_date" name="delivery_date" required>
                </div>

                <div class="form-group">
                    <label for="weight">Вес груза (кг)</label>
                    <input type="number" id="weight" name="weight" step="0.1" min="0" required>
                </div>

                <div class="form-group">
                    <label for="dimensions">Габариты груза</label>
                    <input type="text" id="dimensions" name="dimensions" placeholder="Например: 2x3x1.5 м" required>
                </div>

                <div class="form-group">
                    <label for="pickup_address">Адрес отправления</label>
                    <textarea id="pickup_address" name="pickup_address" required></textarea>
                </div>

                <div class="form-group">
                    <label for="delivery_address">Адрес доставки</label>
                    <textarea id="delivery_address" name="delivery_address" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Отправить заявку</button>
            </form>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
    <script>
        handleFormSubmit('newOrderForm', 'new-order.php');
    </script>
</body>
</html> 