
<form method="post">
        <?php
         if (isset($er_mail)){
             ?>
                <div><?= $er_mail ?></div>
            <?php
            }
             ?>
  <div class="form-group">
    <label for="exampleInputEmail1">mail ou pseudo</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Adresse mail" name="mail" value="<?php if(isset($mail)){ echo $mail; }?>" required>
    <small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais votre e-mail avec quelqu'un d'autre.</small>
  </div>
  <?php
                if (isset($er_mdp)){
                    ?>
                <div><?= $er_mdp ?></div>
            <?php
                }
             ?>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){ echo $mdp; }?>" required>
  </div>
  <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Garder votre section active</label>
  </div>
  <button type="submit" class="btn btn-primary" type="submit" name="connexion">Se connecter</button>
</form>
