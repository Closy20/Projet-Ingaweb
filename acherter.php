<?php
// session_start();
//     // Connexion à la base de données
//     require_once 'php/includes/db.php';

//     // Vérifiez si une nouvelle transaction a été soumise
//     if ($_SERVER["REQUEST_METHOD"] == "POST") {
//         // Vérifiez si l'utilisateur est connecté
//         if (!isset($_SESSION['admin_id'])) {
//             echo "Vous devez être connecté pour effectuer un achat.";
//         } else {
//             // Récupérez les détails de la transaction à partir du formulaire
//             $product_id = $_POST['product_id'];
//             $acheteur = $_SESSION['admin_id']; // Utilisez l'ID de l'utilisateur connecté

//             // Vérifiez la validité des champs
//             if (empty($product_id)) {
//                 echo "Le produit est requis.";
//             } else {
//                 // Insérez la nouvelle transaction dans la base de données
//                 $stmt = $pdo->prepare("
//                     INSERT INTO transactions (product_id, acheteur)
//                     VALUES (:product_id, :acheteur)
//                 ");
//                 $stmt->bindParam(':product_id', $product_id);
//                 $stmt->bindParam(':acheteur', $acheteur);
//                 $stmt->execute();

//                 echo "Transaction ajoutée avec succès.";
//             }
//         }
//     }

//     // ...
//     // Récupérez toutes les transactions de la base de données
//     $stmt = $pdo->prepare("
//         SELECT transactions.*, produits.titre AS titre_produit
//         FROM transactions
//         LEFT JOIN produits ON transactions.product_id = produits.id
//     ");
//     $stmt->execute();

//     if ($stmt->rowCount() > 0) {
//         // Affichez les détails de chaque transaction
//         while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//             echo "<div>";
//             echo "<h3>" . $row["titre_produit"] . "</h3>";
//             echo "<p>Acheteur: " . $row["acheteur"] . "</p>";
//             echo "</div>";
//         }
//     } else {
//         echo "Aucune transaction trouvée.";
//     }


?>


<?php
    // Commencez la session
    session_start();
    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Connexion à la base de données
    require_once 'php/includes/db.php';

    // Vérifiez si l'utilisateur est connecté
    if (!isset($_SESSION['admin_id'])) {
        // Si l'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
        header('Location: php/connexion.php');
        exit;
    }

    // Vérifiez si une nouvelle transaction a été soumise
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérez les détails de la transaction à partir du formulaire
        // $product_id = $_POST['product_id'];
        // $acheteur = $_SESSION['admin_id']; // Utilisez l'ID de l'utilisateur connecté

        // // Récupérez les informations du produit
        // $stmt = $pdo->prepare("
        //     SELECT * FROM produits
        //     WHERE id = :product_id
        // ");
        // $stmt->bindParam(':product_id', $product_id);
        // $stmt->execute();
        // $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // $utilisateur_id = $_POST['utilisateur_id']; // ou toute autre méthode que vous utilisez pour obtenir cette valeur

        if ($utilisateur_id === NULL) {
            // Gérer l'erreur : l'utilisateur_id ne peut pas être NULL
            die('Erreur : utilisateur_id ne peut pas être NULL');
        }

        // Continuez à exécuter votre requête SQL ici

        $product_id = $_POST['product_id']; // Assurez-vous que cette valeur est correcte

        $stmt = $pdo->prepare("
            SELECT * FROM produits
            WHERE id = :product_id
        ");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Insérez la nouvelle transaction dans la base de données
            $stmt = $pdo->prepare("
                INSERT INTO transactions (product_id, categorie_id, utilisateur_id, titre, acheteur)
                VALUES (:product_id, :categorie_id, :utilisateur_id, :titre, :acheteur)
            ");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':categorie_id', $product['categorie_id']);
            $stmt->bindParam(':utilisateur_id', $acheteur);
            $stmt->bindParam(':titre', $product['titre']);
            $stmt->bindParam(':acheteur', $product['acheteur']);
            $stmt->execute();

            echo "Transaction ajoutée avec succès.";
        } else {
            echo "Le produit n'a pas été trouvé.";
        }
    }
    // ...
?>

