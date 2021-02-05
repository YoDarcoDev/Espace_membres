<?php require 'include/header.php'; ?>
<title>Profil</title>
</head>
<body>
</div>

<?php

    // SI IL Y A UNE SESSION DE CONNEXION
    if (isset($_SESSION['id'])) {

?>
    
    <div id="login">
        <h3 class="text-center text-white pt-5">Profil</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <h3> <?= "Bonjour " . $_SESSION['username']; ?> </h3>
                        
                        <table>
                            <tr>
                                <td>Nom d'utilisateur :</td>
                                <td> <?= $_SESSION['username']; ?> </td>
                            </tr>

                            <tr>
                                <td>Adresse email :</td>
                                <td> <?= $_SESSION['email']; ?> </td>
                            </tr>

                            <tr>
                                <td>
                                    <button class="btn btn-warning">
                                        <a href="modif_profil.php" class="text-dark" style="text-decoration:none;">Modifier mon profil</a>
                                    </button>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container button">
        <button class="btn btn-warning"><a href="./index.php" style="text-decoration:none;" class="text-dark">Accueil</a></button>
    </div>

<?php

    }
?>

<body>
</html>