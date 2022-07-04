<?php
  session_start();
  include('../bd/connexionDB.php'); // Fichier PHP contenant la connexion à votre BDD

  $get_id = (int) trim($_GET['id']);

  if(empty($get_id)){
    header('Location: /blog');
    exit;
  }

  $req = $DB->query("SELECT b.*, u.prenoms, u.nom, c.titre as titre_cat
    FROM blog b
    LEFT JOIN utilisateurs u ON u.id = b.id_user
    LEFT JOIN categorie c ON c.id_categorie = b.id_categorie
    WHERE b.id = ?
    ORDER BY b.date_creation",
    array($get_id));

    $req = $req->fetch();
    $req_commentaire = $DB->query("SELECT TC.*, DATE_FORMAT(TC.date_creation, 'Le %d/%m/%Y à %H\h%i') as date_c, U.prenoms, U.nom
    FROM topic_commentaire TC
    LEFT JOIN utilisateurs U ON U.id = TC.id_user
    WHERE id_topic = ?
    ORDER BY date_creation DESC",
    array($get_id));
    $req_commentaire = $req_commentaire->fetchAll();

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
            header('Location: /forumxcience/xcblog/blog/' . $get_id);
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
function geText($string){
    $text = str_replace("```", "", $string);
    return $text;
}

$req['text'] = "$".$req['text'];
$code1_display = get_string_between($req['text'], '```','```');
$text1_display = get_string_between($req['text'], '$','```');


$code2_display = get_string_between($req['text1'], '```','```');
$text2_display = geText($req['text1']);
?>
<!DOCTYPE html>
<html>

<head>
    <base href="/forumxcience/" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $req['titre'] ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>
    <?php include('../includes/nav-bar.php');
    ?>
    <div class="container">
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <a class="btn btn-primary" href="blog" role="button">Retour</a>
                <div
                    style="width:800px; margin-top: 20px; background: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .09); padding: 5px 10px; border-radius: 10px">
                   <!-- <--!h1 style="color: #666; text-decoration: none; font-size: 28px; text-align:center"><?= $req['titre'] ?></h1> -->
                    <div style="border-top: 2px solid #EEE; padding: 15px 0">
                    <div class="introduction-part-1" style="display: flex;">
                        <img class="image-article-title" src="<?= "/forumxcience/image/article/". $_SESSION['id'] . "/" . $req['img_title']; ?>" width="120" />
                        <div class="introdution"><?= nl2br($req['introduction']); ?></div>
                    </div>
                    <br>
                    <div class="text-1-part" style="display: block;">
                        <div class="text"><?= nl2br($text1_display); ?></div>
                        <div><pre><div class="code-questions"><?= $code1_display;?></div> </pre></div>
                    </div>
                    <br>
                    <img class="image-article-texte" src="<?= "/forumxcience/image/article/". $_SESSION['id'] . "/" . $req['img_fin']; ?>" width="120" />
                    <br>
                    <br>
                    <div class="text-2-part" style="display: block;">
                    <div class="text"><?= nl2br($text2_display); ?></div>
                        <div><pre><div class="code-questions"><?= $code2_display; ?></div> </pre></div>
                    </div>
                    <br>
                    <div style="padding-top: 15px; color: #ccc; font-style: italic; text-align: right;font-size: 12px; float:left;text-align:center">
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
 ?>       
              <div
        style="width: 800px; background: white; box-shadow: 0 5px 15px rgba(0, 0, 0, .15); padding: 5px 10px; border-radius: 10px; margin-top: 20px">
                    <h3>Commentaires</h3>
                    <?php
        foreach($req_commentaire as $rc){
            ?>  
                    <div style="background: #eee; margin-top: 20px; padding: 5px 10px; border-radius: 10px">
                          <div style="font-weight: bold"><?= "De " . $rc['nom'] . " " . $rc['prenoms'] . " : "?></div>
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
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>