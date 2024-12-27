<!DOCTYPE html>
<html>

<head>
    <title>Mes Recettes</title>
    <link href="../styles/styleRecettes.css" rel="stylesheet">
	<?php include("../données/header.php")?>
</head>

<body>
<?php
    require("../données/fonctions.php");
    session_start();
    if(!isset($_SESSION['username'])){
        $recettes = $_SESSION['favorisVisiteur'];
        if (!empty($_SESSION['favorisVisiteur'])){
            echo "<br>";
            echo "<strong>Recettes Favories:<br></strong>";
            echo "<br>";
            affichageRecettes($recettes);
        }else{
            echo "<strong>Aucune Recettes Favories</strong>";
        }
    }else{
        $username = $_SESSION['username'];
        $recettes = recettesFromFavori($username);
        if (!empty($recettes)){
            echo "<br>";
            echo "<strong>Recettes Favories:<br></strong>";
            echo "<br>";
            affichageRecettes($recettes);
        }else{
            echo "<strong>Aucune Recettes Favories</strong>";
        }
    }
?>


</body>