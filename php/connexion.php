<?php
session_start();

// Connexion à la base de données
include "includes/db.php";

$error = '';
$succe = '';

// Traitement de la soumission du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Requête pour récupérer l'administrateur par nom d'utilisateur
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE nom = ?');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        // Vérifier si l'administrateur existe et si le mot de passe est correct
        if ($admin && password_verify($password, $admin['password'])) {
            // $_SESSION['admin_username'] = $admin['username'];
             $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_role'] = $admin['role'];
                header('Location: index.php');
            // header('Location: index.php');
        } else {
            $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    }
}


// Traitement de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'register') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Vérifier si l'administrateur existe déjà
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE nom = ? OR email = ?');
        $stmt->execute([$username, $email]);
        $existingAdmin = $stmt->fetch();

        if ($existingAdmin) {
            $error = 'Cet utilisateur ou cette adresse e-mail est déjà utilisé.';
        } else {
            // Hashage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Traitement de l'avatar
            $avatarPath = '';
            if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'avatars/'; // Répertoire où vous souhaitez stocker les avatars
                $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
                    // Le fichier a été téléchargé avec succès
                    $avatarPath = $uploadFile;
                } else {
                    $error = 'Une erreur est survenue lors du téléchargement de l\'avatar.';
                }
            }

            // Insertion du nouvel administrateur dans la base de données
            $stmt = $pdo->prepare('INSERT INTO utilisateurs (nom, email, password, avatar) VALUES (?, ?, ?, ?)');
            $stmt->execute([$username, $email, $hashedPassword, $avatarPath]);

            $succe = 'Inscription réussie. Vous pouvez maintenant vous connecter.';
        }
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'Authentification</title>
    <link rel="stylesheet" href="../nouv/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="header.css">
    <style>
        /* Styles CSS précédents */

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            
        }

        button:hover {
            background-color: #0056b3;
        }

        .toggle-link {
            margin-top: 20px;
            text-align: center;
        }

        .toggle-link a {
            color: #007bff;
            text-decoration: none;
        }

        .toggle-link a:hover {
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }
        h2{
            text-align: center;
            font-size: 2.5em;
            color: #007bff;
        }
        form{
            /* justify-content: center; */
            text-align: center;
        }
    
    </style>
</head>
<body>
<a href="../index.php" style="color: #007bff; text-decoration: none; border: 2px solid #007bff; position: relative; left: 3%; top: 20px; padding: 5px 10px; text-transform:uppercase;border-radius:10px;">acceuil</a>

    <div class="container">
        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($succe): ?>
            <p style="color: green;"><?php echo $succe; ?></p>
        <?php endif; ?>
        <div id="loginForm">
            <h2>Connexion Administrateur</h2>
            <form method="post">
                <input type="hidden" name="action" value="login">
              
                <br><br>
                <div class="input-group input-group-lg mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Nom d'utilisateur</span>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div><br>
                <div class="input-group input-group-lg mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Mot de passe</span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="connexion">Se connecter</button>
            </form>
            <div class="toggle-link">
                <a href="#" id="registerToggleLink">Pas encore de compte? Inscription</a>
            </div>
        </div>
        <div id="registerForm" class="hidden">
                
           <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($succe): ?>
                <p style="color: green;"><?php echo $succe; ?></p>
            <?php endif; ?>
            <h2>Inscription Administrateur</h2>
            <form method="post" enctype="multipart/form-data">
                <br>
                <input type="hidden" name="action" value="register">

                <br>
                <div class="input-group input-group-lg mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Nom d'utilisateur</span>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div><br>
                <div class="input-group input-group-lg mb-3">
                    
                    <span class="input-group-text" id="inputGroup-sizing-sm">Email</span>
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="email" class="form-control" placeholder="Votre email" id="email" name="email" required>
                </div> <br>
              
                <div class="input-group input-group-lg mb-3">
                    <span class="input-group-text" id="basic-addon1">Avatar</span>
                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                </div><br>
                <div class="input-group input-group-lg mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Mot de passe</span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div><br>
                <button type="submit" name="inscription">S'inscrire</button>

            </form>

            <div class="toggle-link">
                <a href="#" id="loginToggleLink">Déjà un compte? Se connecter</a>
            </div>
        </>
    </div>

<!-- <script> -->
 <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const loginToggleLink = document.getElementById('loginToggleLink');
        const registerToggleLink = document.getElementById('registerToggleLink');

        loginToggleLink.addEventListener('click', function(event) {
            event.preventDefault();
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
        });

        registerToggleLink.addEventListener('click', function(event) {
            event.preventDefault();
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
        });
    });
</script>

<!-- </script> -->

</body>
</html>
