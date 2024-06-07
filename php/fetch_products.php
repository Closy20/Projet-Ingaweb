<?php
// require_once 'includes/db.php';

// header('Content-Type: application/json');

// try {
//     // Récupérer toutes les catégories
//     $stmt = $pdo->query('SELECT * FROM categories');
//     $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Récupérer les produits pour chaque catégorie
//     foreach ($categories as $key => $category) {
//         $stmt = $pdo->prepare('SELECT * FROM produits WHERE categorie_id = ?');
//         $stmt->execute([$category['id']]);
//         $categories[$key]['products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     }

//     echo json_encode($categories);
// } catch (PDOException $e) {
//     echo json_encode(['error' => 'Erreur de base de données : ' . $e->getMessage()]);
// }

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
require_once 'includes/db.php';

$query = '';
if (isset($_GET['q'])) {
    $query = trim($_GET['q']);
}

try {
    if (!empty($query)) {
        $stmt = $pdo->prepare("
            SELECT p.*, c.nom as category_name 
            FROM produits p
            JOIN categories c ON p.categorie_id = c.id
            WHERE p.titre LIKE :query OR c.nom LIKE :query
        ");
        $stmt->execute(['query' => '%' . $query . '%']);
    } else {
        $stmt = $pdo->prepare("
            SELECT p.*, c.nom as category_name 
            FROM produits p
            JOIN categories c ON p.categorie_id = c.id
        ");
        $stmt->execute();
    }

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $categories = [];
    foreach ($products as $product) {
        $categories[$product['category_name']]['nom'] = $product['category_name'];
        $categories[$product['category_name']]['products'][] = $product;
    }

    echo json_encode(array_values($categories));
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération des produits : ' . $e->getMessage()]);
}


?>
