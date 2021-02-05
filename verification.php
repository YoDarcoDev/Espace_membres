<?php require 'include/header.php';
require_once 'include/start_bdd.php';
?>
<title>Verification</title>
</head>
<body>

<?php
    

    // RECUPERATION DES PARAMETRES PASSES PAR L'URL
    if ($_GET) {

        if (isset($_GET['email'])) {
            
            $email = $_GET['email'];
        }

        if (isset($_GET['token'])) {
            
            $token = $_GET['token'];
        }
    }


    // VERIFIER SI CES PARAMETRES NE SONT PAS VIDES
    
    if (!empty($email) && !empty($token)) {

        $requete = $bdd->prepare("SELECT * FROM membres.table_membres WHERE email=:email");

        $requete->bindvalue(':email', $email);

        $requete->execute();

        // NOMBRE ENREGISTREMENT RETOURNÉ PAR LA REQUETE
        $nombre = $requete->rowCount();

        if ($nombre == 1) {

            $update = $bdd->prepare("UPDATE membres.table_membres SET validation=:validation, token=:token WHERE email=:email");

            $update->bindvalue(':validation', 1);
            $update->bindvalue(':token', 'valide');
            $update->bindvalue(':email', $email);

            $resultUpdate = $update->execute();

            if ($resultUpdate) {

                $message = "Votre adresse email a bien été confirmée, vous pouvez désormais vous connecter";
                
                echo "<script type='text/javascript'>
                        alert('Votre adresse email est bien confirmée, vous pouvez désormais vous connecter');
                        document.location.href='connexion.php';
                    </script>";

            }
        }
    }


if (isset($message)) {
    echo $message;
}


?>

<body>
    
</body>
</html>