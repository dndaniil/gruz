<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Получение заявок пользователя
$user_id = $_SESSION['user_id'];
$orders = [];

$query = "SELECT o.*, ct.name as cargo_type, 
          (SELECT COUNT(*) FROM reviews r WHERE r.order_id = o.id) as has_review 
          FROM orders o 
          JOIN cargo_types ct ON o.cargo_type_id = ct.id 
          WHERE o.user_id = ? 
          ORDER BY o.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// Обработка добавления отзыва
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $response = ['success' => false, 'message' => ''];
    
    $order_id = $_POST['order_id'];
    $review_text = trim($_POST['review_text']);

    // Проверка, что заявка принадлежит пользователю
    $stmt = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows === 1) {
        $stmt = $conn->prepare("INSERT INTO reviews (order_id, user_id, text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $order_id, $user_id, $review_text);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Отзыв успешно добавлен';
        } else {
            $response['message'] = 'Ошибка при добавлении отзыва';
        }
    } else {
        $response['message'] = 'Заявка не найдена';
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
    <title>Мои заявки - Грузовозофф</title>
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
                <a href="new-order.php">Создать заявку</a>
                <a href="logout.php">Выйти</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <h2>Мои заявки</h2>
        <div class="orders-list">
            <?php if (empty($orders)): ?>
                <p>У вас пока нет заявок</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <h3>Заявка №<?php echo htmlspecialchars($order['id']); ?></h3>
                        <p><strong>Статус:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                        <p><strong>Тип груза:</strong> <?php echo htmlspecialchars($order['cargo_type']); ?></p>
                        <p><strong>Дата перевозки:</strong> <?php echo htmlspecialchars($order['delivery_date']); ?></p>
                        <p><strong>Вес:</strong> <?php echo htmlspecialchars($order['weight']); ?> кг</p>
                        <p><strong>Габариты:</strong> <?php echo htmlspecialchars($order['dimensions']); ?></p>
                        <p><strong>Откуда:</strong> <?php echo htmlspecialchars($order['pickup_address']); ?></p>
                        <p><strong>Куда:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                        
                        <?php if ($order['status'] === 'В работе' && !$order['has_review']): ?>
                            <div class="review-form" style="margin-top: 1rem;">
                                <h4>Оставить отзыв</h4>
                                <form class="review-form" method="POST" onsubmit="return handleReviewSubmit(this, <?php echo $order['id']; ?>)">
                                    <div class="form-group">
                                        <textarea name="review_text" required placeholder="Ваш отзыв о перевозке"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Отправить отзыв</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
    <script>
        function handleReviewSubmit(form, orderId) {
            const formData = new FormData(form);
            formData.append('order_id', orderId);

            fetch('orders.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Отзыв успешно добавлен');
                    location.reload();
                } else {
                    alert(data.message || 'Произошла ошибка');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при отправке отзыва');
            });

            return false;
        }
    </script>
</body>
</html> 