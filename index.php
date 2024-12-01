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
        if(!isset($_POST['Aliments'])){
            $_SESSION['chemin'] = [];
        }
        if(isset($_POST['Aliments']) && $_POST['Aliments'] != 'retour'){
            $_SESSION['chemin'][] = $_POST['Aliments'];
        }
        if(isset($_POST['Aliments']) && $_POST['Aliments'] == 'retour'){
            array_pop($_SESSION['chemin']);
        }
        if(count($_SESSION['chemin']) > 0){
            echo $_SESSION['chemin'][0];
            foreach($_SESSION['chemin'] as $value){
                if($value != $_SESSION['chemin'][0]){
                    echo " -> " . $value;
                }
            }
        }else{
            echo "<br>";
        }
    ?>
    <form method="POST" action="">
        <select name="Aliments" size="10" onchange="this.form.submit()" class="select">
            <option value="retour"><-</option>
            <?php
                if(!isset($_POST['Aliments']) || count($_SESSION['chemin']) == 0){
                    $aliments = sousCategorie("Aliment"); // Appelle la fonction pour obtenir les super-catégories
                    foreach ($aliments as $value) {
                        // Vérifie si cette super-catégorie est sélectionnée
                        $selected = (isset($_POST['Aliments']) && $_POST['Aliments'] == $value) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($value) . "' $selected>" . htmlspecialchars($value) . "</option>";
                    }
                }else{
                    $ingredient = $_SESSION['chemin'][count($_SESSION['chemin']) - 1];
                    $aliments = sousCategorie($ingredient); // Appelle la fonction pour obtenir les super-catégories
                    foreach ($aliments as $value) {
                        // Vérifie si cette super-catégorie est sélectionnée
                        $selected = (isset($_POST['Aliments']) && $_POST['Aliments'] == $value) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($value) . "' $selected>" . htmlspecialchars($value) . "</option>";
                    }
                }
            ?>
        </select>
    </form>
    <?php
        if(isset($ingredient)){
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
</body>
</html>