<?php
  session_start();
  include('bd/connexionDB.php');
  // S'il n'y a pas de session alors on ne va pas sur cette page
  if(!isset($_SESSION['id'])){
    header('Location: index');
    exit;
  }
  // On récupère les informations de l'utilisateur connecté
  $afficher_profil = $DB->query("SELECT *
    FROM utilisateurs
    WHERE id = ?",
  array($_SESSION['id']));

  $afficher_profil = $afficher_profil->fetch();

?>
<html lang="fr">
 

<head>
       
    <meta charset="utf-8">
       
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
       
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" integrity="sha384-3AB7yXWz4OeoZcPbieVW64vVXEwADiYyAEhwilzWsLw+9FgqpyjjStpPnpBO8o8S" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
        <title>Mon profil</title>
     

    <head>
         

    <body>
        <?php include('includes/nav-bar.php');?>
        <div class="visite-profile">
            <div>
                <?php
   if(!empty($_SESSION['avatar'])){
       $chemin_img = realpath($_SERVER["DOCUMENT_ROOT"]."/forumxcience/image/upload/". $_SESSION['id'] . "/" . $_SESSION['avatar']);

      if(file_exists($chemin_img) && isset($_SESSION['avatar'])){
   ?>
                <img class="profile-avatar"
                    src="<?= "/forumxcience/image/upload/". $_SESSION['id'] . "/" . $_SESSION['avatar']; ?>" width="120" />

                <?php
      }else{
   ?>
                <img class="profile-avatar" src="/forumxcience/image/upload/default.svg" width="120" />
                <?php
      }
    }
   ?>
            </div>
            <h2>Voici le profil de <?= $afficher_profil['nom'] . $afficher_profil['prenoms']; ?></h2>
            <div>Quelques informations sur vous : </div>
            <ul>
                <li>Votre id est : <?= $afficher_profil['id'] ?></li>
                <li>Votre mail est : <?= $afficher_profil['mail'] ?></li>
                <li>Votre compte a été crée le : <?= $afficher_profil['date_creation_compte'] ?></li>
            </ul>
            <div>
                  <a href="modifier-profil">modifier mon profil</a>
            </div>
        </div>
        <?php include('includes/footer.php');   ?>
        <script src="js/jquery-3.2.1.slim.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
         
    </body>

</html>