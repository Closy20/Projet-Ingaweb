<?php
include 'view/header.php';
session_start();

// Connexion à la base de données
include "includes/db.php";
if (!isset($_SESSION['admin_id'])) {
    header('Location: connexion.php');
    exit();
}

$error = '';
$succe = '';

// Récupérer les informations de l'utilisateur connecté
$userInfo = [];
if (isset($_SESSION['admin_id'])) {
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <title>Informations de l'utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            width: 100%;
            height: 100vh;
        }

        .containers{
            position: relative;
            padding:30px 20px;
            border-top: 5px solid white;
            height: 40vh;
            width: 100%;
            background: #333;
            color: #fff;
        }

        .containers div{
            display: flex;
            flex-wrap: wrap;
        }

        .navbar {
            background-color: #333;
        }

        .navbar-brand {
            color: #fff;
            font-size: 1.8em;
        }

        .nav-link {
            color: #fff;
            margin-right: 15px;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #ccc;
        }

        .navbar-toggler {
            border-color: #fff;
        }
        .user-info{
            font-size: 2em;
        }

        .user-info img{
            position: absolute;
            right: 10px;
            top: 25%;
            width: 150px;
            height: 150px;
        }
        strong{
            text-transform: uppercase;
            /* font-size: 1.3em; */
        }
        h2{
            font-size: 3em;
        }

        @media (max-width: 992px) {
            .user-info img{
                /* position: absolute; */
                right: 10px;
                top: 30%;
                width: 150px;
                height: 150px;
            }
            .navbar-brand {
                font-size: 1.5rem;
            }

            .user-info img{
                max-width: 50%;
            }
            .user-info{
                font-size:1.3em;
            }

            .navbar-nav {
                margin-top: 15px;
            }

            .nav-link {
                padding: 10px;
                border-bottom: 1px solid #444;
            }

            .nav-link:last-child {
                border-bottom: none;
            }
            h2{
                font-size: 2em;
            }
        }
     
        footer {
            padding-top: 15px;
            text-align: center;
            height: 50px;
            width: 100%;
            background: #444;
            position: absolute;
            bottom: 1px;
        }

    </style>

</head>
<body>

<div class="containers">
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if ($succe): ?>
        <p style="color: green;"><?php echo $succe; ?></p>
    <?php endif; ?>
    <?php if (!empty($userInfo)): ?>
        <h2 style="text-align: center; margin-bottom: 30px; ">Tableau de Bord de : </strong> <?php echo $userInfo['nom']; ?> </h2>
        <div class="user-info">
            <div>
                <div class="dv">

                    <p><strong>Nom d'utilisateur :</strong> <?php echo $userInfo['nom']; ?><br>
                    <strong>Email :</strong> <?php echo $userInfo['email']; ?></p>
                </div>
                <?php if (!empty($userInfo['avatar'])): ?>
                    <img src="<?php echo $userInfo['avatar']; ?>" alt="Avatar de l'utilisateur" class="rounded-circle">
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<h1 style="position: relative; top: 50px;" >bonjour</h1>

<?php include 'view/footer.php'; ?>
 <script src="../nouv/js/bootstrap.bundle.min.js"></script>

</body>
</html>
