<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}

// Обработка изменения статуса заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Статус заявки обновлен';
    } else {
        $response['message'] = 'Ошибка при обновлении статуса';
    }

    echo json_encode($response);
    exit;
}

// Получение всех заявок
$query = "SELECT o.*, u.full_name, u.phone, u.email, ct.name as cargo_type 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          JOIN cargo_types ct ON o.cargo_type_id = ct.id 
          ORDER BY o.created_at DESC";

$result = $conn->query($query);
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора - Грузовозофф</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-orders {
            margin: 2rem 0;
        }
        .order-card {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status-controls {
            margin-top: 1rem;
            display: flex;
            gap: 1rem;
        }
        .contact-info {
            margin: 1rem 0;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav container">
            <div class="nav-logo">
                <a href="../index.php" style="color: white; text-decoration: none;">Грузовозофф</a>
            </div>
            <div class="nav-links">
                <a href="../logout.php">Выйти</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <h2>Управление заявками</h2>
        <div class="admin-orders">
            <?php if (empty($orders)): ?>
                <p>Заявок пока нет</p>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <h3>Заявка №<?php echo htmlspecialchars($order['id']); ?></h3>
                        <p><strong>Статус:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                        <p><strong>Дата создания:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
                        
                        <div class="contact-info">
                            <h4>Информация о клиенте:</h4>
                            <p><strong>ФИО:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
                            <p><strong>Телефон:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                        </div>

                        <p><strong>Тип груза:</strong> <?php echo htmlspecialchars($order['cargo_type']); ?></p>
                        <p><strong>Дата перевозки:</strong> <?php echo htmlspecialchars($order['delivery_date']); ?></p>
                        <p><strong>Вес:</strong> <?php echo htmlspecialchars($order['weight']); ?> кг</p>
                        <p><strong>Габариты:</strong> <?php echo htmlspecialchars($order['dimensions']); ?></p>
                        <p><strong>Откуда:</strong> <?php echo htmlspecialchars($order['pickup_address']); ?></p>
                        <p><strong>Куда:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>

                        <div class="status-controls">
                            <button class="btn btn-primary" onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'В работе')" <?php echo $order['status'] === 'В работе' ? 'disabled' : ''; ?>>
                                В работе
                            </button>
                            <button class="btn btn-danger" onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'Отменена')" <?php echo $order['status'] === 'Отменена' ? 'disabled' : ''; ?>>
                                Отменить
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function updateOrderStatus(orderId, status) {
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('status', status);

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Статус заявки обновлен');
                    location.reload();
                } else {
                    alert(data.message || 'Произошла ошибка');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при обновлении статуса');
            });
        }
    </script>
</body>
</html> 