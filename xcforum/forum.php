<?php
  session_start();
  include('../bd/connexionDB.php'); // Fichier PHP contenant la connexion à votre BDD

  $req = $DB->query("SELECT *
    FROM forum
    ORDER BY ordre");

    $req = $req->fetchAll();
?>
<?php
    require_once('../includes/nav-bar.php');
  ?>
<base href="/forumxcience/">
<link rel="stylesheet" href="css/bootstrap.min.css"/>
<link rel="stylesheet" href="css/styles.css"/>
<link rel="stylesheet" href="css/bootstrap.min.css"/>
<link rel="stylesheet" href="css/styles.css"/>
<table class="table table-striped" style="border: 1px solid #237689; border-radius: 10px; width: 50%; margin: auto;display: flex;justify-content: center;">
  <tr>
    <th>ID</th>
    <th>Titre</th>
  </tr>
  <?php
    foreach($req as $r){
        ?>  
    <tr>
      <td><?= $r['id'] ?></td>
      <td><a href="forum/<?= $r['id'] ?>"><?= $r['titre'] ?></a></td>
    </tr>   
    <?php
}
?>
</table>
<script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>