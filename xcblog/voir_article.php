<?php

  session_start();
  include('../bd/connexionDB.php'); // Fichier PHP contenant la connexion à votre BDD
    require_once('../vendor/autoload.php');

  $get_id = (int) trim($_GET['id']);

  if(empty($get_id)){
    header('Location: /blog');
    exit;
  }


  $req = $DB->query("SELECT b.*, u.prenoms, u.nom, u.avatar, c.titre as titre_cat
    FROM blog b
    LEFT JOIN utilisateurs u ON u.id = b.id_user
    LEFT JOIN categorie c ON c.id_categorie = b.id_categorie
    WHERE b.id = ?
    ORDER BY b.date_creation",
    array($get_id));

    $req = $req->fetch();
    $req_commentaire = $DB->query("SELECT BC.*, DATE_FORMAT(BC.date_creation, 'Le %d/%m/%Y à %H\h%i') as date_c, U.prenoms, U.nom, U.avatar
    FROM blog_commentaire BC
    LEFT JOIN utilisateurs U ON U.id = BC.id_user
    WHERE id_blog = ?
    ORDER BY date_creation DESC",
    array($get_id));
    $req_commentaire = $req_commentaire->fetchAll();

        // La requete pour avoir la listes des articles
    $req_blog = $DB->query("SELECT b.*, u.prenoms, u.nom, c.titre as titre_cat
    FROM blog b
    LEFT JOIN utilisateurs u ON u.id = b.id_user
    LEFT JOIN categorie c ON c.id_categorie = b.id_categorie
    ORDER BY b.date_creation DESC");

  $req_blog = $req_blog->fetchAll();

  // cette requete recupere toutes les informations concernant les forums pour les afficher dans le aside

  $req_forum = $DB->query("SELECT *
  FROM forum
  ORDER BY ordre");

  $req_forum = $req_forum->fetchAll();
  if(!empty($_POST)){
      extract($_POST);
      $valid = true;
      if (isset($_POST['ajout-commentaire'])){
          $text  = (String) trim($text);
          if(empty($text)){
              $valid = false;
              $er_commentaire = "Il faut mettre un commentaire";
            }elseif(iconv_strlen($text, 'UTF-8') <= 3){
            $valid = false;
            $er_commentaire = "Il faut mettre plus de 3 caractères";
        }

        $text = htmlentities($text);
        if($valid){
            $date_creation = date('Y-m-d H:i:s');
            $DB->insert("INSERT INTO blog_commentaire (id_user, id_blog, text, date_creation) VALUES (?, ?, ?, ?)",
            array($_SESSION['id'], $get_id, $text, $date_creation));
            header('Location: /forumxcience/blog/' . $get_id);
            exit;
        }
    }
}
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

#$req['text'] = "$".$req['text'];
#$code1_display = get_string_between($req['text'], '```','```');
#$text1_display = get_string_between($req['text'], '```','```');

#$text = str_replace('```','<pre><code>',$req['text1']);
#$code2_display = get_string_between($req['text1'], '```','```');
#$text2_display = geText($req['text1']);
#echo $text;
$Parsedown = new Parsedown();
$req['text'] = $Parsedown->text($req['text']);
$req['text1'] = $Parsedown->text($req['text1']);

?>
<!DOCTYPE html>
<html>

<head>
    <base href="/forumxcience/" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $req['titre'] ?></title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/default.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css"
        integrity="sha384-3AB7yXWz4OeoZcPbieVW64vVXEwADiYyAEhwilzWsLw+9FgqpyjjStpPnpBO8o8S" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>
    <?php include('../includes/nav-bar.php');
    ?>
    <div id="container1">
        <aside class="aside-left">
            <h4>Acueil</h4>
            <ul>
                <li>Utilisateurs</li>
                <li>Questions</li>
                <li>Blog</li>
                <li>Partenaires</li>
                <li>A propos de nous</li>
            </ul>
        </aside>
        <div class="container container-pub">
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-8 col-md-8 col-lg-8">
                    <a class="btn btn-primary" href="blog" role="button">Retour</a>
                    <div
                        style="width:800px; margin-top: 20px; background: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .09); padding: 5px 10px; border-radius: 10px">
                        <!-- <--!h1 style="color: #666; text-decoration: none; font-size: 28px; text-align:center"><?= $req['titre'] ?></h1> -->
                        <div style="border-top: 2px solid #EEE; padding: 15px 0">
                            <div class="introduction-part-1" style="display: flex;">
                                <img class="image-article-title"
                                    src="<?= "/forumxcience/image/article/". $req[1] . "/" . $req['img_title']; ?>"
                                    width="120" />
                                <div class="introdution"><?= nl2br($req['introduction']); ?></div>
                            </div>
                            <br>
                            <div class="text-1-part" style="display: block;">
                                <div class="text"><?= nl2br($req['text']); ?></div>
                            </div>
                            <br>
                            <img class="image-article-texte"
                                src="<?= "/forumxcience/image/article/". $req[1] . "/" . $req['img_fin']; ?>"
                                width="120" />
                            <br>
                            <br>
                            <div class="text-2-part" style="display: block;">
                                <div class="text"><?= nl2br($req['text1']); ?></div>
                            </div>
                            <br>
                            <div
                                style="padding-top: 15px; color: #ccc; font-style: italic; text-align: right;font-size: 12px; float:left;text-align:center">
                                Fait par <?= $req['nom'] . " " . $req['prenoms'] ?> le
                                <?= date_format(date_create($req['date_creation']), 'D d M Y à H:i'); ?> dans le thème
                                <?= $req['titre_cat'] ?>
                            </div>
                        </div>
                    </div>


                    <!-- Commentaires -->
                    <?php
    if(isset($_SESSION['id'])){
    ?>
                              <div
                        style=" width: 800px;background: white; box-shadow: 0 5px 15px rgba(0, 0, 0, .15); padding: 5px 10px; border-radius: 10px; margin-top: 20px">
                                    <h3>Participer à l'article</h3>
                                    <?php
         if(isset($er_commentaire)){
     ?>
                                    <div class="er-msg"><?= $er_commentaire ?></div>
                                    <?php
        }
?>
                        <form method="post">
                            <div class="form-group">
                                <textarea class="form-control" name="text" rows="4"
                                    placeholder="Écrivez-votre commentaire ..."></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="ajout-commentaire">Envoyer</button>
                            </div>
                        </form>
                    </div>
                    <?php
    }
 ?>  <div style="width: 800px; background: white; box-shadow: 0 5px 15px rgba(0, 0, 0, .15); padding: 5px 10px; border-radius: 10px; margin-top: 20px">
                        <h3>Commentaires</h3>
                        <?php
        foreach($req_commentaire as $rc){
            ?>
                        <div style="background: #eee; margin-top: 20px; padding: 5px 10px; border-radius: 10px">
                            <?php $resultat =  $rc['avatar'] ?  $rc['avatar'] : 'default.svg' ; if($rc['avatar'] )
                        {
                    ?>
                            <div style="font-weight: bold"><a href="/forumxcience/voir_profil/<?=$rc['id_user']?>"><img
                                        src="<?= "/forumxcience/image/upload/". $rc['id_user'] . "/" . $resultat ; ?>"
                                        width="20" /><?= "De " . $rc['nom'] . " " . $rc['prenoms'] . " : "?></a></div>
                            <?php } else{?>
                            <div style="font-weight: bold"><a href="/forumxcience/voir_profil/<?=$rc['id_user']?>"><img
                                        src="<?= "/forumxcience/image/upload/". "/" . $resultat ; ?>"
                                        width="20" /><?= $rc['nom'] . " " . $rc['prenoms'] ?></a></div>
                            <?php } ?>

                            <?= nl2br($rc['text']) ?>
                            <div style="text-align: right; font-size: 12px; color: #665"><?= $rc['date_c'] ?></div>
                        </div>
                        <?php
        }
        ?>
                    </div>
                </div>
            </div>
        </div>
        <aside class="aside-profile">
            <h4>Par <?php echo $req['nom']," "; echo $req['prenoms']; ?></h4>
            <img style="border-radius:50%; width: 20%; height:20%; margin-left: 150px;margin-right:200px; margin-bottom: 10px"
                src="<?= "/forumxcience/image/upload/". $req['id_user'] . "/" . $req['avatar']; ?>" alt="">
            <h4 class="btn btn-info bg-info">voir son profil</h4>
        </aside>
        <aside class="aside-forum-list">
            <h4>Quelques question</h4>
            <?php foreach($req_forum as $r){
              ?>
            <ul>

                <li><a href="forum/<?php echo $r['id']; ?>"> <?php echo $r['titre']; ?></a></li>
            </ul>
            <?php } ?>
        </aside>
        <aside class="aside-blog-list">
            <h4>Quelques articles</h4>
            <?php foreach($req_blog as $r){
              ?>
            <ul>
                <li><a href="blog/<?php echo $r['id']; ?>"> <?php echo $r['titre']; ?></a></li>
            </ul>
            <?php } ?>
        </aside>
    </div>
    <?php include('../includes/footer.php');   ?>
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
    <script>
    hljs.highlightAll();
    </script>
    <script src="js/jquery-3.2.1.slim.min.js"></script>
        <script src="js/popper.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
</body>

</html>