<!DOCTYPE html>
<html>

<head>
    <title>Boissons</title>
	<meta charset="utf-8" />
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php require("affichage.php")?>
    <h1>Recettes</h1>
    <br>
    <?php
        session_start();
        if(!isset($_SESSION['chemin']) || count($_SESSION['chemin']) == 0){
            $_SESSION['chemin'] = [];
            $_SESSION['chemin'][] = "Aliment";
        }
        if(isset($_POST['Aliments']) && $_POST['Aliments'] != 'retour' && $_SESSION['chemin'][count($_SESSION['chemin']) - 1] != $_POST['Aliments']){
            $_SESSION['chemin'][] = $_POST['Aliments'];
        }
        if(isset($_GET['ing'])){
            $b = false;
            foreach($_SESSION['chemin'] as $value){
                if($value == $_GET['ing']){
                    $b = true;
                }else{
                    if($b){
                        array_pop($_SESSION['chemin']);
                    }
                }
            }
            header("Location: index.php");
        }
        if(count($_SESSION['chemin']) > 1 && $_POST['Aliments'] == 'retour'){
            array_pop($_SESSION['chemin']);
        }
        $s = $_SESSION['chemin'][0];
        echo "<a href='index.php?ing=$s'>" . $s . "</a>";
        foreach($_SESSION['chemin'] as $value){
            if($value != $_SESSION['chemin'][0]){
                echo " -> <a href='index.php?ing=$value'>" . $value . "</a>";
            }
        }
        $ingredient = $_SESSION['chemin'][count($_SESSION['chemin']) - 1];
    ?>
    <div class='container'>
    <form method="POST" action="">
        <select name="Aliments" size="10" onchange="this.form.submit()" class="select">
            <option value="retour"><-</option>
            <?php
                $aliments = sousCategorie($ingredient); // Appelle la fonction pour obtenir les super-catégories
                foreach ($aliments as $value) {
                    // Vérifie si cette super-catégorie est sélectionnée
                    $selected = (isset($_POST['Aliments']) && $_POST['Aliments'] == $value) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($value) . "' $selected>" . htmlspecialchars($value) . "</option>";
                }
            ?>
        </select>
    </form>
    <div class="recettes-container">
    <?php
        if($ingredient == 'Aliment'){
            $recettes = allRecettes();
            if (!empty($recettes)){
                echo "<strong>Toutes les Recettes :</strong>";
                echo "<div class='recettes'>";
                foreach($recettes as $recette){
                    echo "<div class='recette'>";
                    infoRecette($recette);
                    echo "</div>";
                }
                echo "</div>";
            }
        }else{
            $recettes = recettesFromIngredient($ingredient);
            if (!empty($recettes)){
                echo "<strong>Recettes avec " . $ingredient ." :</strong>";
                echo "<div class='recettes'>";
                foreach($recettes as $recette){
                    echo "<div class='recette'>";
                    infoRecette($recette);
                    echo "</div>";
                }
                echo "</div>";
            }
        }
    ?>
    </div>
    </div>
</body>
</html>