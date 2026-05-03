<?php
declare(strict_types=1);

define('DB_HOST', 'MySQL-8.4');
define('DB_PORT', 3306);
define('DB_NAME', 'ittender_db');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SITE_NAME', 'ООО ИТ-ТЕНДЕР');
define('BASE_URL', '/ittender');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
