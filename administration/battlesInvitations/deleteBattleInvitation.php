<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminBattleInvitationId'])
&& isset($_POST['delete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminBattleInvitationId'])
    && $_POST['adminBattleInvitationId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminBattleInvitationId = htmlspecialchars(addslashes($_POST['adminBattleInvitationId']));

        //On fait une requête pour vérifier si l'invitation de combat choisit existe
        $battleInvitationQuery = $bdd->prepare('SELECT * FROM car_battles_invitations 
        WHERE battleInvitationId = ?');
        $battleInvitationQuery->execute([$adminBattleInvitationId]);
        $battleInvitationRow = $battleInvitationQuery->rowCount();

        //Si l'invitation de combat existe
        if ($battleInvitationRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($battleInvitation = $battleInvitationQuery->fetch())
            {
                //On récupère les informations du compte
                $adminBattleInvitationName = stripslashes($battleInvitation['battleInvitationName']);
            }
            ?>
            
            <p>ATTENTION</p> 

            Vous êtes sur le point de supprimer l'invitation de combat <em><?php echo $adminBattleInvitationName ?></em>.<br />
            Confirmez-vous la suppression ?

            <hr>
                
            <form method="POST" action="deleteBattleInvitationEnd.php">
                <input type="hidden" class="btn btn-default form-control" name="adminBattleInvitationId" value="<?php echo $adminBattleInvitationId ?>">
                <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
            </form>
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si l'invitation de combat n'existe pas
        else
        {
            echo "Erreur : Cette invitation de combat n'existe pas";
        }
        $battleInvitationQuery->closeCursor();
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