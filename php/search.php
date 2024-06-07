<?php
    // header('Content-Type: application/json');

    // // Connectez-vous à votre base de données
    // $db = new PDO('mysql:host=localhost;dbname=nom_de_votre_base_de_donnees;charset=utf8', 'username', 'password');

    // // Assurez-vous qu'une recherche a été soumise
    // if(isset($_POST['search'])) {
    //     $search = htmlspecialchars($_POST['search']);
    //     $query = $db->prepare("SELECT * FROM table_produits WHERE titre LIKE :search OR description LIKE :search");
    //     $query->execute(['search' => "%$search%"]);
    //     $results = $query->fetchAll(PDO::FETCH_ASSOC);
    //     echo json_encode($results);
    // } else {
    //     echo json_encode(['error' => 'Aucune recherche soumise.']);
    // }

    header('Content-Type: application/json');

    // Connectez-vous à votre base de données
    include "includes/db.php";

    // Assurez-vous qu'une recherche a été soumise
    if(isset($_POST['search'])) {
        $search = htmlspecialchars($_POST['search']);
        $query = $pdo->prepare("SELECT * FROM produits WHERE titre LIKE :search OR description LIKE :search");
        $query->execute(['search' => "%$search%"]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } else {
        echo json_encode(['error' => 'Aucune recherche soumise.']);
    }

?>
