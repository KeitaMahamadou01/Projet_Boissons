<!DOCTYPE html>
<html>

<head>
    <title>Boissons</title>
    <?php require("../données/header.php")?>
</head>

<body>
    <?php require("../données/fonctions.php")?>

    <br>

    <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Rechercher une recette..." value="" />
            <button type="submit">Rechercher</button>
        </form>
        <div class="filter-container">
            <form method="POST" action="">
            <label for="filter-select">Filtrer par :</label>
            <select id="filter-select" name="filter" onchange="this.form.submit()">
                <option value="A-Z" <?php echo (isset($_POST['filter']) && $_POST['filter'] === "A-Z") ? 'selected' : ''; ?>>A-Z</option>
                <option value="Z-A" <?php echo (isset($_POST['filter']) && $_POST['filter'] === "Z-A") ? 'selected' : ''; ?>>Z-A</option>
                <option value="True_Photo" <?php echo (isset($_POST['filter']) && $_POST['filter'] === "True_Photo") ? 'selected' : ''; ?>>Avec photo</option>
                <option value="False_Photo" <?php echo (isset($_POST['filter']) && $_POST['filter'] === "False_Photo") ? 'selected' : ''; ?>>Sans photo</option>
            </select>
            </form>
        </div>
        </form>
    </div>
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
        $s = end($_SESSION['chemin']);
        echo "<nav class='nav_aliments'><ol>";
        foreach($_SESSION['chemin'] as $value){
            if($value != $s){
                echo "<li class='aliment'><a href='accueil.php?ing=$value'>$value</a></li>";
            }
        }
        echo "<li class='aliment'>$value</li>";
        echo "</ol></nav>";
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Mise à jour de la recherche si la valeur a changé
        if (isset($_POST['search']) && $_POST['search'] !== $_SESSION['search']) {
            $_SESSION['search'] = htmlspecialchars($_POST['search']);
        }
    }
    // Vérification et traitement des données envoyées par le formulaire
    $search = $_SESSION['search']; // Recherche mémorisée
    $selectedFilter = $_POST['filter'];

    $triAlp=0;
    $photo=0;
    if (!empty($selectedFilter)) {
        if($selectedFilter=='A-Z'){
            $triAlp=1;
        }else if($selectedFilter=='Z-A'){
            $triAlp=-1;
        }else if($selectedFilter=='True_Photo'){
            $photo=1;
        }else if($selectedFilter=='False_Photo'){
            $photo=-1;
        }

    }



        // Vous pouvez ajouter ici un traitement de la recherche
        // Par exemple, rechercher une recette dans une base de données
    if (!empty($search)) {
            echo "<strong>Vous avez recherché : $search</strong>";
            $recettes = rechercherRecette($search,$triAlp,$photo);
            if (!empty($recettes)) {
                affichageRecettes($recettes);
            }else{
                echo "<strong>Aucune recette ne correspond à votre recherche.</strong>";
            }
    }else{
        if ($ingredient == 'Aliment') {
            $recettes = allRecettes($triAlp,$photo);
            if (!empty($recettes)) {
                echo "<strong>Toutes les Recettes :</strong>";
                affichageRecettes($recettes);
            }
        } else {
            $recettes = recettesFromIngredient($ingredient,$triAlp,$photo);
            if (!empty($recettes)) {
                echo "<strong>Recettes avec " . $ingredient . " :</strong>";
                affichageRecettes($recettes);
            }
        }
    }
    ?>
    </div>
    </div>
</body>
</html>