<?php require 'include/header.php';?>
<title>Réinitialisation</title>
</head>
<body>

<?php

// SI LE USER A CLIQUÉ SUR LE BOUTON REINITIALISER
if (isset($_POST['password_forget'])) {


    // FONCTION GENERER TOKEN ENVOYER VIA L'URL
    function token_random_string($leng=20) {

        $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $token = '';

        for ($i = 0; $i < $leng; $i++) {

            $token .= $str[rand(0, strlen($str)-1)];
        }
            return $token;
    }


    // SI LE CHAMPS EMAIL EST VIDE OU SI L'EMAIL NE CONTIENT PAS DE @, N'EST PAS VALIDE
    if (empty($_POST['email']) || !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL )) {

        $message = "Rentrer une adresse email valide";

    }

    else {

        require "include/start_bdd.php";

        $requete = $bdd->prepare('SELECT * FROM membres.table_membres WHERE email=:email');
        $requete->bindvalue(':email', $_POST['email']);
        $requete->execute();

        // TABLEAU RESULT
        $result = $requete->fetch();
        
        // CONNAITRE LE NOMBRE DE LIGNE RETOURNÉE
        $nombre = $requete->rowCount();


        // IL DOIT Y AVOIR UN USER AVEC UNE ADRESSE UNIQUE
        if ($nombre != 1) {

            $message = "L'adresse email saisie ne correspond à aucun utilisateur, veuillez saisir une adresse valide";

        }

        else {

            // MEMBRE NON CONFIRMÉ
            if ($result['validation'] != 1) {

                $token = token_random_string(20);


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
                    $message = "Votre adresse email n'est pas encore confirmée. Un nouvel email de confirmation vous a été adressé, merci de valider votre compte avant de réinitialiser votre mot de passe";
                }

            }

            else {

                // GENERER TOKEN ALEATOIRE
                $token = token_random_string(20);

                // INTERROGER BDD VOIR SI IL EXISTE UN USER AVEC L'ADRESSE EMAIL SAISIE
                $requete_recup_password = $bdd->prepare("SELECT * FROM membres.recup_password WHERE email=:email");
                $requete_recup_password->bindvalue(':email', $_POST['email']);
                $requete_recup_password->execute();

                // NOMBRE DE LIGNE RETOURNÉE PAR LA REQUETE
                $nombre1 = $requete_recup_password->rowCount();


                // SI IL N'EXISTE AUCUN USER CORRESPONDANT A L'EMAIL DANS LA TABLE RECUP_PASSWORD (INSERTION)
                if ($nombre1 == 0) {

                    $requete2 = $bdd->prepare("INSERT INTO membres.recup_password(email,token) VALUES(:email, :token)");

                    $requete2->bindvalue(':email', $_POST['email']);
                    $requete2->bindvalue(':token', $token);

                    $requete2->execute();
                }

                // IL EXISTE DEJA UN MEMBRE AVEC CETTE ADRESSE EMAIL (DEJA REINITIALISE MDP) (MAJ)
                else {

                    $requete3 = $bdd->prepare("UPDATE membres.recup_password SET token=:token WHERE email=:email");

                    $requete3->bindvalue(':token', $token);
                    $requete3->bindvalue(':email', $_POST['email']);

                    $requete3->execute();

                }

            
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

                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    // $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;

                    $mail->setFrom('yoann.bettinelli@gmail.com', 'Yoann');
                    $mail->addAddress('yoann1805@gmail.com');

                    $mail-> isHTML(true);

                    $mail->Subject = utf8_decode("Réinitialisation du mot de passe");
                    $mail->Body = utf8_decode('Afin de réinitialiser votre adresse email, merci de cliquer sur le lien suivant et suivre les instructions pour modifier votre mot de passe 

                    <a href="http://localhost:8080/Espace_membres/new_password.php?email='.$_POST['email'].'&token='.$token.'">Réinitialiser votre mot de passe</a>');

                    if (!$mail->send()) {

                        $message = "Le message n'a pas pu être envoyé.";
                        echo "Mailer Erreur :" . $mail -> ErrorInfo;
                    }

                    else {


                        $message1 = "Nous vous avons envoyé par courriel les instructions pour réinitialiser votre mot de passe";
                    }

            }
                
        }

    }
}


?>











    <div id="login">
        <h3 class="text-center text-white pt-5">Réinitialiser votre mot de passe</h3>
        <h6 class="text-center text-white pt-5">Merci d'entrer votre adresse email ci-dessous, nous vous enverrons un email afin de réinitialiser votre mot de passe</h6>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        

                        <div class="container text-center" style="background-color: #FB6969;">

                            <?php if (isset($message)) echo $message ?>

                        </div>


                        <div class="container text-center" style="background-color: #95D588;">

                            <?php if (isset($message1)) echo $message1 ?>

                        </div>


                        <form id="login-form" class="form" action="" method="post">

                            <div class="form-group">
                                <label for="email">Adresse Email :</label><br>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Saisissez votre adresse email"> 
                            </div>

                            <div class="form-group">
                                <input type="submit" name="password_forget" class="btn btn-info btn-md" value="Réinitialiser votre mot de passe">
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
