<!DOCTYPE html>
<html>

<head>
    <title>Mes Recettes</title>
	<?php include("../données/header.php")?>
</head>

<body>
<?php
    require("../données/fonctions.php");
    $recettes = recettesFromFavori("test");
    if (!empty($recettes)){
        echo "<strong>Recettes Favories:<br></strong>";
        affichageRecettes($recettes);
    }else{
        echo "<strong>Aucune Recettes Favories</strong>";
    }
?>


</body>