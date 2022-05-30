<?php
  session_start();
  include('bd/connexionDB.php');

  if (!isset($_SESSION['id'])){
    header('Location: /forumxcience/index');
    exit;
  }
  // Récupèration de l'id passer en argument dans l'URL
  $id = (int) htmlentities(trim($_GET['id']));
  if(!is_int($id) || $id == 0 || $id = $_SESSION['id']){
    header('Location: ../utilisateurs');
    exit;
  }
  // On récupère les informations de l'utilisateur grâce à son ID
  $afficher_profil = $DB->query("SELECT *
    FROM utilisateurs
    WHERE id = ?",
    array($id));
  $afficher_profil = $afficher_profil->fetch();
  if(!isset($afficher_profil['id'])){
    header('Location: /forumxcience/index');
    exit;
  }
  function getAge($date){
    $age = date("Y") - date('Y', strtotime($date));
    if(date('md') < date('md', strtotime($date))){
      return $age - 1;
    }
    return $age;
  }
  /*if(isset($_POST['demander'])){
    if(!isset($relation['id'])){
      $DB->insert("INSERT INTO relation (id_demandeur, id_receveur, statut) VALUES (?, ?, ?)",
        array($_SESSION['id'], $id, 1));
      }
    header('Location: /forumxcience/voir-profil/' . $id);
    exit;
  }*/


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <base href="/forumxcience/">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <title>Le profil de <?= $afficher_profil['nom'] . " " . $afficher_profil['prenoms']; ?></title>
</head>

<body>
    <?php include('includes/nav-bar.php'); ?>
    <div class="visite-profile">
        <div>
            <?php
   if(!empty($_SESSION['avatar'])){
      if(file_exists("/forumxcience/upload/". $_SESSION['id'] . "/" . $_SESSION['avatar']) && isset($_SESSION['avatar'])){
   ?>
            <img class="profile-avatar"
                src="<?= "/forumxcience/upload/". $_SESSION['id'] . "/" . $_SESSION['avatar']; ?>" width="120" />

            <?php
      }}else{
   ?>
            <img class="profile-avatar" src="/forumxcience/upload/default.svg" width="120" />
            <?php
      }
   ?>
        </div>
        <h2>Voici le profil de <?= $afficher_profil['nom'] . " " . $afficher_profil['prenoms']; ?></h2>
        <div>Quelques informations sur lui : </div>
        <ul>
            <li>Votre id est : <?= $afficher_profil['id'] ?></li>
            <li>Votre mail est : <?= $afficher_profil['mail'] ?></li>
            <li>Votre compte a été crée le : <?= $afficher_profil['date_creation_compte'] ?></li>
        </ul>
        <form method="post">
            <input type="submit" name="demander" value="Ajouter en ami" />
        </form>
    </div>
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <body>

</html>