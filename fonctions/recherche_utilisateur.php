<?php
  session_start();
  require_once('../bd/connexionDB.php');

  if(isset($_GET['user'])){
    $user = (String) trim($_GET['user']);

    $req = $DB->query("SELECT *
      FROM utilisateurs
      WHERE nom LIKE ?
      LIMIT 10",
      array("$user%"));

    $req = $req->fetchALL();

    foreach($req as $r){
      ?>
        <div style="margin-top: 20px 0; border-bottom: 2px solid #ccc"><a href="/forumxcience/voir_profil/<?= $r['id'] ?>"><?= $r['nom'] . " " . $r['prenoms'] ?></a></div><?php
    }
  }
?>