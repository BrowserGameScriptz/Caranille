<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['adminNewsId'])
&& isset($_POST['token'])
&& isset($_POST['manage']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //Comme il y a un nouveau formulaire on régénère un nouveau token
        $_SESSION['token'] = uniqid();

        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['adminNewsId'])
        && $_POST['adminNewsId'] >= 1)
        {
            //On récupère l'id du formulaire précédent
            $adminNewsId = htmlspecialchars(addslashes($_POST['adminNewsId']));

            //On fait une requête pour vérifier si la news choisie existe
            $newsQuery = $bdd->prepare('SELECT * FROM car_news 
            WHERE newsId = ?');
            $newsQuery->execute([$adminNewsId]);
            $newsRow = $newsQuery->rowCount();

            //Si la news existe
            if ($newsRow == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($news = $newsQuery->fetch())
                {
                    //On récupère les informations de la news
                    $adminNewsTitle = stripslashes($news['newsTitle']);
                }
                ?>
                
                Que souhaitez-vous faire de la news <em><?php echo $adminNewsTitle ?></em> ?

                <hr>
                    
                <form method="POST" action="editNews.php">
                    <input type="hidden" class="btn btn-default form-control" name="adminNewsId" value="<?php echo $adminNewsId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier la news">
                </form>
                
                <hr>

                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                
                <?php
            }
            //Si la news n'exite pas
            else
            {
                echo "Erreur : News indisponible";
            }
            $newsQuery->closeCursor();
        }
        //Si tous les champs numérique ne contiennent pas un nombre
        else
        {
            echo "Erreur : Les champs de type numérique ne peuvent contenir qu'un nombre entier";
        }
    }
    //Si le token de sécurité n'est pas correct
    else
    {
        echo "Erreur : Impossible de valider le formulaire, veuillez réessayer";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");