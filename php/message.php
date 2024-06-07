<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: connexion.php');
    exit();
}

// Récupérer la liste des utilisateurs (en excluant l'utilisateur connecté)
try {
    $stmt = $pdo->prepare('SELECT id, nom FROM utilisateurs WHERE id != ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erreur lors de la récupération des utilisateurs : " . $e->getMessage() . "</div>";
}

// Vérification si le formulaire de message est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['receiver_id']) && isset($_POST['message_content'])) {
    $sender_id = $_SESSION['admin_id']; // L'ID de l'utilisateur connecté est l'expéditeur
    $receiver_id = $_POST['receiver_id'];
    $message_content = $_POST['message_content'];

    // Vérification pour éviter les doublons
    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM messages WHERE sender_id = ? AND receiver_id = ? AND contenu = ?');
        $stmt->execute([$sender_id, $receiver_id, $message_content]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // Insertion du message dans la base de données
            $stmt = $pdo->prepare('INSERT INTO messages (sender_id, receiver_id, contenu, created_at) VALUES (?, ?, ?, NOW())');
            $stmt->execute([$sender_id, $receiver_id, $message_content]);
            echo "<div class='alert alert-success'>Message envoyé avec succès.</div>";
            header("Location: " . $_SERVER['PHP_SELF']);
        } else {
            echo "<div class='alert alert-warning'>Message déjà envoyé.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur lors de l'envoi du message : " . $e->getMessage() . "</div>";
    }
}

// Gérer la suppression du message
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $message_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare('DELETE FROM messages WHERE id = ? AND (sender_id = ? OR receiver_id = ?)');
        $stmt->execute([$message_id, $_SESSION['admin_id'], $_SESSION['admin_id']]);
        echo "<div class='alert alert-success'>Message supprimé avec succès.</div>";
        header("Location: " . $_SERVER['PHP_SELF']);

    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur lors de la suppression du message : " . $e->getMessage() . "</div>";
    }
}
include 'view/header.php';
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boîte de réception</title>
    <!-- Inclure Bootstrap CSS -->
    <link href="nouv/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
    }
    .container {
        max-width: 100%;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 50px;
        display: flex;
        flex-direction: column;
    }
    .messages{
        display: flex;
        flex-wrap: wrap;
        text-align: left;
     
    }

    .navbar-brand {
        color: #fff;
        font-size: 1.8em;
    }
    .messages-container {
        margin-bottom: 20px;
        text-align: center;
    }

    .form {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f8f8f8;
        border-radius: 4px;
        box-shadow: 2px 2px 5px red;
        text-align: center;
    }

    .form h2 {
        margin-bottom: 20px;
        color: #333;
    }

    .form div {
        margin-bottom: 20px;
    }

    .form label {
        display: block;
        margin-bottom: 10px;
        color: #555;
    }

    .form select, .form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
        color: #333;
    }

    .form button {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background-color: #333;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form button:hover {
        background-color: #555;
    }
   .message {
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 15px 20px;
        margin: 5px;
        border-radius: 4px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);

        border: 2px solid black;
        width: 200px; /* Définissez la largeur que vous souhaitez */
        height: 300px; /* Définissez la hauteur que vous souhaitez */
        overflow: auto; /* Permet de faire défiler le contenu si nécessaire */
        word-wrap: break-word; /* Permet de casser les mots longs pour éviter le débordement */
    }


    /* Media Queries */
    @media screen and (min-width: 768px) {
        .container {
            flex-direction: row;
            justify-content: space-between;
        }
        .messages-container {
            flex: 1;
            margin-right: 20px;
            text-align: center;
        }
        .form {
            width: 600px;
        }
    }
</style>

</head>
<body>

<div class="container">
    <div class="messages-container">
        <h2>Boîte de réception</h2>
        
        <!-- Section pour afficher les messages -->
        <div class="messages" id="messages"></div>
    </div>
    
    <!-- Formulaire pour envoyer un message -->
    <form method="post" class="form">
        <div >
        <h2>Boîte d'envoie </h2>
            <label for="receiver_id">Destinataire :</label>
            <select name="receiver_id" id="receiver_id" required>
                <!-- Boucle pour afficher la liste des utilisateurs -->
                <?php foreach ($users as $user) : ?>
                    <option value="<?php echo htmlspecialchars($user['id']); ?>"><?php echo htmlspecialchars($user['nom']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="message_content">Message :</label>
            <textarea name="message_content" id="message_content" rows="5" required></textarea>
        </div>
        <button type="submit">Envoyer</button>
    </form>
    
    <!-- Affichage des messages d'erreur/succès -->
    <?php if (isset($success_message)) : ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($warning_message)) : ?>
        <div class="alert alert-warning"><?php echo $warning_message; ?></div>
    <?php endif; ?>
</div>

<!-- Inclure Bootstrap JS -->
<script src="../nouv/js/bootstrap.bundle.min.js"></script>
<script>
    // Fonction pour récupérer et afficher les messages
    function fetchMessages() {
        fetch('fetch_messages.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    const messagesContainer = document.getElementById('messages');
                    messagesContainer.innerHTML = ''; // Vider le conteneur

                    // Boucler à travers les messages et les afficher
                    data.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message');
                        messageDiv.innerHTML = `
                            <p><strong>De :</strong> ${message.sender_nom}</p>
                            <p><strong>À :</strong> ${message.receiver_nom}</p>
                            <p><strong>Message :</strong> ${message.contenu}</p>
                            <p><strong>Date :</strong> ${message.created_at}</p>
                            <form class="delete-form" method="get" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                <input type="hidden" name="delete" value="${message.id}">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        `;
                        messagesContainer.appendChild(messageDiv);
                    });
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Appeler fetchMessages toutes les 10 secondes
    fetchMessages();
    setInterval(fetchMessages, 10000);
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>
