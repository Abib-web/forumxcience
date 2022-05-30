<img src="image/logo.jpg" class="img-fluid" alt="...">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index">Accueil</a>
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
     </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
     <ul class="navbar-nav mr-auto">
        <?php
                if(!isset($_SESSION['id'])){
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="forum">Forum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog">Blog</a>
                    </li>
                    <?php
                    }else{
                        ?>
                    <li class="nav-item">
                        <a class="nav-link" href="forum">Forum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog">Blog</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" href="profil">Mon profil</a>
                    </li>
                    <?php
                    }
               ?>
     </ul>
     <ul class="navbar-nav ml-md-auto">
        <?php
                if(!isset($_SESSION['id'])){
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="inscription">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="connexion">Connexion</a>
                    </li>
                    <?php
                    }else{
                        ?>
                        <li class="nav-item">
                            <input class="form-control" type="text" id="search-user" value="" placeholder="Rechercher un ou plusieurs utilisateurs"/>
                            <div>
                                <div id="result-search"></div> <!-- C'est ici que nous aurons nos résultats de notre recherche -->
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="deconnexion">Déconnexion</a>
                        </li>
                <?php
                    }
               ?>
     </ul>
 </div>
</nav>