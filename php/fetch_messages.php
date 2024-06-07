<?php
session_start();
require_once 'includes/db.php';

// Vérification de la session
if (!isset($_SESSION['admin_id'])) {
    exit(json_encode(['error' => 'Session non valide']));
}

try {
    // Récupérer les messages de l'utilisateur connecté
    $stmt = $pdo->prepare('SELECT m.id, m.contenu, m.created_at, 
                                CASE WHEN m.sender_id = :admin_id THEN :admin_nom ELSE u_sender.nom END AS sender_nom,
                                CASE WHEN m.receiver_id = :admin_id THEN :admin_nom ELSE u_receiver.nom END AS receiver_nom
                        FROM messages m 
                        LEFT JOIN utilisateurs u_sender ON m.sender_id = u_sender.id 
                        LEFT JOIN utilisateurs u_receiver ON m.receiver_id = u_receiver.id 
                        WHERE (m.sender_id = :admin_id OR m.receiver_id = :admin_id)
                        ORDER BY m.created_at DESC');
    $stmt->execute([
        'admin_id' => $_SESSION['admin_id'], 
        'admin_nom' => isset($_SESSION['admin_nom']) ? $_SESSION['admin_nom'] : '<label style="color: orange; font-weight: bold;">Moi</label>'
    ]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les messages au format JSON
    echo json_encode($messages);
} catch (PDOException $e) {
    exit(json_encode(['error' => 'Erreur lors de la récupération des messages : ' . $e->getMessage()]));
}
?>

<?php
// require_once 'includes/db.php';
// session_start();

// if (!isset($_SESSION['admin_id'])) {
//     echo json_encode(['error' => 'Utilisateur non connecté']);
//     exit();
// }

// $admin_id = $_SESSION['admin_id'];

// try {
//     $stmt = $pdo->prepare('SELECT m.id, m.contenu, m.created_at, m.sender_id, s.nom as sender_nom, m.receiver_id, r.nom as receiver_nom 
//                            FROM messages m
//                            JOIN utilisateurs s ON m.sender_id = s.id
//                            JOIN utilisateurs r ON m.receiver_id = r.id
//                            WHERE m.sender_id = ? OR m.receiver_id = ?
//                            ORDER BY m.created_at ASC');
//     $stmt->execute([$admin_id, $admin_id]);
//     $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
//     $groupedMessages = [];
//     foreach ($messages as $message) {
//         $otherUserId = $message['sender_id'] == $admin_id ? $message['receiver_id'] : $message['sender_id'];
//         if (!isset($groupedMessages[$otherUserId])) {
//             $groupedMessages[$otherUserId] = [
//                 'name' => $message['sender_id'] == $admin_id ? $message['receiver_nom'] : $message['sender_nom'],
//                 'messages' => []
//             ];
//         }
//         $groupedMessages[$otherUserId]['messages'][] = $message;
//     }

//     echo json_encode($groupedMessages);

// } catch (PDOException $e) {
//     echo json_encode(['error' => 'Erreur lors de la récupération des messages : ' . $e->getMessage()]);
// }
?>

<?php
// session_start();
// require_once 'includes/db.php';

// if (!isset($_SESSION['admin_id'])) {
//     header('Location: connexion.php');
//     exit();
// }

// try {
//     // Récupérer la liste des utilisateurs (en excluant l'utilisateur connecté)
//     $stmt = $pdo->prepare('SELECT id, nom FROM utilisateurs WHERE id != ?');
//     $stmt->execute([$_SESSION['admin_id']]);
//     $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Initialiser un tableau pour stocker les messages par utilisateur
//     $messagesData = array();

//     // Récupérer tous les messages et regrouper par utilisateur
//     $stmt = $pdo->prepare('SELECT m.*, u1.nom AS sender_nom, u2.nom AS receiver_nom FROM messages m 
//                             INNER JOIN utilisateurs u1 ON m.sender_id = u1.id 
//                             INNER JOIN utilisateurs u2 ON m.receiver_id = u2.id 
//                             WHERE (m.sender_id = ? OR m.receiver_id = ?) AND m.receiver_id != ? ORDER BY m.created_at DESC');
//     $stmt->execute([$_SESSION['admin_id'], $_SESSION['admin_id'], $_SESSION['admin_id']]);
//     $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // Regrouper les messages par utilisateur
//     foreach ($messages as $message) {
//         $senderId = $message['sender_id'];
//         $receiverId = $message['receiver_id'];

//         // Stocker les messages dans un tableau associatif indexé par l'ID de l'utilisateur
//         if (!isset($messagesData[$senderId])) {
//             $messagesData[$senderId] = array(
//                 'name' => $message['sender_nom'],
//                 'messages' => array()
//             );
//         }
//         if (!isset($messagesData[$receiverId])) {
//             $messagesData[$receiverId] = array(
//                 'name' => $message['receiver_nom'],
//                 'messages' => array()
//             );
//         }

//         // Ajouter le message dans le tableau associé à l'expéditeur et au destinataire
//         array_push($messagesData[$senderId]['messages'], $message);
//         array_push($messagesData[$receiverId]['messages'], $message);
//     }

//     // Convertir le tableau associatif en format JSON et l'envoyer
//     header('Content-Type: application/json');
//     echo json_encode($messagesData);
// } catch (PDOException $e) {
//     // En cas d'erreur lors de la récupération des données
//     echo json_encode(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()]);
// }
?>


