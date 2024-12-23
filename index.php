<!DOCTYPE html>
<html>

<head>
    <title>Boissons</title>
	<meta charset="utf-8" />
    <link href="style.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
    <script src="script.js"></script>
</head>

<body>
    <?php require("affichage.php")?>
    <div class="entete">
        <h1>Recettes</h1>
        <div class=en_tete><p><a href="mesRecettes.php">Mes Recettes Préférées<a><p></div>
        <button class="connexion" type="button" onclick="location.href = 'authentification.php'">Connexion</button>
    <div>
    <br>
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
        if($ingredient == 'Aliment'){
            $recettes = allRecettes();
            if (!empty($recettes)){
                echo "<strong>Toutes les Recettes :</strong>";
                affichageRecettes($recettes);
            }
        }else{
            $recettes = recettesFromIngredient($ingredient);
            if (!empty($recettes)){
                echo "<strong>Recettes avec " . $ingredient ." :</strong>";
                affichageRecettes($recettes);
            }
        }
    ?>
    </div>
    </div>
</body>
</html>