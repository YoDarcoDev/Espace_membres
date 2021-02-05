<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
    

<link rel="stylesheet" href="include/bootstrap.min.css">

<style> 

body {
  margin: 0;
  padding: 0;
  background-color: #147AF5;
  height: 100vh;
}
#login .container #login-row #login-column #login-box {
  margin-top: 20px;
  max-width: 600px;
  height: 620;
  border: 1px solid #9C9C9C;
  background-color: #EAEAEA;
}
#login .container #login-row #login-column #login-box #login-form {
  padding: 20px;
}
#login .container #login-row #login-column #login-box #login-form #register-link {
  margin-top: -85px;
}

</style>

<div>
  <nav class="navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Accueil</a>
          <ul class="navbar-nav navbar-right">
            <li class="nav-item"><a class="nav-link " href="#">Blog</a></li>
            <li class="nav-item"><a class="nav-link " href="#">A propos</a></li>
            <li class="nav-item"><a class="nav-link " href="#">Contact</a></li>
          </ul>
        
          <ul class="navbar-nav ml-auto">

      <?php
          if(isset($_SESSION['id']))
          {?>

          <button class="btn btn-outline-success" ><a href="deconnexion.php">Se déconnecter</a></button>
          <button class="btn btn-outline-success"><a href="profil.php">Profil</a></button>

          <?php
           }
          else{?>

          <button class="btn btn-outline-success" ><a href="inscription.php">Inscription</a></button>
          <button class="btn btn-outline-success" ><a href="connexion.php">Se connecter</a></button>

          </ul>
                  
    </nav>
</div>

    <?php } ?>

    

 

