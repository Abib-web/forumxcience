<?php
  session_start();
  include('bd/connexionDB.php'); // Fichier PHP contenant la connexion à votre BDD

 // S'il y a une session alors on ne retourne plus sur cette page  
 if (isset($_SESSION['id'])){
     header('Location: index');
     exit;
}

// Si la variable "$_Post" contient des informations alors on les traitres
 if(!empty($_POST)){
     extract($_POST);
     $valid = true;
        if (isset($_POST['connexion'])){
             $mail = htmlentities(strtolower(trim($mail)));
             $mdp = trim($mdp);

             if(empty($mail)){ // Vérification qu'il y est bien un mail de renseigné
                $valid = false;
                $er_mail = "Il faut mettre un mail";
             }

             if(empty($mdp)){ // Vérification qu'il y est bien un mot de passe de renseigné
                $valid = false;
                $er_mdp = "Il faut mettre un mot de passe";
            }

// On fait une requête pour savoir si le couple mail / mot de passe existe bien car le mail est unique !
     $req = $DB->query("SELECT *
                 FROM utilisateurs
                 WHERE mail = ? AND mdp = ?",
                 array($mail, crypt($mdp, '$6$rounds=5000$macleapersonnaliseretagardersecret$')));
    $req = $req->fetch();

// Si on a pas de résultat alors c'est qu'il n'y a pas d'utilisateur correspondant au couple mail / mot de passe
     if ($req['id'] == ""){
         $valid = false;
         $er_mail = "Le mail ou le mot de passe est incorrecte";
         }elseif($req['n_mdp'] == 1){ // On remet à zéro la demande de nouveau mot de passe s'il y a bien un couple mail / mot de passe
            $DB->insert("UPDATE utilisateur SET n_mdp = 0 WHERE id = ?",
            array($req['id']));
            }
// Si le token n'est pas vide alors on ne l'autorise pas à accéder au site

if($req['token'] === NULL){
    $valid = false;
    $er_mail = "Le compte n'a pas été validé";
}

// S'il y a un résultat alors on va charger la SESSION de l'utilisateur en utilisateur les variables $_SESSION
if($valid){
     $_SESSION['id'] = $req['id']; // id de l'utilisateur unique pour les requêtes futures
     $_SESSION['nom'] = $req['nom'];
     $_SESSION['prenom'] = $req['prenoms'];
     $_SESSION['mail'] = $req['mail'];
     $_SESSION['role'] = $req['role'];
     $_SESSION['avatar'] = $req['avatar'];
    header('Location:index');
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
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/styles.css">
        <title>Connexion</title>
    </head>
    <body> 
<?php
include('includes/nav-bar.php');
?>
    <div class="container">
    <div class="row">   
        <div class="col-0 col-sm-0 col-md-2 col-lg-3"></div>
        <div class="col-12 col-sm-12 col-md-8 col-lg-6 border-pop">     
            <?php
                include('includes/popuplogin.php');
            ?>
            </div>
        </div>
    </div>
<script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    </body>
</html>