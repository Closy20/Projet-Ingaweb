<?php
    // Traitement du formulaire de contact
    $message_sent = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contact_form'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $subject = htmlspecialchars($_POST['subject']);
        $message = htmlspecialchars($_POST['message']);

        if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
            $to = "mbaidouboutarkente908@gmail.com";
            $headers = "From: $email";

            if (mail($to, $subject, $message, $headers)) {
                $message_sent = true;
            } else {
                $error_message = "Échec de l'envoi du message.";
            }
        } else {
            $error_message = "Tous les champs sont requis.";
        }
    }

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "boutique";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Récupérer les avis des utilisateurs
    // $sql = "SELECT * FROM commentaires";
    // $sql = "SELECT * FROM commentaires ORDER BY id DESC LIMIT 3";
    $sql = "SELECT  c.id  AS commentaier_id, c.commentaire, u.nom as utilisateur_nom from commentaires c join utilisateurs u 
            on c.user_id = u.id ";
    $reviews_result = $conn->query($sql);


    // Récupération de la catégorie sélectionnée (s'il y en a une)
    $categorie_id = $_GET['categorie_id'] ?? null;
    // Si aucune catégorie n'est sélectionnée, par défaut on affiche les produits de la catégorie "Ordinateur"
    if (!$categorie_id) {
        // Remplacez l'ID "1" par l'ID de la catégorie "Ordinateur" dans votre base de données
        $categorie_id = 6;
    }

    // Récupération de toutes les catégories
    $sql_categories = "SELECT * FROM categories";
    $result_categories = $conn->query($sql_categories);
    $categories = [];

    if ($result_categories->num_rows > 0) {
        while ($row = $result_categories->fetch_assoc()) {
            $categories[$row['id']] = $row;
        }
    }

    // Récupération des produits par catégorie (si une catégorie est sélectionnée)
    $produits_par_categorie = [];
    if ($categorie_id) {
        $sql = "SELECT * FROM produits WHERE categorie_id = '$categorie_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $produits_par_categorie[$row['categorie_id']][] = $row;
            }
        }
    }

    // Fonction pour récupérer les produits par catégorie
    // Connexion à la base de données (mettre à jour avec vos informations)
    
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "boutique";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        
    // Fonction pour récupérer les catégories
    function getCategories($mysqli) {
        $result = $mysqli->query("SELECT id, nom FROM categories");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fonction pour récupérer les derniers produits par catégorie avec limite
    function getProductsByCategory($mysqli, $category_id, $limit, $offset = 0) {
        $stmt = $mysqli->prepare("SELECT * FROM produits WHERE categorie_id = ? ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param('iii', $category_id, $limit, $offset);
        $stmt->execute();
        // $sql = "SELECT * FROM produits WHERE categorie_id = ? ORDER BY id DESC LIMIT ? OFFSET ?";

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Récupérer les catégories
    $categories = getCategories($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Clark Kente</title>
    <!-- Bootstrap CSS -->
    <link href="../nouv/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="clark.css">
     <script>
        function loadMores(categoryId, offset) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '?load_mores=1&category_id=' + categoryId + '&offset=' + offset, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var produitsContainer = document.getElementById('produits-containers-' + categoryId);
                    produitsContainer.innerHTML += xhr.responseText;

                    var loadMoreButton = document.getElementById('load-mores-' + categoryId);
                    loadMoreButton.setAttribute('data-offset', parseInt(offset) + 3);
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg position-fixed w-100 navbar-dark cc-navbar">
        <div class="container">
            <a class="navbar-brand fw-bolder" href="#">Clark Kente</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item pe-4">
                        <a class="nav-link active" aria-current="page" href="#">Accueil</a>
                    </li>
                    <li class="nav-item pe-4">
                        <a class="nav-link" href="#varieter">Découvrir</a>
                    </li>
                    <li class="nav-item pe-4">
                        <a class="nav-link" href="#classique">Les Classiques</a>
                    </li>
                    <li class="nav-item pe-4">
                        <!-- <a class="btn btn-order" href="#commande">Passer la commande</a> -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="banner pt-5 d-flex justify-content-center align-items-center">
        <div class="container py-5 my-5">
            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <h1 class="text-capitalize py-3 redresse banner-desc">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Lorem ipsum
                    </h1>
                    <p>
                        <button class="btn btn-order btn-lg me-5 rounded-0 marriweather">
                        <a class="" href="#commande" style="border: none; text-decoration: none; ">Passer la commande ou 
                            Commander maintenant</a></button>
                        <!-- <button class="btn btn-outline-info btn-lg marriweather">Réserver</button> -->
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="availabel marriweather py-5">
        <div class="container">
            <div class="row">
                <div class="card mb-3 border-0 rounded-0">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="images/istockphoto-1081334280-612x612.jpg" class="img-fluid rounded-start" alt="..." />
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="card-title">Titre de la carte</h5>
                                <p class="card-text">
                                    Ceci est une carte plus large avec du texte de support en dessous comme une introduction naturelle à un contenu supplémentaire. Ce contenu est un peu plus long.
                                </p>
                                <p class="card-text">
                                    <a href="#" class="text-muted btn">Dernière mise à jour il y a 3 minutes</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2eme card -->
                <div class="card my-5 border-0 rounded-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="card-title">Titre de la carte</h5>
                                <p class="card-text">
                                    Ceci est une carte plus large avec du texte de support en dessous comme une introduction naturelle à un contenu supplémentaire. Ce contenu est un peu plus long.
                                </p>
                                <p class="card-text">
                                    <a href="#" class="text-muted btn">Dernière mise à jour il y a 3 minutes</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="images/istockphoto-1081334280-612x612.jpg" class="d-block w-100" alt="..." />
                                    </div>
                                    <div class="carousel-item">
                                        <img src="images/img1.jpg" class="d-block w-100" alt="..." />
                                    </div>
                                    <div class="carousel-item">
                                        <img src="images/img2.jpg" class="d-block w-100" alt="..." />
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Précédent</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Suivant</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3eme card -->
                <div class="card mb-3 border-0 rounded-0">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="images/img3.jpg" class="img-fluid rounded-start" alt="..." />
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="card-title">Titre de la carte</h5>
                                <p class="card-text">
                                    Ceci est une carte plus large avec du texte de support en dessous comme une introduction naturelle à un contenu supplémentaire. Ce contenu est un peu plus long.
                                </p>
                                <p class="card-text">
                                    <a href="#" class="text-muted btn">Dernière mise à jour il y a 3 minutes</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- teste debut -->
        
                <div id="produits-container">
                    <?php foreach ($categories as $categorie): ?>
                        <h2 id="categorie-<?php echo $categorie['id']; ?>" style="text-align: center;"><?php echo $categorie['nom']; ?></h2>
                        <div class="produits" id="produits-containers-<?php echo $categorie['id']; ?>">
                            <?php
                            // Récupérer les trois derniers produits de la catégorie
                            $produits = getProductsByCategory($conn, $categorie['id'], 3);
                            if (!empty($produits)): ?>
                                <?php foreach ($produits as $produit): ?>
                                    <div class="produit">
                                        <img src="produits/images/<?php echo $produit['image']; ?>" alt="<?php echo $produit['nom']; ?>">
                                        <h3><?php echo $produit['nom']; ?></h3>
                                        <p><?php echo $produit['description']; ?></p>
                                        <p><strong>Prix:</strong> <?php echo $produit['prix']; ?> €</p>
                                        <button onclick="addToCart(<?php echo $produit['id']; ?>)">Ajouter au panier</button>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Aucun produit trouvé dans cette catégorie.</p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- <button id="load-mores-<?php echo $categorie['id']; ?>" data-offset="3" onclick="loadMores(<?php echo $categorie['id']; ?>, 3)">Afficher plus</button> -->
                <?php
                if (isset($_GET['load_mores'])) {
                    $category_id = intval($_GET['category_id']);
                    $offset = intval($_GET['offset']);
                    $produits = getProductsByCategory($conn, $category_id, 3, $offset);

                    foreach ($produits as $produit) {
                        echo '<div class="produit">';
                        echo '<img src="produits/images/' . $produit['image'] . '" alt="' . $produit['nom'] . '">';
                        echo '<h3>' . $produit['nom'] . '</h3>';
                        echo '<p>' . $produit['description'] . '</p>';
                        echo '<p><strong>Prix:</strong> ' . $produit['prix'] . ' €</p>';
                        echo '<button onclick="addToCart(' . $produit['id'] . ')">Ajouter au panier</button>';
                        echo '</div>';
                    }
                    exit();
                }
                ?>

                <!-- fin  -->
            </div>
        </div>
    </section>

    <section class="cc-menu marriweather py-5" id="varieter">
        <div class="container">
            <div class="row">
                <h3 class="text-center marriweather text-light">Nos variétés</h3>
                <div class="card bg-transparent text-light text-center">
                    <div class="card-header redresse fs-4">
                        <ul class="nav nav-tabs justify-content-center card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="true" href="#varieter">Active</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="#varieter">Link</a>
                            </li>
                        
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Traitement de titre spécial</h5>
                        <p class="card-text">Avec du texte de support en dessous comme une introduction naturelle à un contenu supplémentaire.</p>
                        <a href="#varieter" class="btn btn-primary">Aller quelque part</a>
                    </div>
                </div>
            </div>
            <!-- carte images -->
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col">
                    <div class="card">
                        <img src="images/kali.jpeg" class="card-img-top" alt="..." />
                        <div class="card-body">
                            <h5 class="card-title">Titre de la carte</h5>
                            <p class="card-text">Ceci est une carte plus longue avec du texte de support en dessous comme une introduction naturelle à un contenu supplémentaire. Ce contenu est un peu plus long.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="images/kali2.jpeg" class="card-img-top" alt="..." />
                        <div class="card-body">
                            <h5 class="card-title">Titre de la carte</h5>
                            <p class="card-text">Ceci est une carte plus longue avec du texte de support en dessous comme une introduction naturelle à un contenu supplémentaire. Ce contenu est un peu plus long.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="images/kali3.jpeg" class="card-img-top" alt="..." />
                        <div class="card-body">
                            <h5 class="card-title">Titre de la carte</h5>
                            <p class="card-text">Ceci est une carte plus longue avec du texte de support en dessous comme une introduction naturelle à un contenu supplémentaire. Ce contenu est un peu plus long.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="images/kali4.jpeg" class="card-img-top" alt="..." />
                        <div class="card-body">
                            <h5 class="card-title">Titre de la carte</h5>
                            <p class="card-text">Ceci est une carte plus longue avec du texte de support en dessous comme une introduction naturelle à un contenu supplémentaire. Ce contenu est un peu plus long.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="order-now marriweather py-5" id="commande">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h3 class="text-capitalize">Passer la commande maintenant</h3>
                    <p>Obtenez votre nourriture en une seule commande</p>
                    <form id="orderForm" class="text-start">
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="fullName" placeholder="Entrez votre nom complet" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailAddress" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" id="emailAddress" placeholder="Entrez votre adresse e-mail" required>
                        </div>
                        <div class="mb-3">
                            <label for="phoneNumber" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-control" id="phoneNumber" placeholder="Entrez votre numéro de téléphone" required>
                        </div>
                        <div class="mb-3">
                            <label for="orderDetails" class="form-label">Détails de la commande</label>
                            <!-- <textarea class="form-control" id="orderDetails" rows="3" placeholder="Entrez les détails de votre commande -->
                            <textarea class="form-control" id="orderDetails" rows="3" placeholder="Entrez les détails de votre commande" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Commander</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <section class="faq py-5">
        <div class="container">
            <h2 class="text-center mb-4">Questions Fréquemment Posées</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Question 1 : Comment passer une commande ?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Pour passer une commande, cliquez sur "Passer la commande" et remplissez le formulaire avec vos informations.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Question 2 : Quels sont les modes de paiement disponibles ?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Nous acceptons les paiements par carte bancaire, PayPal et en espèces à la livraison.
                        </div>
                    </div>
                </div>
                <!-- Ajoutez plus de questions selon les besoins -->
            </div>
        </div>
    </section>
   
    <section class="user-reviews py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Avis des utilisateurs</h2>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">John Doe</h5>
                            <p class="card-text">Excellent service, je recommande vivement !</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Jane Smith</h5>
                            <p class="card-text">Très bon produits, livraison rapide.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
                
    
    <section class="contact-form py-5">
        <div class="container">
            <h2 class="text-center mb-4">Contactez-nous</h2>
            <form id="contactForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" id="name" placeholder="Votre nom" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="email" class="form-control" id="email" placeholder="Votre email" required>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="subject" placeholder="Sujet" required>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" id="message" rows="5" placeholder="Votre message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </section>

     <!-- Formulaire de contact -->  
     <section class="contact-form py-5">
        <div class="container">
            <h2 class="text-center mb-4">Contactez-nous</h2>
            <?php if ($message_sent): ?>
                <div class="alert alert-success">Message envoyé avec succès.</div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger"><?= $error_message ?></div>
            <?php endif; ?>
            <form action="index.php" method="POST" id="contactForm">
                <input type="hidden" name="contact_form" value="1">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Votre nom" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Votre email" required>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="subject" placeholder="Sujet" required>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" name="message" rows="5" placeholder="Votre message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </section>

    <!-- Section FAQ -->
    <section class="faq py-5">
        <div class="container">
            <h2 class="text-center mb-4">Questions Fréquemment Posées</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Question 1 : Comment passer une commande ?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Pour passer une commande, cliquez sur "Passer la commande" et remplissez le formulaire avec vos informations.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Question 2 : Quels sont les modes de paiement disponibles ?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Nous acceptons les paiements par carte bancaire, PayPal et en espèces à la livraison.
                        </div>
                    </div>
                </div>
                <!-- Ajoutez plus de questions selon les besoins -->
            </div>
        </div>
    </section>

    <!-- Avis des utilisateurs -->
    <section class="user-reviews py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Avis des utilisateurs</h2>
            <div class="row">
                <?php
                if ($reviews_result->num_rows > 0) {
                    while($row = $reviews_result->fetch_assoc()) {
                        echo "<div class='col-md-4 mb-3'>";
                        echo "<div class='card'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>" . htmlspecialchars($row["utilisateur_nom"]) . "</h5>";
                        echo "<p class='card-text'>" . htmlspecialchars($row["commentaire"]) . "</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "0 avis";
                }
                ?>
            </div>
        </div>
    </section>

    <footer class="footer bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Clark Kente</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam non urna vitae nisi aliquet tincidunt.</p>
                </div>
                <div class="col-md-4">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light">Accueil</a></li>
                        <li><a href="#varieter" class="text-light">Découvrir</a></li>
                        <li><a href="#classique" class="text-light">Les Classiques</a></li>
                        <li><a href="#commande" class="text-light">Passer la commande</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>Email: contact@clarkkente.com</p>
                    <p>Téléphone: +123 456 789</p>
                    <div class="d-flex">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();
            
            if (name === '' || email === '' || subject === '' || message === '') {
                alert('Tous les champs sont requis.');
                return;
            }

            // Ajouter plus de validations si nécessaire
            
            alert('Message envoyé avec succès.');
            // Vous pouvez également ajouter un code pour envoyer les données à un serveur ici
        });



    </script>

    

    <!-- Bootstrap JS -->
    <script src="../nouv/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>
