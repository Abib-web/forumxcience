<?php
  session_start();
  include('bd/connexionDB.php');

  if (!isset($_SESSION['id'])){
    header('Location: index');
    exit;
  }

  // On récupère tous les utilisateurs sauf l'utilisateur en cours
  $afficher_profil = $DB->query("SELECT *
    FROM utilisateurs
    WHERE id <> ?",
    array($_SESSION['id']));
  $afficher_profil = $afficher_profil->fetchAll(); // fetchAll() permet de récupérer plusieurs enregistrements
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/styles.css"/>
    <title>Utilisateurs du site</title>
  </head>
  <body>
<?php include('includes/nav-bar.php');?>      
    <div>Utilisateurs</div>
    <table>
      <tr>
        <th>Nom</th> 
        <th>Prénom</th>
        <th>Voir le profil</th>
      </tr>
      <?php
   foreach($afficher_profil as $ap){ ?>
          <tr>          
            <td><?= $ap['nom'] ?></td>
            <td><?= $ap['prenoms'] ?></td>
            <td><a href="voir_profil/<?= $ap['id'] ?>">Aller au profil</a></td>
          </tr>
        <?php } ?>
    </table>

<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-3.2.1.slim.min.js"></script>
<script src="js/popper.min.js"></script>
  </body>
</html>