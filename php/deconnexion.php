<?php
    session_start();
    unset($_SESSION['id']);
    header("Location: connexion.php");
    session_destroy();
?>