<!DOCTYPE html>
<html>

<head>
    <title>Boissons</title>
    <?php require("../données/header.php")?>
</head>

<body>
    <?php require("../données/fonctions.php")?>

    <br>
    <form method="POST" action="">
        <input type="text" name="search" placeholder="Rechercher une recette..." value="<?php echo $search; ?>" />
        <button type="submit">Rechercher</button>
    </form>
    <br>
    <?php
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
            header("Location: accueil.php");
        }
        if(count($_SESSION['chemin']) > 1 && $_POST['Aliments'] == 'retour'){
            array_pop($_SESSION['chemin']);
        }
        $s = $_SESSION['chemin'][0];
        echo "<a href='accueil.php?ing=$s'>" . $s . "</a>";
        foreach($_SESSION['chemin'] as $value){
            if($value != $_SESSION['chemin'][0]){
                echo " -> <a href='accueil.php?ing=$value'>" . $value . "</a>";
            }
        }
        $ingredient = $_SESSION['chemin'][count($_SESSION['chemin']) - 1];
    ?>
    <div class='container'>
    <form method="POST" action="">
        <select name="Aliments" size="10" onchange="this.form.submit()" class="select">
            <option value="retour"><-</option>
            <?php
                $aliments = sousCategorie($ingredient);
                foreach ($aliments as $value) {
                    $selected = (isset($_POST['Aliments']) && $_POST['Aliments'] == $value) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($value) . "' $selected>" . htmlspecialchars($value) . "</option>";
                }
            ?>
        </select>
    </form>
    <div class="recettes-container">
    <?php
    // Vérification et traitement des données envoyées par le formulaire
    $search = '';
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $search = isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '';
        // Vous pouvez ajouter ici un traitement de la recherche
        // Par exemple, rechercher une recette dans une base de données
        if($search != '') {
            echo "<strong>Vous avez recherché : $search</strong>";
            $recettes = rechercherRecette($search);
            if (!empty($recettes)) {
                affichageRecettes($recettes);
            }else{
                echo "<strong>Aucune recette ne correspond à votre recherche.</strong>";
            }
        }else{
        if ($ingredient == 'Aliment') {
            $recettes = allRecettes();
            if (!empty($recettes)) {
                echo "<strong>Toutes les Recettes :</strong>";
                affichageRecettes($recettes);
            }
        } else {
            $recettes = recettesFromIngredient($ingredient);
            if (!empty($recettes)) {
                echo "<strong>Recettes avec " . $ingredient . " :</strong>";
                affichageRecettes($recettes);
            }
        }}
    }
    ?>
    </div>
    </div>
</body>
</html>