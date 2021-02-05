<?php require 'include/header.php'; ?>
<title>Profil</title>
</head>
<body>
</div>



<?php


    if (isset($_POST['modification']) && isset($_SESSION['id'])) {


        $id = $_SESSION['id'];

        // SI LE CHAMPS USERNAME EST VIDE OU SI LE REGEX NE CORRESPOND PAS
        if(empty($_POST['username']) || !preg_match('/[a-zA-Z0-9]+/', $_POST['username'])) {
            
            $message = "Le username doit être une chaine de caractères";
        }

        elseif (empty($_POST['password']) || $_POST['password2'] != $_POST['password']) {

            $message = "Veuillez saisir un mot de passe valide";
        }

        else {

            // CONNEXION BDD
            require_once 'include/start_bdd.php'; 
    
    
            // VERIFIER SI USERNAME EXISTE EN BDD
            $req_username = $bdd->prepare("SELECT * FROM membres.table_membres WHERE username = :username");
            $req_username->bindvalue(':username', $_POST['username']);
            $req_username->execute();
            $result_username = $req_username->fetch();

            if ($result_username) {
                $message = "Le username que vous avez choisi existe déjà, veuillez en saisir un nouveau";
            }

            else {
                
                // CRYPTER LE MDP
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


                // REQUETE SQL PREPAREE
                $requete = $bdd->prepare("UPDATE membres.table_membres SET username=:username, password=:password WHERE id=:id");


                // LIAISON DES PARAMETRES NOMMÉS
                $requete->bindvalue(':username', $_POST['username']);
                $requete->bindvalue(':password', $password);
                $requete->bindvalue(':id', $id);


                // EXECUTER LA REQUETE
                $requete->execute();

                header('Location: index.php');
            }
    
        }
    }

        ?>

        <div id="login">
        <h3 class="text-center text-white pt-5">Modifier mon profil</h3>
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
                                <label for="password">Mot de passe:</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="password2">Confirmation Mot de passe:</label><br>
                                <input type="password" name="password2" id="password2" class="form-control">
                            </div>

                            <div class="form-group">
                                <input type="submit" name="modification" class="btn btn-info btn-md" value="Modifier">
                            </div>

    
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>



<body>
</html>