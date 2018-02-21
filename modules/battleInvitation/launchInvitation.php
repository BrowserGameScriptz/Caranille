<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['battleInvitationCharacterId'])
&& isset($_POST['open']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['battleInvitationCharacterId'])
    && $_POST['battleInvitationCharacterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $battleInvitationCharacterId = htmlspecialchars(addslashes($_POST['battleInvitationCharacterId']));

        //On fait une requête pour vérifier si l'invitation de combnat choisit existe
        $battleInvitationQuery = $bdd->prepare('SELECT * FROM car_battles_invitations, car_battles_invitations_characters, car_monsters
		WHERE battleInvitationId = battleInvitationCharacterBattleInvitationId
		AND battleInvitationMonsterId = monsterId
		AND battleInvitationCharacterId = ?
		AND battleInvitationCharacterCharacterId = ?');
        $battleInvitationQuery->execute([$battleInvitationCharacterId, $characterId]);
        $battleInvitationRow = $battleInvitationQuery->rowCount();

        //Si l'invitation de combat existe
        if ($battleInvitationRow == 1) 
        {
            //On fait une recherche dans la base de donnée de toutes les lieux
            while ($battleInvitation = $battleInvitationQuery->fetch())
            {
                $battleInvitationName = stripslashes($battleInvitation['battleInvitationName']);
                $battleInvitationMonsterName = stripslashes($battleInvitation['monsterName']);
            }
            ?>
            
            ATTENTION : Vous êtes sur le point de lancer un combat contre <?php echo $battleInvitationMonsterName ?><br /><br />
            
            Si vous perdez ou fuyez le combat vous ne pourrez pas le recommencer à moins de recevoir une nouvelle invitation<br /><br />
            
            Que souhaitez-vous faire ?
            
            <hr>
                
            <form method="POST" action="launchInvitationEnd.php">
                <input type="hidden" class="btn btn-default form-control" name="battleInvitationCharacterId" value="<?php echo $battleInvitationCharacterId ?>">
                <input type="submit" class="btn btn-default form-control" name="launch" value="Lancer le combat">
            </form>
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si l'invitation de combat n'exite pas
        else
        {
            echo "Erreur : Cette invitation de combat n'existe pas";
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

require_once("../../html/footer.php");