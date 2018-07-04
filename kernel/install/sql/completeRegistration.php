<?php require_once("../../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['accountPseudo']) 
&& isset($_POST['accountPassword'])
&& isset($_POST['accountPasswordConfirm'])
&& isset($_POST['accountEmail'])
&& isset($_POST['characterName'])
&& isset($_POST['characterLocation'])
&& isset($_POST['characterCode'])
&& isset($_POST['token'])
&& isset($_POST['register']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;
        
        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['characterCode'])
        && $_POST['characterCode'] >= 0
        && $_POST['characterCode'] <= 1)
        {
            //On récupère les valeurs du formulaire dans une variable
            $accountPseudo = htmlspecialchars(addslashes($_POST['accountPseudo']));
            $accountPassword = sha1(htmlspecialchars(addslashes($_POST['accountPassword'])));
            $accountPasswordConfirm = sha1(htmlspecialchars(addslashes($_POST['accountPasswordConfirm'])));
            $accountEmail = htmlspecialchars(addslashes($_POST['accountEmail']));
            $characterName = htmlspecialchars(addslashes($_POST['characterName']));
            $characterLocation = htmlspecialchars(addslashes($_POST['characterLocation']));
            $characterCode = htmlspecialchars(addslashes($_POST['characterCode']));
    
            //On vérifie si les deux mots de passes sont identiques
            if ($accountPassword == $accountPasswordConfirm) 
            {
                //On fait une requête pour vérifier si le pseudo est déjà utilisé
                $pseudoQuery = $bdd->prepare('SELECT * FROM pokemongo_accounts 
                WHERE accountPseudo= ?');
                $pseudoQuery->execute([$accountPseudo]);
                $pseudoRow = $pseudoQuery->rowCount();
                $pseudoQuery->closeCursor();
    
                //Si le pseudo est disponible
                if ($pseudoRow == 0) 
                {
                    //On fait une requête pour vérifier si le nom du personnage est déjà utilisé
                    $characterQuery = $bdd->prepare('SELECT * FROM pokemongo_characters 
                    WHERE characterName= ?');
                    $characterQuery->execute([$characterName]);
                    $characterRow = $characterQuery->rowCount();
                    $characterQuery->closeCursor();
    
                    //Si le personnage existe
                    if ($characterRow == 0) 
                    {
                        //On fait une requête pour vérifier si le code ami est déjà dans la liste
                        $findCodeQuery = $bdd->prepare("SELECT * FROM pokemongo_characters 
                        WHERE characterCode = ?");
                        $findCodeQuery->execute([$characterCode]);
                        $findCodeRow = $findCodeQuery->rowCount();
                        $findCodeQuery->closeCursor();
                    
                        //Si le code est pas utilisé
                        if ($findCodeRow == 0) 
                        {
                            //Variables pour la création d'un compte
                            $date = date('Y-m-d H:i:s');
                            $ip = $_SERVER['REMOTE_ADDR'];
                            $timeStamp = strtotime("now");
    
                            /*
                            Add account model
                            NULL, //accountId
                            :accountPseudo, //accountPseudo
                            :accountPassword, //accountPassword
                            :accountEmail, //accountEmail
                            '0', //accountAccess
                            '0', //accountStatus
                            'None', //accountReason
                            :accountLastAction, //accountLastAction
                            :accountLastConnection, //accountLastConnection
                            :accountIp, //accountLastIp
                            */
    
                            //Insertion du compte dans la base de donnée
                            $addAccount = $bdd->prepare("INSERT INTO pokemongo_accounts VALUES(
                            NULL,
                            :accountPseudo,
                            :accountPassword,
                            :accountEmail,
                            '0',
                            '0',
                            'None',
                            :accountLastAction,
                            :accountLastConnection,
                            :accountIp)");
                            $addAccount->execute([
                            'accountPseudo' => $accountPseudo,
                            'accountPassword' => $accountPassword,
                            'accountEmail' => $accountEmail,
                            'accountLastAction' => $date,
                            'accountLastConnection' => $date,
                            'accountIp' => $ip]);
                            $addAccount->closeCursor();
    
                            //On recherche l'id du personnage
                            $accountQuery = $bdd->prepare("SELECT * FROM pokemongo_accounts 
                            WHERE accountPseudo = ?");
                            $accountQuery->execute([$accountPseudo]);
    
                            while ($account = $accountQuery->fetch())
                            {
                                //On Stock l'id du compte
                                $accountId = $account['accountId'];
                            }
                            $accountQuery->closeCursor();
    
                            /*
                            Add character model
                            NULL, //characterId
                            :accountId, //characterAccountId
                            '0', //characterGuildId
                            :characterRaceId, //characterRaceId
                            '0', //characterPlaceId
                            'http://localhost/character.png', //characterPicture
                            :characterName, //characterName
                            '1', //characterLevel
                            :characterSex, //characterSex
                            '100', //characterHpMin
                            '100', //characterHpMax
                            '0', //characterHpSkillPoints
                            '0', //characterHpParchment
                            '0', //characterHpEquipments
                            '0', //characterHpGuild
                            '100', //characterHpTotal
                            '10', //characterMpMin
                            '10', //characterMpMax
                            '0', //characterMpSkillPoints
                            '0', //characterMpParchment
                            '0', //characterMpEquipments
                            '0', //characterMpGuild
                            '10', //characterMpTotal
                            '1', //characterStrength
                            '0', //characterStrengthSkillPoints
                            '0', //characterStrengthParchment
                            '0', //characterStrengthEquipments
                            '0', //characterStrengthGuild
                            '1', //characterStrengthTotal
                            '1', //characterMagic
                            '0', //characterMagicSkillPoints
                            '0', //characterMagicParchment
                            '0', //characterMagicEquipments
                            '0', //characterMagicGuild
                            '1', //characterMagicTotal
                            '0', //characterAgility
                            '0', //characterAgilitySkillPoints
                            '0', //characterAgilityParchment
                            '0', //characterAgilityEquipments
                            '0', //characterAgilityGuild
                            '0', //characterAgilityTotal
                            '0', //characterDefense
                            '0', //characterDefenseSkillPoints
                            '0', //characterDefenseParchment
                            '0', //characterDefenseEquipments
                            '0', //characterDefenseGuild
                            '0', //characterDefenseTotal
                            '0', //characterDefenseMagic
                            '0', //characterDefenseMagicSkillPoints
                            '0', //characterDefenseMagicParchment
                            '0', //characterDefenseMagicEquipments
                            '0', //characterDefenseMagicGuild
                            '0', //characterDefenseMagicTotal
                            '0', //characterWisdom
                            '0', //characterWisdomSkillPoints
                            '0', //characterWisdomParchment
                            '0', //characterWisdomEquipments
                            '0', //characterWisdomGuild
                            '0', //characterWisdomTotal
                            '0', //characterProspecting
                            '0', //characterProspectingSkillPoints
                            '0', //characterProspectingParchment
                            '0', //characterProspectingEquipments
                            '0', //characterProspectingGuild
                            '0', //characterProspectingTotal
                            '0', //characterArenaDefeate
                            '0', //characterArenaVictory
                            '0', //characterExperience
                            '0', //characterExperienceTotal
                            '0', //characterSkillPoints
                            '0', //characterGold
                            '1', //characterChapter
                            '0', //characterOnBattle
                            '1' //characterEnable
                            */
    
                            $addCharacter = $bdd->prepare("INSERT INTO pokemongo_characters VALUES(
                            NULL,
                            :accountId,
                            :characterName,
                            :characterLocation,
                            :characterCode");
                            $addCharacter->execute([
                            'accountId' => $accountId,
                            'characterName' => $characterName,
                            'characterLocation' => $characterLocation,
                            'characterCode' => $characterCode]);
                            $addCharacter->closeCursor();
                            ?>

                            Compte crée

                            <hr>

                            <form method="POST" action="../../index.php">
                                <input type="submit" name="continue" class="btn btn-default form-control" value="Continuer">
                            </form>
                                
                            <?php
                        }
                        //Si la classe choisie n'existe pas
                        else
                        {
                            echo "La classe choisit n'existe pas";
                        }
                        $raceQuery->closeCursor();  
                    }
                    //Si le nom du personnage a déjà été utilisé
                    else
                    {
                        echo "Ce nom de personnage est déjà utilisé";
                    }
                    $characterQuery->closeCursor();
                }
                //Si le pseudo est déjà utilisé
                else 
                {
                    echo "Le pseudo est déjà utilisé";
                }
                $pseudoQuery->closeCursor();   
            }
            //Si les deux mots de passe ne sont pas identique
            else 
            {
                echo "Les deux mots de passe ne sont pas identiques";
            }
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
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>