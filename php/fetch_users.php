<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: connexion.php');
    exit();
}

// Récupérer la liste des utilisateurs (en excluant l'utilisateur connecté)
$stmt = $pdo->prepare('SELECT id, nom FROM utilisateurs WHERE id != ?');
$stmt->execute([$_SESSION['admin_id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
?>
