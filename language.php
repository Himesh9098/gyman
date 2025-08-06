<?php
session_start();
if (isset($_POST['toggle_lang'])) {
    $_SESSION['lang'] = ($_SESSION['lang'] ?? 'en') === 'en' ? 'hi' : 'en';
}
$ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $ref);
exit();