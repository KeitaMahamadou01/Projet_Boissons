<?php
// Connexion à la base de données
$mysqli=mysqli_connect('127.0.0.1', 'root', '',"Boissons") or die("Erreur de connexion");


if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}
function recettesFromIngredient($ingredient,$triAlp,$photo) {
    global $mysqli; // Utilisation de la connexion MySQLi globale


    // Requête pour récupérer les titres des recettes contenant l'ingrédient
    $requete=null;
    if($triAlp==-1){
        $requete = "SELECT DISTINCT r.titre
        FROM recette r
        INNER JOIN ingredient i ON r.titre = i.nom_recette
        WHERE i.nom_ingredient = '" . $ingredient . "'
            ORDER BY r.titre DESC";
    }else if($photo==1){
        $requete = "SELECT DISTINCT r.titre
        FROM recette r
        INNER JOIN ingredient i ON r.titre = i.nom_recette
        WHERE i.nom_ingredient = '" . $ingredient . "' and r.titre in(SELECT DISTINCT nom_recette
            FROM photo)";

    }else if($triAlp==-1){
        $requete = "SELECT DISTINCT r.titre
        FROM recette r
        INNER JOIN ingredient i ON r.titre = i.nom_recette
        WHERE i.nom_ingredient = '" . $ingredient . "' and r.titre not in(SELECT DISTINCT nom_recette
            FROM photo)";

    }else{
        $requete = "SELECT DISTINCT r.titre
        FROM recette r
        INNER JOIN ingredient i ON r.titre = i.nom_recette
        WHERE i.nom_ingredient = '" . $ingredient . "'";
    }
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


    $souscategs=sousCategorie($ingredient);
    if(!empty($souscategs)) {

        foreach ($souscategs as $souscateg) {
            $titres=array_merge($titres,recettesFromIngredient($souscateg,$triAlp,$photo));
        }
    }
    $titres = array_unique($titres);
    return $titres;

}

function allRecettes($triAlp,$photo) {
    global $mysqli; // Utilisation de la connexion MySQLi globale
    $requete=null;


    if($triAlp==-1){
            $requete = "SELECT DISTINCT r.titre
            FROM recette r
            ORDER BY r.titre DESC";
    }else if($photo==1){
            $requete = "SELECT DISTINCT r.titre
            FROM recette r
            where r.titre in(SELECT DISTINCT nom_recette
            FROM photo)";
    }else if($photo==-1){
            $requete = "SELECT DISTINCT r.titre
            FROM recette r
            where r.titre not in(SELECT DISTINCT nom_recette
            FROM photo)";

    }else{
        $requete = "SELECT DISTINCT r.titre
            FROM recette r
            ORDER BY r.titre ASC";
    }


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

    // Afficher le titre
    echo "<p><strong>" . htmlspecialchars($titre) . "</strong></p>";

    // Afficher l'image si elle existe
    $sqlTitre = "SELECT * FROM photo WHERE nom_recette = '" . $mysqli->real_escape_string($titre) . "'";
    $photo = $mysqli->query($sqlTitre);
    if ($photo->num_rows > 0) {
        while ($photo1 = $photo->fetch_assoc()) {
            echo "<p><img src='" . htmlspecialchars($photo1["chemin_photo"]) . "' alt='" . htmlspecialchars($titre) . "' style='max-width: 300px;'></p>";
        }
    }else{
        echo "<p><img src='" . htmlspecialchars('image.jpg') . "' alt='" . htmlspecialchars($titre) . "' style='max-width: 300px;'></p>";
    }

    // Afficher les ingrédients
    /*$sqlIngredient = "SELECT * FROM ingredient WHERE nom_recette = '" . $mysqli->real_escape_string($titre) . "'";
    $ingredient = $mysqli->query($sqlIngredient);

    if ($ingredient->num_rows > 0) {
        echo "<p><strong>Ingrédients :</strong>";
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
            echo "<p><strong>Préparation :</strong>" . htmlspecialchars($p['preparation']) . "</p>";
        }
    }*/

}

function affichageRecettes($recettes){
    echo "<div class='recettes'>";
    foreach($recettes as $recette){
        echo "<div class='recette'>";
        $recetteJson = htmlspecialchars(json_encode($recette), ENT_QUOTES, 'UTF-8');
        session_start();
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];
            $user = htmlspecialchars(json_encode($username), ENT_QUOTES, 'UTF-8');
            if(!estFavori($recette,$username)){
                echo "<button class='nonfavori' onClick='actionFavori($recetteJson,$user,true)'><img src='../données/heart-fill.svg' alt='favori'/></button>";
            }else{
                echo "<button class='favori' onClick='actionFavori($recetteJson,$user,false)'><img src='../données/heart-fill.svg' alt='favori'/></button>";
            }
        }else{
            $username = "visiteur";
            $user = htmlspecialchars(json_encode($username), ENT_QUOTES, 'UTF-8');
            if(isset($_SESSION['favorisVisiteur']) && !in_array($recette,$_SESSION['favorisVisiteur'])){
                echo "<button class='nonfavori' onClick='actionFavori($recetteJson,$user,true)'><img src='../données/heart-fill.svg' alt='favori'/></button>";
            }else{
                echo "<button class='favori' onClick='actionFavori($recetteJson,$user,false)'><img src='../données/heart-fill.svg' alt='favori'/></button>";
            }
        }
        infoRecette($recette);
        echo "</div>";
    }
    echo "</div>";
}

function superCategorie($nom)
{
    global $mysqli;
    $sqlsupercategorie = "SELECT * FROM super_categ WHERE nom_hierarchie = '" . $mysqli->real_escape_string($nom) . "'";
    
    $superCateg = $mysqli->query($sqlsupercategorie);
    if ($superCateg->num_rows > 0) {
        $superCategList = [];
        while ($superCateg1 = $superCateg->fetch_assoc()) {
            $superCategList[] = htmlspecialchars($superCateg1['nom_super_categ']);
        }
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
function authentification($nom_utilisateur,$mot_de_passe){
    global $mysqli;
    $sqlAuthent="SELECT mot_de_passe FROM authentification WHERE nom_utilisateur='" . $mysqli->real_escape_string($nom_utilisateur) . "'";
    $auth = $mysqli->query($sqlAuthent);
    if ($auth) {
        if($ligne = mysqli_fetch_assoc($auth)) {
            $hashed_password = $ligne['mot_de_passe'];
        }
    }else{
        echo "Erreur SQL : " . mysqli_error($mysqli) . "<br>";
        return false;
    }

    if (password_verify($mot_de_passe,$hashed_password)) {
        return true;
    }
    return false;
}
function ajouterUtilisateur($nom,$prenom,$nom_utilisateur,$mot_de_passe,$email,$num_telephone,$adresse,$code_postale,$ville,$dateNaissance,$sexe){
    global $mysqli;
    $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);
    $SqladdUser="INSERT INTO authentification VALUES('".$mysqli->real_escape_string($nom).
                                                    "','".$mysqli->real_escape_string($prenom).
                                                    "','".$mysqli->real_escape_string($nom_utilisateur).
                                                    "','".$mysqli->real_escape_string($hashed_password).
                                                    "','".$mysqli->real_escape_string($email).
                                                    "','".$mysqli->real_escape_string($num_telephone).
                                                    "','". $mysqli->real_escape_string($adresse) .
                                                    "','".$mysqli->real_escape_string($code_postale).
                                                    "','". $mysqli->real_escape_string($ville) .
                                                    "','".$mysqli->real_escape_string($dateNaissance).
                                                    "','". $mysqli->real_escape_string($sexe) . "')";
    if($mysqli->query($SqladdUser)){
        return true;
    }
    return false;
}
function nomUtilisateurExist($nom_utilisateur){
    global $mysqli;
    $sqlAuthent="SELECT * FROM authentification WHERE nom_utilisateur='" . $mysqli->real_escape_string($nom_utilisateur) . "'";
    $auth = $mysqli->query($sqlAuthent);
    if ($auth->num_rows > 0) {
        return true;
    }
    return false;
}


function ajouterFavori($nomRecette,$nom_utilisateur){
    global $mysqli;
    $SqladdFav="INSERT INTO panier (utilisateur_id, nom_recette) VALUES('".$mysqli->real_escape_string($nom_utilisateur).
                                            "','".$mysqli->real_escape_string($nomRecette). "')";
    
    if($mysqli->query($SqladdFav)){
        return true;
    }
    return false;
}
function enleverFavori($nomRecette,$nom_utilisateur){
    global $mysqli;
    $sqlRmFav = "DELETE FROM panier WHERE utilisateur_id='" . $mysqli->real_escape_string($nom_utilisateur) . "' and nom_recette='" . $mysqli->real_escape_string($nomRecette) . "'";
    if($mysqli->query($sqlRmFav)){
        return true;
    }
    return false;
}
function estFavori($nomRecette,$nom_utilisateur){
    global $mysqli;
    $sqlIsFav="SELECT 1 FROM panier WHERE utilisateur_id='" . $mysqli->real_escape_string($nom_utilisateur) . "' and nom_recette='" . $mysqli->real_escape_string($nomRecette) . "'";
    $fav = $mysqli->query($sqlIsFav);
    if ($fav->num_rows > 0) {
        return true;
    }
    return false;
}
function recettesFromFavori($nom_utilisateur) {
    global $mysqli; // Utilisation de la connexion MySQLi globale

    // Requête pour récupérer les titres des recettes contenant l'ingrédient
    $requete = "SELECT DISTINCT nom_recette FROM panier
        WHERE utilisateur_id='" . $mysqli->real_escape_string($nom_utilisateur) . "'";
        
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
        $titres[] = $ligne['nom_recette'];
    }

    // Libérer les ressources
    mysqli_free_result($resultat);

    return $titres;
}
function rechercherRecette($nomRecette,$triAlp,$photo) {
    global $mysqli;
    $requete=null;

        if($triAlp==-1){
            echo 'ouyi';
            $requete = "SELECT DISTINCT r.titre
            FROM recette r
            where upper(r.titre) like upper('%" . $mysqli->real_escape_string($nomRecette) . "%')
            ORDER BY r.titre DESC";

        }else if($photo==1){
            $requete = "SELECT DISTINCT nom_recette
            FROM photo
            where upper(r.titre) like upper('%" . $mysqli->real_escape_string($nomRecette) . "%')";
        }else if($photo==-1){
            $requete = "SELECT DISTINCT r.titre
            FROM recette r
            where upper(r.titre) like upper('%" . $mysqli->real_escape_string($nomRecette) . "%') and r.titre not in(SELECT DISTINCT nom_recette
            FROM photo)";

        }else {
        $requete = "SELECT DISTINCT r.titre
            FROM recette r
            where upper(r.titre) like upper('%" . $mysqli->real_escape_string($nomRecette) . "%')
            ORDER BY r.titre ASC";
    }
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

?>
