<?php
  session_start();
  include('../bd/connexionDB.php'); // Fichier PHP contenant la connexion à votre BDD

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
    <base href="/forumxcience/">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Blog</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>
    <?php include('../includes/nav-bar.php')
    ?>

    <div class="container">
        <div class="row">

            <div class="col-sm-0 col-md-0 col-lg-0"></div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h1 style="text-align: center">Les publications</h1>

                <?php if(isset($_SESSION['id']) && $_SESSION['role'] == 1){
          ?><a href="blog/creer-mon-article" class="btn btn-primary btn-lg active" role="button"
                    aria-pressed="true">Créer un article</a><?php
              }
            ?><?php foreach($req as $r){
              ?>
                <div
                    style="margin-top: 20px; background: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .09); padding: 5px 10px; border-radius: 10px">
                    <a href="blog/<?= $r['id'] ?>"
                        style="color: #666; text-decoration: none; font-size: 28px;"><?= $r['titre'] ?></a>
                    <div style="display: flex;">


                        <img class="image-article-title-sm"
                            src="<?= "/forumxcience/image/article/". $r['id'] . "/" . $r['img_title']; ?>" alt="">
                <div style="border-top: 2px solid #EEE; padding: 15px 0"><?= nl2br($r['introduction']); ?><a href="blog/<?= $r['id'] ?>">Voir plus</a></div>
                </div>
                    <div style="padding-top: 15px; color: #ccc; font-style: italic; text-align: right;font-size: 12px;">
                        Fait par <?= $r['nom'] . " " . $r['prenoms'] ?> le
                        <?= date_format(date_create($r['date_creation']), 'D d M Y à H:i'); ?> dans le thème
                        <?= $r['titre_cat'] ?>
                    </div>
                </div>
                <?php
              }
            ?>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</html>