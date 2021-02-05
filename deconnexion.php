<?php

session_start();

// SI CONNECTE
if (isset($_SESSION['id'])) {

    // DETRUIRE LES VARIABLES DE LA SESSION
    session_unset();
    
    // DETRUIRE LA SESSION ELLE MEME
    session_destroy();
    
    header('Location: index.php');
}

// SI PAS CONNECTE
else {
    echo "Vous n'êtes pas connecté";
}