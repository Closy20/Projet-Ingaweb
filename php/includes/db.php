<?php
$host = 'localhost';
$db = 'clark-boutiques';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    Echo 'Connection failed: ' . $e->getMessage();
}
?>
