<?php
  session_start();
  include('../bd/connexionDB.php'); // Fichier PHP contenant la connexion à votre BDD

  if (!isset($_SESSION['id'])){
    header('Location: /blog');
    exit;
  }

  if($_SESSION['role'] <> 1){
    header('Location: /blog');
    exit;
  }

  if(!empty($_POST)){
    extract($_POST);
    $valid = true;

    if (isset($_POST['creer-article'])){
      $titre  = (string) htmlentities(trim($titre));
      $contenu = (string) htmlentities(trim($contenu));
      $categorie = (int) htmlentities(trim($categorie));

      if(empty($titre)){
        $valid = false;
        $er_titre = ("Il faut mettre un titre");
      }

      if(empty($contenu)){
        $valid = false;
        $er_contenu = ("Il faut mettre un contenu");
      }

      if(empty($categorie)){
        $valid = false;
        $er_categorie = "Le thème ne peut pas être vide";
      }else{
        // On vérifit que le mail est disponible
        $verif_cat = $DB->query("SELECT id, titre
          FROM categorie
          WHERE id = ?",
          array($categorie));

        $verif_cat = $verif_cat->fetch();

        if (!isset($verif_cat['id'])){
          $valid = false;
          $er_categorie = "Ce thème n'existe pas";
        }
      }

      if($valid){
        $dossier = '../image/article/'.$_SESSION['id']."/" ;
      if(!is_dir($dossier)){
        mkdir($dossier);
      }
      $file_1 = 'file_1';
      $file_2 = 'file_2';
      $file_3 = 'file_3';
      $article = $DB->query("SELECT * FROM blog WHERE id_user = ?",array($_SESSION['id']));
      $article = $article->fetch();
      $fichier_1 = basename($_FILES[$file_1]['name']);
      $fichier_2 = basename($_FILES[$file_2]['name']);
      $fichier_3 = basename($_FILES[$file_3]['name']);
        if(move_uploaded_file($_FILES[$file_1]['tmp_name'], $dossier . $fichier_1) && move_uploaded_file($_FILES[$file_2]['tmp_name'], $dossier . $fichier_2)&& move_uploaded_file($_FILES[$file_3]['tmp_name'], $dossier . $fichier_3)) {
          if(file_exists("image/article/".$_SESSION['id'].'/'.$article['img_title']) && isset($article['img_title'])){
               unlink("image/article/".$_SESSION['id']."/".$article['img_title']);
          }
          if(file_exists("image/article/".$_SESSION['id'].'/'.$article['img_milieu']) && isset($article['img_milieu'])){
            unlink("image/article/".$_SESSION['id']."/".$article['img_milieu']);
       }
       if(file_exists("image/article/".$_SESSION['id'].'/'.$article['img_fin']) && isset($article['img_fin'])){
        unlink("image/article/".$_SESSION['id']."/".$article['img_fin']);
    }
  }
        $date_creation = date('Y-m-d H:i:s');
        $DB->insert("INSERT INTO blog (id_user, titre, text, date_creation, id_categorie,img_title , img_milieu, img_fin) VALUES
          (?, ?, ?, ?, ?,?,?,?)",
          array($_SESSION['id'], $titre, $contenu, $date_creation, $categorie, $fichier_1, $fichier_2, $fichier_3));

        header('Location: /forumxcience/xcblog/blog');
        exit;
      }
    }
  }
?>
<!DOCTYPE html>
<html>

<head>
    <base href="/forumxcience/" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Créer mon article</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
</head>

<body>
    <?php
     include('../includes/nav-bar.php');
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="cdr-ins">
                    <h1>Créer mon article</h1>
                    <form method="post" enctype="multipart/form-data">
                        <?php
                // S'il y a une erreur sur le nom alors on affiche
                if (isset($er_categorie)){
                ?>
                        <div class="er-msg"><?= $er_categorie ?></div>
                        <?php
                }
              ?>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <select name="categorie" class="custom-select" id="inputGroupSelect01">
                                    <?php
                      if(empty($categorie)){
                      ?>
                                    <option selected>Sélectionner votre thème</option>
                                    <?php
                      }else{
                      ?>
                                    <option value="<?= $categorie ?>"><?= $verif_cat['titre'] ?></option>
                                    <?php
                      }

                      $req_cat = $DB->query("SELECT *
                        FROM categorie");
                      $req_cat = $req_cat->fetchALL();

                      foreach($req_cat as $rc){
                      ?>
                                    <option value="<?= $rc['id'] ?>"><?= $rc['titre'] ?></option>
                                    <?php
                      }
                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                if (isset($er_titre)){
                ?>
                        <div class="er-msg"><?= $er_titre ?></div>
                        <?php
                }
              ?>
                        <div class="form-group">
                            <input class="form-control" type="text" placeholder="Votre titre" name="titre"
                                value="<?php if(isset($titre)){ echo $titre; }?>">
                        </div>
                        <?php
                if (isset($er_contenu)){
                  ?>
                        <div class="er-msg"><?= $er_contenu ?></div>
                        <?php
                  }
              ?>
                        <div class="form-group">
                            <textarea class="form-control" rows="3" placeholder="Décrivez votre article"
                                name="contenu"><?php if(isset($contenu)){ echo $contenu; }?></textarea>
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="form-group">
                            <label for="file_1" style="margin-bottom: 0; margin-top: 5px; display: inline-flex">
                                Image du titre : <input id="file_1" type="file" name="file_1" class="hide-upload"
                                    required />
                                <i class="fa fa-plus image-plus"></i>
                            </label>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label for="file_2" style="margin-bottom: 0; margin-top: 5px; display: inline-flex">
                                    Image du milieu : <input id="file_2" type="file" name="file_2" class="hide-upload"
                                        required />
                                    <i class="fa fa-plus image-plus"></i>
                                </label>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="file_3" style="margin-bottom: 0; margin-top: 5px; display: inline-flex">
                                        Image de la fin : <input id="file_3" type="file" name="file_3"
                                            class="hide-upload" required />
                                        <i class="fa fa-plus image-plus"></i>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit" name="creer-article">Envoyer</button>
                                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>