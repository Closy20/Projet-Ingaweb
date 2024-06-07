<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits par Catégorie</title>
    <link href="nouv/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="nouv/css/style.css">
    <style>
        /* Ajoutez votre CSS ici */
       body {
        
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            color: #343a40;
            transition: all 0.3s ease;
            height: 100vh;
            width: 100%;
        }

        .containers {
            padding: 20px;
            position: relative;
            top: 150px;
        }

        .category {
            margin-bottom: 40px;
            border: 2px solid black;
            padding: 20px;
        }

        .category h2 {
            margin-bottom: 20px;
            color: #007bff;
            transition: color 0.3s ease;
            text-align: center;
            text-transform: uppercase;
            font-size: 2em;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .product {
            width: 30%;
            margin-right: 1.66%;
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }

        .product:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .product img {
            width: 100%;
            height: 300px; /* vous pouvez ajuster cette valeur en fonction de vos besoins */
            object-fit: cover;
            display: block;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }


        .product img:hover {
            transform: scale(1.05);
        }

        .product h3 {
            margin-bottom: 10px;
            color: #007bff;
            transition: color 0.3s ease;
        }

        .product p {
            margin-bottom: 10px;
        }

        .product .price {
            font-weight: bold;
            color: red;
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
            margin: 0 15px;
            transition: background-color 0.3s ease;

        }
        .product .btn1{
            margin-top: 10px;
        }

        .product .btn:hover {
            background-color: #0056b3;
        }

        .results {
            position: relative;
            top: 500px;
            left: 330px;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .product {
                width: 100%;
                margin-right: 0;
            }
        }
        .footer{

            position: fixed;
            bottom: 0;
            width: 100%;
            height: 50px;
            background-color:rgba(0,0,0,0.5);
            text-align: center;
            padding-top: 10px;
        }
        .navbar{
            min-height: 100px;
            padding-bottom: 50px;
        }
        .logo{
            position: relative;
            left: -50px;
            top: 10px;
            color: green;
            font-size: 3em;
            text-decoration: none;
        }
    </style>
</head>
<body>
   <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100 cc-navbar">
        <div class="container">
            <a class="navbar-brand fw-bolder logo" href="#">Clark Kente</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item pe-3">
                        <a class="btn btn-orders" href="recherche.php">Rechercher Article</a>
                    </li>
                    <li class="nav-item pe-3">
                        <a class="btn btn-orders" href="php/connexion.php">Login</a>
                    </li>
                    <li class="nav-item pe-3">
                        <a class="btn btn-orders" href="index.php">Decouvrir</a>
                    </li>
                    <li class="nav-item pe-3">
                        <a class="btn btn-order" href="php/index.php">Profil</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>



        <div id="results" class="results"></div>


        <div class="container containers">
            <!-- Les produits seront chargés ici par Fetch API -->
        </div>
     </div>
    


    <script src="nouv/js/bootstrap.bundle.min.js"></script>
    <!-- script pour recharger ou afficher les produits -->
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            function fetchProducts() {
                fetch('php/fetch_products.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            const container = document.querySelector('.containers');
                            container.innerHTML = ''; // Vider le conteneur
                            data.forEach(category => {
                                let categoryHtml = '<div class="category">';
                                categoryHtml += `<h2>${category.nom}</h2>`;
                                categoryHtml += '<div class="products">';
                                if (category.products.length > 0) {
                                    category.products.forEach(product => {
                                        categoryHtml += '<div class="product">';
                                                         
                                        categoryHtml += `<a href="php/${product.image}"> <img src="php/${product.image}" alt="${product.titre}"></a>`;
                                        categoryHtml += `<h3>${product.titre}</h3>`;
                                        categoryHtml += `<p>${product.description}</p>`;
                                        categoryHtml += `<p class="price">${product.prix} Frc</p>`;
                                        categoryHtml += `<a href="detail.php?id=${product.id}" class="btn">Voir Détails</a>`;
                                        categoryHtml += `<form method="post" action="acherter.php">
                                                            <input type="hidden" name="product_id" value="${product.id}">
                                                            <input type="hidden" name="utilisateur_id" value="valeur_de_utilisateur_id">


                                                            <input type="submit" class="btn btn1" value="Acheter maintenant">
                                                        </form>`;
                                        // ...
                                        categoryHtml += '</div>';
                                    });
                                } else {
                                    categoryHtml += '<p>Aucun produit trouvé pour cette catégorie.</p>';
                                }
                                categoryHtml += '</div>';
                                categoryHtml += '</div>';
                                container.innerHTML += categoryHtml;
                            });
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }

            // Appeler fetchProducts toutes les 10 secondes
            fetchProducts();
            setInterval(fetchProducts, 10000);
        });

        
    </script>

 

  


</body>
</html>
<div class="footer">
    <?php include 'php/view/footer.php'; ?>
</div>

