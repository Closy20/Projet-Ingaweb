        <a class="aa" href="index.php">retour</a>

<?php

    // Connexion à la base de données
    require_once 'php/includes/db.php';

    // Récupérez l'ID du produit à partir de l'URL
    $product_id = $_GET['id'];

    // Sélectionnez le produit avec cet ID
    // $stmt = $pdo->prepare("
    //     SELECT produits.*, categories.nom AS nom_categorie, utilisateurs.nom AS nom 
    //     FROM produits 
    //     LEFT JOIN categories ON produits.categorie_id = categories.id 
    //     LEFT JOIN utilisateurs ON produits.admin_id = utilisateurs.id
    //     WHERE produits.id = :id
    // ");
       $stmt = $pdo->prepare("
            SELECT produits.*, categories.nom AS nom_categorie, utilisateurs.nom AS nom, transactions.acheteur AS nom_acheteur
            FROM produits 
            LEFT JOIN categories ON produits.categorie_id = categories.id 
            LEFT JOIN utilisateurs ON produits.admin_id = utilisateurs.id
            LEFT JOIN transactions ON produits.id = transactions.product_id
            WHERE produits.id = :id
        ");
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Affichez les détails du produit
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='product'>";
            echo "<h3>" . $row["titre"] . "</h3>";
            // echo "<a href="php/${product.image}"> <img src="php/${product.image}" alt="${product.titre}"></a>";
            if (isset($row["image"])) {
                echo "<img src='php/" . $row["image"] . "' alt='Aucune image pour ce produit'>";
            } else {
                echo "<p>Image du produit: Non disponible</p>";
            }

            if (isset($row["nom_acheteur"])) {
                echo "<p>Acheteur: " . $row["nom_acheteur"] . "</p>";
            } else {
                echo "<p>Acheteur: Non disponible</p>";
            }

            echo "<p>Catégorie: " . $row["nom_categorie"] . "</p>";
            echo "<p>Description: " . $row["description"] . "</p>";
            echo "<p class='price'>Prix: " . $row["prix"] . " Frc</p>";
            // echo "<p>Admin ID:  " . $row["admin_id"] . "</p>";
            if (isset($row["nom"])) {
                echo "<p> Publier par: " . $row["nom"] . "</p>";
            } else {
                echo "<p>Auteur de la publication: Non disponible</p>";
            }


            echo "</div>";
        }
    } else {
        echo "Aucun produit trouvé.";
    }
?>

<style>
    .product {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease-in-out;
    }
    .product:hover {
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
    .product h3 {
        color: orange;
        font-size: 24px;
        margin-bottom: 10px;
    }
    .product img {
        width: 500px;
        height: 500px;
        border-radius: 10px;
        margin-bottom: 10px;
    }
    .product p {
        color: #666;
        font-size: 16px;
        line-height: 1.6;
    }
    .product .price {
        color: #e74c3c;
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
    }
     .aa {
            padding: 5px 10px;
            border: 5px solid #007bff;
            right: 10px;
            font-size: 1.2em;
            text-transform: uppercase;
            font-weight: bolder;
            position: fixed;
            top: 10px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
            z-index: 1100;
        }

        .aa:hover {
            color: #0056b3;
        }
</style>

