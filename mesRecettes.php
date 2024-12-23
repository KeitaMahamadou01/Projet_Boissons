<!DOCTYPE html>
<html>

<head>
    <title>Mes Recettes</title>
	<meta charset="utf-8" />
    <link href="style.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
    <script src="script.js"></script>
</head>

<body>
<?php
    require("affichage.php");
    $recettes = recettesFromFavori("test");
    if (!empty($recettes)){
        echo "<strong>Recettes Favories:<br></strong>";
        affichageRecettes($recettes);
    }else{
        echo "<strong>Aucune Recettes Favories</strong>";
    }
?>


</body>