<!DOCTYPE html>
<html>

<head>
    <title>Boissons</title>
	<meta charset="utf-8" />
</head>

<body>
    <?php require("affichage.php")?>
    <h1>Aliments</h1>
    <form method="POST" action="">
        <select name="Aliments" size="5" onchange="this.form.submit()">
            <option value="0"></option>
            <?php
            if ($hierarchie->num_rows > 0) {
                while ($row = $hierarchie->fetch_assoc()) {
                    $selected = (isset($_POST['Aliments']) && $_POST['Aliments'] == $row['nom']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['nom']) . "' $selected>" . htmlspecialchars($row['nom']) . "</option>";
                }
            }
            ?>
        </select>
        <select name="Super_categorie" size="3" onchange="this.form.submit()">
            <option value="0"></option>
            
            <?php
                if (isset($_POST['Aliments'])) {
                    $alimentChoisi = $_POST['Aliments']; // Récupère l'aliment sélectionné
                    $superCategories = sousCategorie($alimentChoisi); // Appelle la fonction pour obtenir les super-catégories

                    if (is_array($superCategories)) {
                        foreach ($superCategories as $value) {
                            // Vérifie si cette super-catégorie est sélectionnée
                            $selected = (isset($_POST['Super_categorie']) && $_POST['Super_categorie'] == $value) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($value) . "' $selected>" . htmlspecialchars($value) . "</option>";
                        }
                    }
                }
            ?>
        </select>
        <select name="Sous_categorie" size="10" onchange="this.form.submit()">
            <option value="0"></option>
            
            <?php
               $alimentChoisi1 = $_POST['Super_categorie'];
               $a1 = sousCategorie($alimentChoisi1);
               foreach($a1 as $value){
                   echo "<option value='" . $value ."'>" . $value . "</option>";
               }
            ?>
        </select>
    </form>
</body>
</html>