<!DOCTYPE html>
<html>

<head>
    <title>Mes Recettes</title>
	<?php include("../données/header.php")?>
</head>

<body>
<?php
    require("../données/fonctions.php");
    session_start();
    if(!isset($_SESSION['username'])){
        $recettes = $_SESSION['favorisVisiteur'];
        if (!empty($_SESSION['favorisVisiteur'])){
            echo "<strong>Recettes Favories:<br></strong>";
            affichageRecettes($recettes);
        }else{
            echo "<strong>Aucune Recettes Favories</strong>";
        }
    }else{
        $username = $_SESSION['username'];
        $recettes = recettesFromFavori($username);
        if (!empty($recettes)){
            echo "<strong>Recettes Favories:<br></strong>";
            affichageRecettes($recettes);
        }else{
            echo "<strong>Aucune Recettes Favories</strong>";
        }
    }
?>


</body>