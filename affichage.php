<?php
// Connexion à la base de données
$mysqli=mysqli_connect('127.0.0.1', 'root', '',"Boissons") or die("Erreur de connexion");


if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}
function recettesFromIngredient($ingredient) {
    global $mysqli; // Utilisation de la connexion MySQLi globale


    // Requête pour récupérer les titres des recettes contenant l'ingrédient
    $requete = "SELECT DISTINCT r.titre
        FROM recette r
        INNER JOIN ingredient i ON r.titre = i.nom_recette
        WHERE i.nom_ingredient = '" . $mysqli->real_escape_string($ingredient) . "'";

    // Exécuter la requête
    $resultat = mysqli_query($mysqli, $requete);

    if (!$resultat) {
        // Gérer les erreurs SQL
        echo "Erreur SQL : " . mysqli_error($mysqli) . "<br>";
        return [];
    }

    // Récupérer les titres dans un tableau
    $titres = [];
    while ($ligne = mysqli_fetch_assoc($resultat)) {
        $titres[] = $ligne['titre'];
    }

    // Libérer les ressources
    mysqli_free_result($resultat);

    return $titres;
}


function infoRecette($titre){
    global $mysqli;
    //echo "<div class='recette'>";

    // Afficher le titre
    echo "<p><strong>" . htmlspecialchars($titre) . "</strong></p>";

    // Afficher l'image si elle existe
    $sqlTitre = "SELECT * FROM photo WHERE nom_recette = '" . $mysqli->real_escape_string($titre) . "'";
    $photo = $mysqli->query($sqlTitre);
    if ($photo->num_rows > 0) {
        while ($photo1 = $photo->fetch_assoc()) {
            echo "<p><img src='" . htmlspecialchars($photo1["chemin_photo"]) . "' alt='" . htmlspecialchars($titre) . "' style='max-width: 300px;'></p>";
        }
    }

    // Afficher les ingrédients
    $sqlIngredient = "SELECT * FROM ingredient WHERE nom_recette = '" . $mysqli->real_escape_string($titre) . "'";
    $ingredient = $mysqli->query($sqlIngredient);

    if ($ingredient->num_rows > 0) {
        echo "<p><strong>Ingrédients :</strong><br>";
        $ingredientsList = [];
        while ($ingredient1 = $ingredient->fetch_assoc()) {
            $ingredientsList[] = htmlspecialchars($ingredient1['nom_ingredient']);
        }
        echo implode("<br>", $ingredientsList);
        echo "</p>";
    } else {
        echo "<p>Aucun ingrédient trouvé</p>";
    }

    // Afficher la préparation
    $sqlPreparation = "SELECT * FROM recette WHERE titre = '" . $mysqli->real_escape_string($titre) . "'";
    $preparation = $mysqli->query($sqlPreparation);
    if($preparation->num_rows > 0){
        while($p = $preparation->fetch_assoc()){
            echo "<p><strong>Préparation :</strong><br>" . htmlspecialchars($p['preparation']) . "</p>";
        }
    }

    //echo "</div><hr>";
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
        $sousCategList = [];
        while ($sousCateg1 = $sousCateg->fetch_assoc()) {
            $sousCategList[] = htmlspecialchars($sousCateg1['nom_sous_categ']);
        }
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
?>
