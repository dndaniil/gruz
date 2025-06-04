<?php
// Database configuration
define('DB_HOST', '10.37.20.12');
define('DB_USER', 'ddzfskgv');
define('DB_PASS', 'duB2mG');
define('DB_NAME', 'ddzfskgv');

// Server paths
define('PHPMYADMIN_URL', 'http://10.37.20.12/phpmyadmin/');
define('SSH_HOST', '10.37.20.12');
define('SAMBA_PATH', '\\\\10.37.20.12\\ddzfskgv');

// Other settings
define('DEBUG_MODE', true);

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?> 