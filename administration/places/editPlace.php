<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminPlaceId'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminPlaceId'])
    && $_POST['adminPlaceId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminPlaceId = htmlspecialchars(addslashes($_POST['adminPlaceId']));

        //On fait une requête pour vérifier si le lieu choisit existe
        $placeQuery = $bdd->prepare('SELECT * FROM car_places 
        WHERE placeId = ?');
        $placeQuery->execute([$adminPlaceId]);
        $placeRow = $placeQuery->rowCount();

        //Si le lieu existe
        if ($placeRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($place = $placeQuery->fetch())
            {
                //On récupère les informations du lieu
                $adminplacePicture = stripslashes($place['placePicture']);
                $adminplaceName = stripslashes($place['placeName']);
                $adminplaceDescription = stripslashes($place['placeDescription']);
                $adminplacePriceInn = stripslashes($place['placePriceInn']);
                $adminplaceChapter = stripslashes($place['placeChapter']);
            }
            ?>

            <p><img src="<?php echo $adminplacePicture ?>" height="100" width="100"></p>

            <p>Informations du lieu</p>
            
            <form method="POST" action="editPlaceEnd.php">
                Image : <input type="text" name="adminplacePicture" class="form-control" placeholder="Image" value="<?php echo $adminplacePicture ?>" required>
                Nom : <input type="text" name="adminplaceName" class="form-control" placeholder="Nom" value="<?php echo $adminplaceName ?>" required>
                Description : <br> <textarea class="form-control" name="adminplaceDescription" id="adminplaceDescription" rows="3"><?php echo $adminplaceDescription; ?></textarea>
                Prix de l'auberge : <input type="number" name="adminplacePriceInn" class="form-control" placeholder="Prix de l'auberge" value="<?php echo $adminplacePriceInn ?>" required>
                lieu disponible au chapitre : <input type="number" name="adminplaceChapter" class="form-control" placeholder="lieu disponible au chapitre" value="<?php echo $adminplaceChapter ?>" required>
                <input type="hidden" name="adminPlaceId" value="<?php echo $adminPlaceId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>
            
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le lieu n'exite pas
        else
        {
            echo "Erreur : Cette lieu n'existe pas";
        }
        $placeQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur : Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");