<?php
// Connexion à la base de données
$mysqli=mysqli_connect('127.0.0.1', 'root', '',"Boissons") or die("Erreur de connexion");


if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}
function infoRecette($mysqli,$row){
    echo "<div class='recette'>";

    // Afficher le titre
    echo "<p><strong>Titre :</strong> " . htmlspecialchars($row['titre']) . "</p>";

    // Afficher les ingrédients
    $sqlIngredient = "SELECT * FROM ingredient WHERE nom_recette = '" . $mysqli->real_escape_string($row['titre']) . "'";
    $ingredient = $mysqli->query($sqlIngredient);
    $sql = "SELECT * FROM photo WHERE nom_recette = '" . $mysqli->real_escape_string($row['titre']) . "'";
    $photo = $mysqli->query($sql);

    if ($ingredient->num_rows > 0) {
        echo "<p><strong>Une liste d'ingrédients :</strong> ";
        $ingredientsList = [];
        while ($ingredient1 = $ingredient->fetch_assoc()) {
            $ingredientsList[] = htmlspecialchars($ingredient1['nom_ingredient']);
        }
        echo implode(", ", $ingredientsList);
        echo "</p>";
    } else {
        echo "<p><strong>Une liste d'ingrédients :</strong> Aucun ingrédient trouvé</p>";
    }

    // Afficher la préparation
    echo "<p><strong>Sa préparation :</strong> " . htmlspecialchars($row['preparation']) . "</p>";

    // Afficher l'image si elle existe
    if ($photo->num_rows > 0) {
        while ($photo1 = $photo->fetch_assoc()) {
            echo "<p><strong>Une image :</strong> <img src='" . htmlspecialchars($photo1["chemin_photo"]) . "' alt='" . htmlspecialchars($row['titre']) . "' style='max-width: 300px;'></p>";
        }
    } else {
        echo "<p><strong>Une image :</strong> Aucune image disponible</p>";
    }

    echo "</div><hr>";
}
function superCategorie($nom)
{
    global $mysqli;
    $sqlsupercategorie = "SELECT * FROM super_categ WHERE nom_hierarchie = '" . $mysqli->real_escape_string($nom) . "'";
    
    $superCateg = $mysqli->query($sqlsupercategorie);
    if ($superCateg->num_rows > 0) {
        echo "<p><strong>Super catégorie :</strong> ";
        $superCategList = [];
        while ($superCateg1 = $superCateg->fetch_assoc()) {
            $superCategList[] = htmlspecialchars($superCateg1['nom_super_categ']);
        }
        echo implode(", ", $superCategList);
        echo "</p>";
        return $superCategList;
    }

}
function sousCategorie($nomhierarchie){
    global $mysqli;
    $sqlsouscategorie = "SELECT * FROM sous_categ WHERE nom_hierarchie = '" . $mysqli->real_escape_string($nomhierarchie) . "'";
    $sousCateg = $mysqli->query($sqlsouscategorie);
    if ($sousCateg->num_rows > 0) {
        echo "<p><strong>Sous catégorie :</strong> ";
        $sousCategList = [];
        while ($sousCateg1 = $sousCateg->fetch_assoc()) {
            $sousCategList[] = htmlspecialchars($sousCateg1['nom_sous_categ']);
        }
        echo implode(", ", $sousCategList);
        echo "</p>";
        return $sousCategList;
    }
}
function panier($mysqli,$nom_utilisateur)
{
    $sqlpanier = "SELECT * FROM panier WHERE utilisateur_id='" . $mysqli->real_escape_string($nom_utilisateur) . "'";
    $panier = $mysqli->query($sqlpanier);
    if ($panier->num_rows > 0) {
        echo "<p><strong>Votre panier est : :</strong> ";
        $panierList = [];
        while ($panier1 = $panier->fetch_assoc()) {
            $panierList[] = htmlspecialchars($panier1['nom_recette']);
        }
        echo implode(", ", $panierList);
        echo "</p>";
    }
}
function authentification($mysqli,$nom_utilisateur,$mot_de_passe){
    $sqlAuthent="SELECT * FROM authentification WHERE utilisateur_id='" . $mysqli->real_escape_string($nom_utilisateur) . "' and mot_de_passe='" . $mysqli->real_escape_string($mot_de_passe) . "'";
    $auth = $mysqli->query($sqlAuthent);
    if ($auth->num_rows > 0) {
        return true;
    }
    return false;
}
function ajouterUtilisateur($mysqli,$nom,$prenom,$nom_utilisateur,$mot_de_passe){
    $SqladdUser="INSERT INTO authentification VALUES('".mysqli_real_escape_string($nom)."','".mysqli_real_escape_string($prenom)."','".mysqli_real_escape_string($nom_utilisateur)."','".mysqli_real_escape_string($mot_de_passe)."')";
    if($mysqli->query($SqladdUser)){
        return true;
    }
    return false;
}


// Requête pour récupérer les recettes
$sql = "SELECT * FROM recette";
$recette = $mysqli->query($sql);
$sql = "SELECT * FROM hierarchie";
$hierarchie = $mysqli->query($sql);

/*if ($recette->num_rows > 0) {
    while ($row = $recette->fetch_assoc()) {
        infoRecette($mysqli,$row);
    }
} else {
    echo "Aucune recette trouvée.";
}

if ($hierarchie->num_rows > 0) {
    while ($row = $hierarchie->fetch_assoc()) {
        echo "<div class='Hierarchie'>";

        // Afficher le titre
        echo "<p><strong>Titre :</strong> " . htmlspecialchars($row['nom']) . "</p>";
        sousCategorie($mysqli,$row);
        superCategorie($mysqli,$row);
        echo "</div><hr>";
    }
} else {
    echo "Aucune recette trouvée.";
}*/
//$mysqli->close();
?>
