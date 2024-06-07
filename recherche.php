<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits par Catégorie</title>
    <link href="nouv/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            background-color: #f4f4f4;
        }
        .container {
            position: relative;
            top: 200px;
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 20px;
        }
        .product {
            display:grid;
            border-bottom: 1px solid #ddd;
            padding: 20px 10px;
        }
        .product:last-child {
            border-bottom: none;
        }
        .product h3 {
            color: #333;
            font-size: 1.5em;
        }
        .product p {
            color: #666;
            font-size: 1em;
        }
        .product .price {
            font-weight: bold;
            color: #2a2a2a;
            font-size: 1.2em;
        }
        .product img {
            object-fit: contain;
            min-width: 100%;
            height: 400px;
            margin: 10px 0;
            border-radius: 10px;
        }
        .product a.btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .product a.btn:hover {
            background-color: #0056b3;
        }
        .product a.btn.btn-primary {
            background-color: #28a745;
        }
        .product a.btn.btn-primary:hover {
            background-color: #1e7e34;
        }
        #searchForm {
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .aa {
            padding: 5px 10px;
            border: 5px solid #007bff;
            right: 20px;
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
        .navbars{
            position: fixed;
            top: 1px;
            left: 0;
            height: 70px;
            width: 100%;
            background-color: orange; 
            z-index: 1000;
        }
        .navbars .divv{
            position: absolute;
            top: 10px;
            left: 30%;
        }
        .resul{
            color: orange;
            font-weight: bolder;
            font-size: 1.2em;
        }
    </style>
</head>

<body>
    <header>
        <!-- En-tête ici -->
    </header>

    <div class="containerss">
        <a class="aa" href="index.php">retour</a>
        <nav class="navbars ">
            <div class="containe divv" >
                <form class="d-flex" method="POST" id="searchForms" >
                    <input class="form-control me-2" id="searchInput" name="search" type="search" placeholder="Recherche..." aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </nav>
       

        <div id="results" class="results"></div>
        <div class="container containers">
            <!-- Les produits seront chargés ici par Fetch API -->
            <?php
            include 'php/includes/db.php';
            // Vérifier si une recherche a été soumise
            if (isset($_POST['search']) AND !empty($_POST['search'])) {
                // Nettoyer le terme de recherche
                $search_term = htmlspecialchars($_POST['search']);

                // Préparer la requête de recherche
                        $query = "SELECT produits.*, categories.nom AS nom_categorie, utilisateurs.nom AS nom_auteur
                        FROM produits 
                        LEFT JOIN categories ON produits.categorie_id = categories.id 
                        LEFT JOIN utilisateurs ON produits.admin_id = utilisateurs.id
                        WHERE titre LIKE :search_term 
                        OR acheteur LIKE :search_term 
                        OR categorie_id LIKE :search_term 
                        OR image LIKE :search_term 
                        OR description LIKE :search_term 
                        OR prix LIKE :search_term 
                        OR utilisateurs.nom LIKE :search_term"; // Ajout de la clause LIKE pour le nom de l'auteur
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(':search_term', "%$search_term%", PDO::PARAM_STR);
                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // $stmt = $pdo->prepare($query);
                // $stmt->bindValue(':search_term', "%$search_term%", PDO::PARAM_STR);
                // $stmt->execute();
                // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Afficher les résultats de la recherche
                if ($stmt->rowCount() > 0) {
                    echo "<h2>Résultats de la recherche pour : <p class=\"resul\"> {$search_term}...</p> </h2>";
                    foreach ($results as $result) {
                        echo "<div class='product'>";
                        echo "<h3>{$result['titre']}</h3>";
                        echo "<p>Acheteur: {$result['acheteur']}</p>";
                        echo "<p>Catégorie ID: {$result['categorie_id']}</p>";
                        echo "<p>Description: {$result['description']}</p>";
                        echo "<p class='price'>Prix: {$result['prix']} Frc</p>";
                        // echo "<p>Admin ID:  {$result['admin_id']}</p>";
                         // echo "<p>Admin ID:  " . $row["admin_id"] . "</p>";
                        echo "<p>Publié par: {$result['nom_auteur']}</p>";
                        // Affichage de l'image si disponible
                        if (!empty($result['image'])) {
                            $chemin_image = "php/" . $result['image'];
                            echo "<a href='{$chemin_image}'> <img src='{$chemin_image}' alt='{$result['titre']}'></a>";
                        } else {
                            echo "<pre>Aucune image disponible pour : <p>{$result['titre']}</p></pre> ";
                        }

                        echo "<a href='detail.php?id={$result['id']}' class='btn'>Voir Détails</a>";
                        echo "<a href='php/acheter.php?id={$result['id']}' class='btn btn-primary'>Acheter maintenant</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Aucun résultat trouvé pour:<p class=\"resul\">  {$search_term}</p></p>";
                }
            }
            ?>
        </div>
    </div>

    <script src="nouv/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts ici -->
</body>
</html>
