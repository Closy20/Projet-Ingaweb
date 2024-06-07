<?php
    session_start();
    require_once 'includes/db.php';

    if (!isset($_SESSION['admin_id'])) {
        header('Location: connexion.php');
        exit();
    }

    // Récupérer les produits ajoutés par l'administrateur connecté
    $stmt = $pdo->prepare('SELECT * FROM produits WHERE admin_id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $products = $stmt->fetchAll();
    $title = 'Gestion des Produits';
    include 'view/header.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['delete_product'])) {
            // Suppression du produit
            $product_id = $_POST['product_id'];
            try {
                // Supprimer le produit
                $stmt = $pdo->prepare('DELETE FROM produits WHERE id = ?');
                $stmt->execute([$product_id]);

                echo "<div class='alert alert-success'>Produit supprimé avec succès.</div>";
                
                // Redirection pour actualiser la page
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Erreur lors de la suppression du produit : " . $e->getMessage() . "</div>";
            }
        } else {
            // Ajout d'un produit
            $name = $_POST['nom'];
            $description = $_POST['description'];
            $categorie = $_POST['categorie'];
            $price = $_POST['price'];
            $image = $_FILES['image']['name'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "img/";
                $targetFile = $targetDir . basename($_FILES['image']['name']);

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    try {
                        // Vérifier les doublons
                        $stmt = $pdo->prepare('SELECT COUNT(*) FROM produits WHERE titre = ?');
                        $stmt->execute([$name]);
                        $product_exists = $stmt->fetchColumn();

                        if ($product_exists) {
                            echo "<div class='alert alert-warning'>Un produit avec ce nom existe déjà.</div>";
                        } else {
                            // Vérifier si la catégorie existe déjà
                            $stmtC = $pdo->prepare('SELECT id FROM categories WHERE nom = ?');
                            $stmtC->execute([$categorie]);
                            $categorie_id = $stmtC->fetchColumn();

                            // Si la catégorie n'existe pas, l'ajouter
                            if (!$categorie_id) {
                                $stmtC = $pdo->prepare('INSERT INTO categories (nom) VALUES (?)');
                                $stmtC->execute([$categorie]);
                                $categorie_id = $pdo->lastInsertId();
                            }

                            // Ajouter le produit avec l'ID de l'administrateur
                            $stmt = $pdo->prepare("INSERT INTO produits (titre, acheteur, categorie_id, image, description, prix, admin_id) VALUES (?, '', ?, ?, ?, ?, ?)");
                            if ($stmt->execute([$name, $categorie_id, $targetFile, $description, $price, $_SESSION['admin_id']])) {
                                echo "<div class='alert alert-success'>Produit ajouté avec succès.</div>";

                                // Redirection pour actualiser la page
                                header("Location: " . $_SERVER['PHP_SELF']);
                                exit();
                            } else {
                                echo "<div class='alert alert-danger'>Échec de l'ajout du produit.</div>";
                            }
                        }
                    } catch (PDOException $e) {
                        echo "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Échec du téléchargement de l'image.</div>";
                }
            } else {
                echo "<div class='alert alert-warning'>L'image est requise.</div>";
            }
        }
    }
?>
<!-- Style and HTML code for the product management page -->
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #343a40;
    }

    .contenue {
        display: flex;
        flex-direction: column;
        padding: 20px;
    }

    .navbar-brand {
        color: #fff;
        font-size: 3em;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th, .table td {
        border: 1px solid #dee2e6;
        padding: 18px;
        text-align: left;
    }

    .table th {
        background-color: #343a40;
        color: #ffffff;
    }

    .table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .btn {
        display: inline-block;
        padding: 8px 12px;
        margin: 4px 0;
        font-size: 14px;
        color: #ffffff;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #007bff;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    .btn:hover {
        opacity: 0.8;
    }

    .nouveau {
        width: 100%;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .alert {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 4px;
        color: #ffffff;
    }

    .alert-success {
        background-color: #28a745;
    }

    .alert-danger {
        background-color: #dc3545;
    }

    .alert-warning {
        background-color: #ffc107;
        color: #212529;
    }

    /* Media Queries */
    /* @media screen and (min-width: 900px) {
        .contenue {
            flex-direction: row;
            justify-content: space-between;
        }

        .table {
            width: 60%;
        }

        .nouveau {
            width: 35%;
        }
    } */
    @media screen and (max-width: 500px) {
        .contenue {
            flex-direction: column;
        }

        .table, .nouveau {
            width: 100%;
        }

        .navbar-brand {
            font-size: 1.5em;
        }
    }

</style>

<div class="contenue">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
                <th>Actions</th>
            </tr>
        </thead>
        <h2 style="margin-bottom: 20px;">Gestion des Produits</h2>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['titre']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><?php echo htmlspecialchars($product['prix']); ?></td>
                    <td>
                        <a href="vue.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Modifier</a>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="nouveau">
        <h2>Ajouter un produit</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom de produit</label>
                <input type="text" class="form-control" id="name" name="nom" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="categorie">Catégorie</label>
                <input type="text" class="form-control" id="categorie" name="categorie" required>
            </div>
            <div class="form-group">
                <label for="price">Prix</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="index.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#update-product-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'update_product.php',
            data: $(this).serialize(),
            success: function(response) {
                alert('Produit mis à jour avec succès.');
                location.reload();
            },
            error: function(response) {
                alert('Erreur lors de la mise à jour du produit.');
            }
        });
    });
});
</script>

<?php include 'view/footer.php'; ?>
