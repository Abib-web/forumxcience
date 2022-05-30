<?php
include('bd/connexionDB.php');
session_start();

// Recuperons les articles
$req = $DB->query("SELECT b.*, u.prenoms, u.nom, c.titre as titre_cat
    FROM blog b
    LEFT JOIN utilisateurs u ON u.id = b.id_user
    LEFT JOIN categorie c ON c.id_categorie = b.id_categorie
    ORDER BY b.date_creation DESC");

  $req = $req->fetchAll();
     ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" integrity="sha384-3AB7yXWz4OeoZcPbieVW64vVXEwADiYyAEhwilzWsLw+9FgqpyjjStpPnpBO8o8S" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>Accueil</title>
  </head>
  <body>
<?php
include('includes/nav-bar.php');
?>

<div class="container">
    <div class="row">   
        <div class="col-0 col-sm-0 col-md-2 col-lg-3"></div>
        <div class="col-12 col-sm-12 col-md-8 col-lg-6">
            <h1>Forum Xcience</h1>
            <div>
                <?php
                    if(!isset($_SESSION['id'])){
                ?>
                       
                    <?php
                        }
                         ?>
            </div>
            <div>
                <?php
                    if(isset($_SESSION['id'])){
                        echo '<h1>  Bienvenue   ' . $_SESSION['nom'] . " ". $_SESSION['prenom'].'</h1>';
                         }
                          ?>
            </div>
        </div>
    </div>
</div>
<div class="wrapper">
    <div class="title-categorie">
        <div class="forum-title">
            <h1><a href="">Forum</a></h1>
        </div>
        <div class="top-title">
            <h1><a href="">Meilleurs articles</a></h1>
        </div>
        <div class="recent-title">
            <h1><a href="">Articles recents</a></h1>
        </div>
    </div>
    <article>
    <?php foreach($req as $r){
              ?>
                <div
                    style="margin-top: 20px; background: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .09); padding: 5px 10px; border-radius: 10px">
                    <a href="blog/<?= $r['id'] ?>"
                        style="color: #666; text-decoration: none; font-size: 28px;"><?= $r['titre'] ?></a>
                </div>
                <?php
              }
            ?>
    </article>

</div>
<?php include('includes/footer.php');   ?>
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>