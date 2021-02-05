<?php require 'include/header.php'; ?>
<title>Accueil</title>
</head>
<body>
</div>

    <h1 class="text-center text-white mt-5">Page d'accueil</h1>

<?php



if (isset($_SESSION['id'])) {
    
   echo '<h2 class="text-center text-white mt-5">Bonjour ' .$_SESSION['username']. '</h2>';

?>

    <table>
        <tr>
            <td>
                <a href="deconnexion.php">Se Déconnecter</a>
            </td>
        </tr>
        <tr>
            <td>
                <a href="profil.php">Afficher le Profil</a>
            </td>
        </tr>
    </table>

<?php

}

else {

?>

    <table>
        <tr>
            <td>
                <a href="inscription.php">Inscription</a>
            </td>
        </tr>

        <tr>
            <td>
                <a href="connexion.php">Se Connecter</a>
            </td>
        </tr>
    </table>

<?php

}

?>
    
</body>
</html>