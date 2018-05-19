<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }
?>
    
<form method="POST" action="manageEquipmentsPicture.php">
    Images d'équipements : <select name="pictureFile" class="form-control">
            
        <?php
        $dir = '../../img/equipments/';
        //On ouvre le dossier
        if ($dh = opendir($dir)) 
        {
            //On fait une boucle sur chaque fichier
            while (($file = readdir($dh)) !== false) 
            {
                //On affiche chaque fichier dans un menu option
                if($file != '.' && $file != '..') 
                {
                    ?>
                    <option value="<?php echo $file ?>"><?php echo $file; ?></option>
                    <?php
                }
            }
            // on ferme la connection
            closedir($dh);
        }
        ?>

    </select>
    <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer l'image">
</form>

<hr>

<form method="POST" action="addEquipmentsPicture.php" enctype="multipart/form-data">
    <!-- On limite le fichier à 1000Ko -->
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
    <input type="file" name="picture">
    <input name="upload" class="btn btn-default form-control" type="submit" value="Envoyer le fichier">
</form>

<?php require_once("../html/footer.php");