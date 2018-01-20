<?php
require_once("../../kernel/config.php");

//Si le personnage est dans un lieu
if ($characterPlaceId >= 1)
{
    //On fait une recherche dans la base de donnée pour récupérer le lieu du personnage
    $placeQuery = $bdd->prepare("SELECT * FROM car_places 
    WHERE placeId = ?");
    $placeQuery->execute([$characterPlaceId]);

    //On fait une boucle sur les résultats
    while ($place = $placeQuery->fetch())
    {
        //On récupère les informations du lieu
        $placeId = stripslashes($place['placeId']);
        $placePicture = stripslashes($place['placePicture']);
        $placeName = stripslashes($place['placeName']);
        $placeDescription = stripslashes(nl2br($place['placeDescription']));
        $placePriceInn = stripslashes($place['placePriceInn']);
    }
    $placeQuery->closeCursor();
}
?>