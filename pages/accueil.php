<!DOCTYPE html>
<html>

<head>
    <title>Boissons</title>
    <link href="../styles/styleAccueil.css" rel="stylesheet">
    <link href="../styles/styleRecettes.css" rel="stylesheet">
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
        //initialisation d'une liste d'aliments sélectionnés au début d'une session
        if(!isset($_SESSION['chemin'])){
            $_SESSION['chemin'] = [];
            $_SESSION['chemin'][] = "Aliment";
        }

        //ajout de l'élément sélectionné dans le select à liste d'aliments grâce à la méthode POST
        if(isset($_POST['Aliments']) && $_POST['Aliments'] != 'retour' && $_SESSION['chemin'][count($_SESSION['chemin']) - 1] != $_POST['Aliments']){
            $_SESSION['chemin'][] = $_POST['Aliments'];
        }

        //si on a cliqué sur un lien de la navigation aliment qui est dans $_GET['ing']
        //on supprime tous les éléments dans la liste d'aliments après celui-ci
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

        //si on a sélectionné retour dans le select on supprime le dernier élément de la liste d'aliments
        if(count($_SESSION['chemin']) > 1 && $_POST['Aliments'] == 'retour'){
            array_pop($_SESSION['chemin']);
        }

        //création de la nav-bar du chemin des aliments sélectionnés
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

    //On affiche les aliments par rapport à la recherche effectué
    //sinon on affiche les aliments par rapport au dernier choisi
    //On prends aussi en compte le filtre
    if (!empty($search)) {
            echo "<strong>Vous avez recherché : $search</strong>";
            $recettes = rechercherRecette($search,$triAlp,$photo);
            if (!empty($recettes)) {
                affichageRecettes($recettes);
            }else{
                echo "<strong>Aucune recette ne correspond à votre recherche.</strong>";
            }
    }else{
        //On affiche tout les aliments si la navigation contient seulement 'Aliment'
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