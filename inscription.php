<?php require 'include/header.php'; ?>
<title>Inscription</title>


<?php

// SI LE USER A CLIQUÉ SUR INSCRIPTION
if (isset($_POST['inscription'])) {

    // SI LE CHAMPS USERNAME EST VIDE OU SI LE REGEX NE CORRESPOND PAS
    if(empty($_POST['username']) || !preg_match('/[a-zA-Z0-9]+/', $_POST['username'])) {
        
        $message = "Le username doit être une chaine de caractères";
    }

    elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $message = "Veuillez entrer une adresse email valide";
    }

    elseif (empty($_POST['password'])) {

        $message = "Veuillez saisir un mot de passe";
    }

    elseif (empty($_POST['password2']) || $_POST['password2'] != $_POST['password']) {

        $message = "Veuillez rentrer un mot de passe valide et identique à celui saisi précédemment";
    }

    else {

        // CONNEXION BDD
        require_once 'include/start_bdd.php'; 


        // VERIFIER SI USERNAME EXISTE EN BDD
        $req_username = $bdd->prepare("SELECT * FROM membres.table_membres WHERE username = :username");
        $req_username->bindvalue(':username', $_POST['username']);
        $req_username->execute();
        $result_username = $req_username->fetch();


        // VERIFIER SI EMAIL EXISTE EN BDD
        $req_email = $bdd->prepare("SELECT * FROM membres.table_membres WHERE email = :email");
        $req_email->bindvalue(':email', $_POST['email']);
        $req_email->execute();
        $result_email = $req_email->fetch();


        // TESTER USERNAME
        if ($result_username) {
            $message = "Le username que vous avez choisi existe déjà, veuillez en saisir un nouveau";
        }

        // TESTER EMAIL
        elseif ($result_email) {
            $message = "Un compte existe déjà avec cette adresse email";
        }

        // SINON PASSER AU TRAITEMENT ENREGISTREMENT EN BDD
        else {


            // TOKEN ENVOYER VIA L'URL
            function token_random_string($leng=20) {
    
                $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $token = '';

                for ($i = 0; $i < $leng; $i++) {

                    $token .= $str[rand(0, strlen($str)-1)];
                }
                    return $token;
            }

            $token = token_random_string(20);
            // echo $token;



            // CRYPTER LE MDP
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


            // REQUETE SQL PREPAREE
            $requete = $bdd->prepare("INSERT INTO membres.table_membres(username, email, password, token) VALUES(:username, :email, :password, :token)");


            // LIAISON DES PARAMETRES NOMMÉS
            $requete->bindvalue(':username', $_POST['username']);
            $requete->bindvalue(':email', $_POST['email']);
            $requete->bindvalue(':password', $password);
            $requete->bindvalue(':token', $token);


            // EXECUTER LA REQUETE
            $requete->execute();


            // CODE POUR L'ENVOI DE MAIL PHPMAILER
            require 'PHPMailer/PHPMailerAutoload.php';
            require 'PHPMailer/class.phpmailer.php';

            $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yoann.bettinelli@gmail.com';
            $mail->Password = 'darco180587';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yoann.bettinelli@gmail.com', 'Yoann');
            $mail->addAddress($_POST['email']);

            $mail-> isHTML(true);

            $mail->Subject = "Confirmation adresse email";
            $mail->Body = 'Afin de valider votre adresse email, merci de cliquer sur le lien suivant pour confirmer votre email :

            
            <a href="http://localhost:8080/Espace_membres/verification.php?email='.$_POST['email'].'&token='.$token.'">Confirmer votre adresse email</a>';


            if (!$mail->send()) {

                $message =  "Le message n'a pas pu être envoyé.";
                echo "Mailer Erreur :" . $mail -> ErrorInfo;
            }

            else {
                $message = "Un email de confirmation vous a été adressé, merci de confirmer votre adresse email";
            }

        }
    }
}


?>


<body>
    

    <div id="login">
        <h3 class="text-center text-white pt-5">Inscription</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        
                    
                        <div class="container text-center" style="background-color: #FB6969;">

                            <?php if (isset($message)) echo $message ?>

                        </div>


                        <form id="login-form" class="form" action="" method="post">
                        
                            <div class="form-group">
                                <label for="username">Username:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="email">Adresse Email:</label><br>
                                <input type="text" name="email" id="email" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="password">Mot de passe:</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="password2">Confirmation Mot de passe:</label><br>
                                <input type="password" name="password2" id="password2" class="form-control">
                            </div>

                            <div class="form-group">
                                <input type="submit" name="inscription" class="btn btn-info btn-md" value="S'inscrire">
                                <a href="connexion.php" class="btn btn-info btn-md">Se connecter</a>
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