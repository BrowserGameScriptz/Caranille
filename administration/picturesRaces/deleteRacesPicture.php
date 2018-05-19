<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['finalDelete']))
{
    //On récupère le nom de l'image du formulaire précédent
    $adminFile = htmlspecialchars(addslashes($_POST['pictureFile']));

    if ($adminFile != "default.png")
    {
        unlink("../../img/races/" . $adminFile);

        ?>

        L'image a bien été supprimée
    
        <hr>
    
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>

        <?php
    }
    else
    {
        ?>

        Erreur : Il est impossible de supprimer l'image par défaut
    
        <hr>
    
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>

        <?php
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");