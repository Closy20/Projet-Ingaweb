<?php
    session_start();
    require_once 'includes/db.php';

    // Vérifier si l'administrateur est connecté
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin_login.php');
        exit();
    }

    // Vérifier si un identifiant de produit est spécifié
    if (!isset($_GET['id'])) {
        header('Location: produit.php');
        exit();
    }

    $product_id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM produits WHERE id = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        header('Location: produit.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $admin_id = $_POST['admin_id'];
        $categorie_id = $_POST['categorie_id'];

        // Gestion de l'image
        $image = $product['image'];
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "img/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $image = $target_file;
        }

        $stmt = $pdo->prepare('UPDATE produits SET titre = ?, description = ?, prix = ?, admin_id = ?, categorie_id = ?, image = ? WHERE id = ?');
        $stmt->execute([$titre, $description, $prix, $admin_id, $categorie_id, $image, $product_id]);

        header('Location: produit.php');
        exit();
    }

    // Récupérer toutes les catégories pour le champ de sélection
    $categories_stmt = $pdo->query('SELECT * FROM categories');
    $categories = $categories_stmt->fetchAll();

    $title = 'Modifier un produit';
    include 'view/header.php';
?>
    <link href="nouv/css/bootstrap.min.css" rel="stylesheet">

<div class="container">

    
    <h2>Modifier un produit</h2>

<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="titre">Nom du produit</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($product['titre']); ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
    </div>
    <div class="form-group">
        <label for="prix">Prix</label>
        <input type="number" class="form-control" id="prix" name="prix" value="<?php echo htmlspecialchars($product['prix']); ?>" required>
    </div>
    <div class="form-group">
        <label for="admin_id">Admin ID</label>
        <input type="number" class="form-control" id="admin_id" name="admin_id" value="<?php echo htmlspecialchars($product['admin_id']); ?>" required>
    </div>
    <div class="form-group">
        <label for="categorie_id">Catégorie</label>
        <select class="form-control" id="categorie_id" name="categorie_id" required>
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category['id']; ?>" <?php if ($category['id'] == $product['categorie_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($category['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" class="form-control" id="image" name="image">
        <?php if ($product['image']) : ?>
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Image actuelle" style="width: 100px;">
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
    <a href="produit.php" class="btn btn-secondary">Retour</a>
</form>

</div>
<?php include 'view/footer.php'; ?>
