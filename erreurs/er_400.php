<?php
    session_start();
    include('../bd/connexionDB.php');
    $erreur = (int) htmlentities(trim($_GET['erreur']));
    if(!is_int($erreur) || $erreur <= 0){
        header('Location: /');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/styles.css"/>
</head>
<body>
    <?php
        require_once('../includes/nav-bar.php');
    ?>
<div class="container">
    <div class="row">   
        <div class="col-sm-0 col-md-2 col-lg-3"></div>
        <div class="col-sm-12 col-md-8 col-lg-6">
                <h1 style="text-align:center; margin: top 20px;"><?= 'Erreur' . $erreur ?></>
            </div>
        </div>
</div>
<script src="../js/jquery-3.2.1.slim.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>