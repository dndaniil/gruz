<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Грузовозофф - Грузоперевозки</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .hero {
            text-align: center;
            padding: 4rem 0;
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(44, 62, 80, 0.9)), url('assets/img/truck.jpg');
            background-size: cover;
            color: white;
            margin-bottom: 2rem;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 2rem 0;
        }
        
        .feature-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .feature-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .cta-button {
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
        }
        
        .cta-primary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .cta-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .how-it-works {
            padding: 3rem 0;
            background-color: #f8f9fa;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .step {
            text-align: center;
            padding: 1rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-weight: bold;
        }

        @media (max-width: 390px) {
            .hero {
                padding: 2rem 0;
            }
            
            .hero h1 {
                font-size: 1.8rem;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
            
            .cta-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav container">
            <div class="nav-logo">Грузовозофф</div>
            <div class="nav-links">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="login.php">Войти</a>
                    <a href="register.php">Регистрация</a>
                <?php else: ?>
                    <a href="orders.php">Мои заявки</a>
                    <a href="new-order.php">Создать заявку</a>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <a href="admin/index.php">Панель администратора</a>
                    <?php endif; ?>
                    <a href="logout.php">Выйти</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Грузоперевозки по всей России</h1>
                <p>Быстро, надежно, профессионально</p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="cta-buttons">
                        <a href="register.php" class="cta-button cta-primary">Зарегистрироваться</a>
                        <a href="login.php" class="cta-button cta-secondary">Войти</a>
                    </div>
                <?php else: ?>
                    <div class="cta-buttons">
                        <a href="new-order.php" class="cta-button cta-primary">Создать заявку</a>
                        <a href="orders.php" class="cta-button cta-secondary">Мои заявки</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="container">
            <h2 style="text-align: center; margin-bottom: 2rem;">Наши преимущества</h2>
            <div class="features">
                <div class="feature-card">
                    <h3>Широкий выбор транспорта</h3>
                    <p>От малых фургонов до крупнотоннажных грузовиков</p>
                </div>
                <div class="feature-card">
                    <h3>Безопасность</h3>
                    <p>Все грузы застрахованы, опытные водители</p>
                </div>
                <div class="feature-card">
                    <h3>Отслеживание</h3>
                    <p>Контроль статуса вашего заказа онлайн</p>
                </div>
                <div class="feature-card">
                    <h3>Доступные цены</h3>
                    <p>Конкурентные тарифы и система скидок</p>
                </div>
            </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <h2 style="text-align: center; margin-bottom: 2rem;">Как это работает</h2>
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h3>Регистрация</h3>
                        <p>Создайте аккаунт на нашем сайте</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h3>Заявка</h3>
                        <p>Укажите детали перевозки</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h3>Подтверждение</h3>
                        <p>Мы обработаем вашу заявку</p>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <h3>Перевозка</h3>
                        <p>Доставим ваш груз вовремя</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="header" style="margin-top: 3rem; padding: 2rem 0;">
        <div class="container" style="text-align: center; color: white;">
            <p>© 2024 Грузовозофф - Все права защищены</p>
            <p style="margin-top: 0.5rem;">Телефон: +7 (XXX) XXX-XX-XX | Email: info@gruzovozoff.ru</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html> 