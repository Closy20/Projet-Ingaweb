<?php
require_once 'php/includes/db.php';

try {
    // Démarrez une transaction
    $pdo->beginTransaction();

    // Récupérer toutes les catégories
    $stmt = $pdo->query('SELECT * FROM categories');
    $categories = $stmt->fetchAll();

    // Récupérer les produits pour chaque catégorie
    foreach ($categories as $key => $category) {
        $stmt = $pdo->prepare('SELECT * FROM produits WHERE categorie_id = ?');
        $stmt->execute([$category['id']]);
        $categories[$key]['products'] = $stmt->fetchAll();
    }

    // Validez la transaction
    $pdo->commit();
} catch (PDOException $e) {
    // En cas d'erreur, annulez la transaction
    $pdo->rollBack();
    echo "Erreur de base de données : " . $e->getMessage();
    // Vous pouvez également enregistrer l'erreur dans un fichier journal ou afficher un message d'erreur à l'utilisateur.
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits par Catégorie</title>
    <link href="nouv/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="nouv/css/style.css">
    <style>
        /* Ajoutez votre CSS ici */
         body {
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #343a40;
    }

    .containers {
        padding: 20px;
        position: relative;
        top: 150px;
    }

    .category {
        margin-bottom: 40px;
    }

    .category h2 {
        margin-bottom: 20px;
    }

    .products {
        display: flex;
        flex-wrap: wrap;
    }

    .product {
        width: 30%;
        margin-right: 1.66%;
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        background-color: #ffffff;
    }

    .product img {
        max-width: 100%;
        height: auto;
        display: block;
        margin-bottom: 10px;
    }

    .product h3 {
        margin-bottom: 10px;
    }

    .product p {
        margin-bottom: 10px;
    }

    .product .price {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .product .btn {
        display: inline-block;
        padding: 8px 12px;
        font-size: 14px;
        color: #ffffff;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        cursor: pointer;
    }

    .product .btn:hover {
        opacity: 0.8;
    }
  
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100 navbar-dark cc-navbar">
            <div class="container">
                <a class="navbar-brand fw-bolder" href="#">Clark Kente</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-0 mb-lg-0" style="position: relative; top: 20px; left: 100px;">
                        <form class="" style="position: relative; top: -15px;">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    
                    <li class="nav-item1 pe-3">
                        <a class="btn btn-orders" style="margin-left: 100px;" href="php/connexion.php">SignUp</a>
                    </li>
                    <li class="nav-item pe-4">
                        <a class="btn btn-order" href="php/index.php">Login</a>
                    </li>
                    <li class="nav-item pe-4">
                        <a class="btn btn-orders" href="#">Decouvrir</a>
                    </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container containers">
        <?php foreach ($categories as $category) : ?>
            <div class="category">
                <h2><?php echo htmlspecialchars($category['nom']); ?></h2>
                <div class="products">
                    <?php if (isset($category['products']) && count($category['products']) > 0) : ?>
                        <?php foreach ($category['products'] as $product) : ?>
                            <div class="product">
                                <img src="php/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['titre']); ?>">
                                <h3><?php echo htmlspecialchars($product['titre']); ?></h3>
                                <p><?php echo htmlspecialchars($product['description']); ?></p>
                                <p class="price"><?php echo htmlspecialchars($product['prix']); ?> €</p>
                                <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn">Voir Détails</a>
                                <a href="php/acheter.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Acheter maintenant</a>
                                <a href="acheter.php?id=<?php echo $product['id']; ?>" class="btn">Acheter</a>

                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>Aucun produit trouvé pour cette catégorie.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'php/view/footer.php'; ?>
    <script src="nouv/js/bootstrap.bundle.min.js"></script>

</body>
</html>
