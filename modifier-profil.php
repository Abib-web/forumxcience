<?php
session_start();
include('bd/connexionDB.php');
if (!isset($_SESSION['id'])){
     header('Location: index');
     exit;
    }
 // On récupère les informations de l'utilisateur connecté
 $afficher_profil = $DB->query("SELECT *
        FROM utilisateurs
        WHERE id = ?",
        array($_SESSION['id']));
 $afficher_profil = $afficher_profil->fetch();

 if(!empty($_POST)){
      extract($_POST);
      $valid = true;

  if (isset($_POST['modification'])){
      $nom = htmlentities(trim($nom));
      $prenom = htmlentities(trim($prenom));
      $mail = htmlentities(strtolower(trim($mail)));

  if(empty($nom)){
       $valid = false;
        $er_nom = "Il faut mettre un nom";
     }

  if(empty($prenom)){
      $valid = false;
      $er_prenom = "Il faut mettre un prénom";
     }

  if(empty($mail)){
       $valid = false;
       $er_mail = "Il faut mettre un mail";

    }elseif(!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $mail)){
        $valid = false;
        $er_mail = "Le mail n'est pas valide";
     }else{
          $req_mail = $DB->query("SELECT mail
                FROM utilisateurs
                WHERE mail = ?",
                array($mail));
          $req_mail = $req_mail->fetch();

    if ($req_mail['mail'] <> "" && $_SESSION['mail'] != $req_mail['mail']){
         $valid = false;
         $er_mail = "Ce mail existe déjà";
         }
     }
    if ($valid){

    $DB->insert("UPDATE utilisateurs SET prenoms = ?, nom = ?, mail = ?
         WHERE id = ?",
         array($prenom, $nom,$mail, intval($_SESSION['id'])));

         $_SESSION['nom'] = $nom;
         $_SESSION['prenom'] = $prenom;
         $_SESSION['mail'] = $mail;

        }


     $dossier = 'image/upload/'.$_SESSION['id']."/";
     if(!is_dir($dossier)){
          mkdir($dossier);
     }

     $fichier = basename($_FILES['file']['name']);

     if(move_uploaded_file($_FILES['file']['tmp_name'], $dossier . $fichier)) {
          if(file_exists("image/upload/".$_SESSION['id'].'/'.$_SESSION['avatar']) && isset($_SESSION['avatar'])){
               unlink("image/upload/".$_SESSION['id']."/".$_SESSION['avatar']);
          }

          $req = $DB->insert("UPDATE utilisateurs SET avatar = ? WHERE id = ?",array($fichier, $_SESSION['id']));
          $_SESSION['avatar'] = $fichier;
     } else {
          header('Location: /forumxcience/modifier-profil');
          exit;
     }
     }

    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier votre profil</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>

</head>

<body>
    <?php include("includes/nav-bar.php") ;?>
    <div class="container visite-profile">
        <div class="row">
            <div class="col-sm-0 col-md-2 col-lg-3"></div>
            <div class="col-sm-12 col-md-8 col-lg-6">
                <h1>Modification</h1>
                <form method="post" enctype="multipart/form-data">
                    <?php
                 if (isset($er_nom)){
                      ?>
                    <div><?= $er_nom ?></div>
                    <?php }  ?>
                    <div class="form-group">
                        <input type="text" placeholder="Votre nom" name="nom"
                            value="<?php if(isset($nom)){ echo $nom; }else{ echo $afficher_profil['nom'];}?>" required>
                    </div>
                    <?php if (isset($er_prenom)){ ?>
                    <div><?= $er_prenom ?></div>
                    <?php } ?>
                    <div class="form-group">
                        <input type="text" placeholder="Votre prénom" name="prenom"
                            value="<?php if(isset($prenom)){ echo $prenom; }else{ echo $afficher_profil['prenoms'];}?>"
                            required>
                    </div>
                    <?php if (isset($er_mail)){ ?>
                    <div><?= $er_mail ?></div>
                    <?php } ?>
                    <div class="form-group">
                        <input type="email" placeholder="Adresse mail" name="mail"
                            value="<?php if(isset($mail)){ echo $mail; }else{ echo $afficher_profil['mail'];}?>"
                            required>
                    </div>
                    <?php
               if(isset($_SESSION['avatar'])){
            ?>
                <!--div class="form-group">
                    <div
                        style="background:url(<?= '/forumxcience/image/upload/'.$_SESSION['id'].'/'.$_SESSION['avatar'] ?>); width:150px; height:150px;">
                    </div>
                </-div -->
                    <?php } ?>
                <div class="form-group">
                    <label for="file" style="margin-bottom: 0; margin-top: 5px; display: inline-flex">
                        <input id="file" type="file" name="file" class="hide-upload" required />
                        <i class="fa fa-plus image-plus"></i>
                        </label>
                </div>
                <div class="form-group">
                        <button type="submit" name="modification">Modifier</button>
                </div>
                </form>
            </div>
        </div>
        <script src="js/jquery-3.2.1.slim.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
           
</body>

</html>