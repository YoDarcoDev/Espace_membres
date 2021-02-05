<?php require 'include/header.php';?>

<?php
   
    // SI LE USER CLIQUE SUR CONNEXION
    if (isset($_POST['connexion'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];
    

        // CONNEXION BDD
        require_once 'include/start_bdd.php';

        $requete = $bdd->prepare("SELECT * FROM membres.table_membres WHERE email=:email");
        $requete->execute(array('email' => $email));

        $result = $requete->fetch();
        

        if (!$result) {

            $message = "Veuillez saisir une adresse email valide";
        }

        elseif ($result['validation'] == 0) {

            
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



            // SI VALIDATION != 1 RENVOI DU MAIL POUR CONFIRMER
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
                $message = "Votre adresse email n'est pas encore confirmée. Veuillez vous rendre dans votre boîte mail afin de confirmer votre adresse email, et pouvoir accéder au site";
            }
        } 

        // SI VALIDATION = 1
        else {

            $passwordIsOk = password_verify($password, $result['password']);

            if ($passwordIsOk) {

                session_start();

                $_SESSION['id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['email'] = $email;


                // CASE SE SOUVENIR COCHEE
                if (isset($_POST['sesouvenir'])) {

                    setcookie('email', $_POST['email']);
                    setcookie('password', $_POST['password']);
                }

                else {

                    if (isset($_COOKIE['email'])) {

                        setcookie($_COOKIE['email'], "");
                    }

                    if (isset($_COOKIE['password'])) {

                        setcookie($_COOKIE['password'], "");
                    }
                }

                header('Location: index.php');
            }

            else {
                $message = "Votre mot de passe est incorrect, veuillez rentrer un mot de passe valide";
            }
        }

    }
?>


    <div id="login">
        <h3 class="text-center text-white pt-5">Connexion</h3>
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        

                            <div class="container text-center" style="background-color: #FB6969;">

                            <?php if (isset($message)) echo $message ?>

                            </div>
                        
                
                        <form id="login-form" class="form" action="" method="post">

                            <div class="form-group">
                                <label for="email">Adresse Email:</label><br>
                                <input type="text" name="email" id="email" class="form-control" 
                                value="<?php 
                                            if (isset($_COOKIE['email'])) {
                                                echo $_COOKIE['email'];
                                            } 
                                        ?>">
                            </div>

                            <div class="form-group">
                                <label for="password">Mot de passe:</label><br>
                                <input type="password" name="password" id="password" class="form-control"
                                value="<?php 
                                            if (isset($_COOKIE['password'])) {
                                                    echo $_COOKIE['password']; 
                                            }
                                            ?>">
                            </div>

                            <div class="form-group">
                                <input type="checkbox" name="sesouvenir" id="sesouvenir">
                                <label for="sesouvenir">Se souvenir de moi</label>
                            </div>

                            <div class="form-group">
                                <input type="submit" name="connexion" class="btn btn-info btn-md" value="Se Connecter">
                                <a href="password_forget.php" class="btn btn-info btn-md">Mot de passe oublié</a>
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

