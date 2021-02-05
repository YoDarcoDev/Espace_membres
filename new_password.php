<?php require 'include/header.php'; ?>
<title>Réinitialisation du mot de passe</title>
</head>
<body>
    

    

</head>
<body>

<?php

if ($_GET) {

    if (isset($_GET['email'])) {
        $email = $_GET['email'];
    }

    if (isset($_GET['token'])) {
        $token = $_GET['token'];
    }


    if (!empty($email) && !empty($token)) {

        // ON INTERROGE RECUP_PASSWORD POUR SAVOIR SI LES PARAMETRES ONT BIEN ETE RECUPERÉS PAR L'URL (VERIFIER QUE LE CLIENT A BIEN RECU LE TOKEN EN PASSANT PAR LE LIEN DE REINITIALISATION DE MDP)

        require_once('include/start_bdd.php');

        $requete = $bdd->prepare("SELECT * FROM membres.recup_password WHERE email=:email AND token=:token");
        
        $requete->bindvalue(':email', $email);
        $requete->bindvalue(':token', $token);

        $requete->execute();

        $nombre = $requete->rowCount();


        // SI IL EXISTE AUCUN USER DANS LA TABLE RECUP_PASSWORD AYANT L'EMAIL ET LE TOKEN PASSÉ EN PARAMÈTRE PAR LE GET
        if ($nombre != 1) {

            header('location:connexion.php');
        }

        else {

            // SI LE USER A VALIDE SON EMAIL
            if (isset($_POST['new_password'])) {


                // SI LE CHAMPS PASSWORD EST VIDE OU SI LE PASSWORD EST DIFFERENT DU PASSWORD2
                if (empty($_POST['password']) || $_POST['password'] != $_POST['password2']) {

                    $message = "Veuillez saisir un mot de passe identique dans les deux champs";
                }

                else {

                    // HACHAGE DE MOT DE PASSE

                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $requete = $bdd->prepare("UPDATE membres.table_membres SET password=:password WHERE email=:email");

                    $requete->bindvalue(':email', $email);
                    $requete->bindvalue(':password', $password);

                    $result = $requete->execute();


                    if ($result) {

                        echo "<script type='text/javascript'>
                                alert('Votre mot de passe a bien été réinitialisé');
                                document.location.href = 'connexion.php'
                            </script>";
                    }

                    else {

                        $message = "Votre mot de passe n'a pas été réinitialisé";
                        header('Location:connexion.php');
                    }
                }
            }
        }
    }
}

else {
    header("Location:inscription.php");
}


?>


<div id="login">
        <h3 class="text-center text-white pt-5">Réinitialiser votre mot de passe</h3>
        <h6 class="text-center text-white pt-5">Entrez un nouveau mot de passe</h6>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        

                        <div class="container text-center" style="background-color: #FB6969;">

                            <?php if (isset($message)) echo $message ?>

                        </div>
                        

                        <form id="login-form" class="form" action="" method="post">

                            <div class="form-group">
                                <label for="password">Nouveau mot de passe :</label><br>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Saisissez votre nouveau mot de passe"> 
                            </div>

                            <div class="form-group">
                                <label for="password2">Confirmer le mot de passe :</label><br>
                                <input type="password" name="password2" id="password2" class="form-control" placeholder="Confirmer votre mot de passe"> 
                            </div>

                            <div class="form-group">
                                <input type="submit" name="new_password" class="btn btn-info btn-md" value="Valider">
                            </div>

    
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container button">
        <button class="btn btn-warning"><a href="index.php">Accueil</a></button>
    </div>


<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>

