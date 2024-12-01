<?php
include 'Donnees.inc.php'; // Ou require 'fichier1.php'


function query($link,$requete)
{
    if (!$resultat = mysqli_query($link, $requete)) {
        echo "Erreur SQL : " . mysqli_error($link) . "<br>";
        return false; // Continuer même en cas d'erreur
    }
    return $resultat;
}
function insererRecette($conn,$donnees){
    $sql="";
    foreach($donnees as $cle => $valeur){
        $titre = mysqli_real_escape_string($conn, $valeur["titre"]);
        $preparation = mysqli_real_escape_string($conn, $valeur["preparation"]);
        $sql.="INSERT INTO recette VALUES ('".$titre."','".str_replace(';','.',$preparation)."')";
        $sql.=";\n";
    }
    return $sql;
}
function insererIngredient($conn,$donnees){
    $sql="";
    foreach($donnees as $cle => $valeur){
        $quantite=explode('|',$valeur["ingredients"]);
        $indice=0;
        foreach ($valeur["index"] as $ingredient){
            $sql.="INSERT IGNORE INTO ingredient VALUES ('".mysqli_real_escape_string($conn,$valeur["titre"])."','".mysqli_real_escape_string($conn,str_replace(';','.',$ingredient))."','".mysqli_real_escape_string($conn,str_replace(';','.',$quantite[$indice]))."')";
            $indice++;
            $sql.=";\n";

        }
    }
    return $sql;
}
function insererHierarchie($conn,$donnees){
    $sql="";
    foreach($donnees as $cle => $valeur){
        $sql.="INSERT INTO hierarchie VALUES ('".mysqli_real_escape_string($conn,$cle)."')";
        $sql.=";\n";
    }
    return $sql;
}
function insererSous_categ($conn,$donnees){
    $sql="";
    foreach($donnees as $cle => $valeur){
        if(array_key_exists('sous-categorie',$donnees[$cle])){
            foreach($valeur["sous-categorie"] as $sousCategorie){
                $sql.="INSERT INTO sous_categ VALUES ('".mysqli_real_escape_string($conn,$cle)."','".mysqli_real_escape_string($conn,$sousCategorie)."')";
                $sql.=";\n";
            }

        }
    }
    return $sql;
}
function insererSuper_categ($conn,$donnees){
    $sql="";
    foreach($donnees as $cle => $valeur){

        if(array_key_exists('super-categorie',$donnees[$cle])){
            foreach($valeur["super-categorie"] as $superCategorie){

                $sql.="INSERT IGNORE INTO super_categ VALUES ('".mysqli_real_escape_string($conn,$cle)."','".mysqli_real_escape_string($conn,$superCategorie)."')";
                $sql.=";";

            }

        }
    }
    return $sql;
}
function insererPhoto($conn,$donnees){
    $dossier = 'Photos/';
    $sql="";
// Vérifier si le chemin est un dossier valide
    if (is_dir($dossier)) {
        // Ouvrir le dossier
        if ($handle = opendir($dossier)) {

            // Parcourir chaque élément du dossier
            while (false !== ($fichier = readdir($handle))) {
                // Ignorer les dossiers spéciaux "." et ".."
                if ($fichier !== '.' && $fichier !== '..' && (explode(".", $fichier)[1]=='jpg' || explode(".", $fichier)[1]=='png')) {
                    $nom_recette=str_replace("_"," ", explode(".", $fichier)[0]);
                    if($nom_recette=='Tipunch'){
                        $nom_recette='Ti\'punch';
                    }
                    $sql.="INSERT INTO photo VALUES ('".mysqli_real_escape_string($conn,$nom_recette)."','".$dossier.$fichier."')";
                    $sql.=";\n";
                }
            }

            // Fermer le gestionnaire de dossier
            closedir($handle);
        } else {
            echo "Impossible d'ouvrir le dossier.";
        }
    } else {
        echo "'$dossier' n'est pas un dossier valide.";
    }
    return $sql;
}

$mysqli=mysqli_connect('127.0.0.1', 'root', '') or die("Erreur de connexion");

$base="Boissons";

$Sql="
		DROP DATABASE IF EXISTS $base;
		CREATE DATABASE $base;
		USE $base;
		CREATE TABLE recette (titre VARCHAR(500) PRIMARY KEY,preparation VARCHAR(3024));
		CREATE TABLE ingredient (nom_recette VARCHAR(255) ,nom_ingredient VARCHAR(255) ,quantite VARCHAR(255),
		                        PRIMARY KEY(nom_recette,nom_ingredient),
		                        FOREIGN KEY (nom_recette) REFERENCES recette(titre));
		CREATE TABLE hierarchie (nom VARCHAR(255) PRIMARY KEY);
		CREATE TABLE sous_categ (nom_hierarchie VARCHAR(255),nom_sous_categ VARCHAR(255),
		                        PRIMARY KEY(nom_hierarchie,nom_sous_categ),
		                        FOREIGN KEY (nom_hierarchie) REFERENCES hierarchie(nom));
		CREATE TABLE super_categ (  nom_hierarchie VARCHAR(255) ,nom_super_categ VARCHAR(255),
		                            PRIMARY KEY(nom_hierarchie,nom_super_categ),
		                            FOREIGN KEY (nom_hierarchie) REFERENCES hierarchie(nom));  
		CREATE TABLE photo (nom_recette VARCHAR(255),chemin_photo VARCHAR(255),
		                    FOREIGN KEY (nom_recette) REFERENCES recette(titre));
		CREATE TABLE authentification ( nom VARCHAR(100) NOT NULL,
                                        prenom VARCHAR(100) NOT NULL,
                                        nom_utilisateur VARCHAR(100) PRIMARY KEY,
                                        mot_de_passe VARCHAR(255) NOT NULL,
                                        adresse_mail VARCHAR(255) NOT NULL,
                                        num_telephone VARCHAR(255) NOT NULL,
                                        adresse VARCHAR(500) ,
                                        dateNaissance DATE,
                                        sexe varchar(1)); 
        CREATE TABLE panier (   id INT AUTO_INCREMENT PRIMARY KEY,   
                                utilisateur_id VARCHAR(100) NOT NULL, 
                                nom_recette VARCHAR(500) NOT NULL, 
                                date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date et heure d'ajout
                                FOREIGN KEY (utilisateur_id) REFERENCES authentification(nom_utilisateur) ON DELETE CASCADE,
                                FOREIGN KEY (nom_recette) REFERENCES recette(titre) ON DELETE CASCADE );          
		".insererRecette($mysqli ,$Recettes).insererIngredient($mysqli,$Recettes).
        insererHierarchie($mysqli,$Hierarchie).insererSous_categ($mysqli,$Hierarchie).
        insererSuper_categ($mysqli,$Hierarchie).insererSuper_categ($mysqli,$Hierarchie).insererPhoto($mysqli,$Recettes);
//echo insererIngredient($mysqli,$Recettes);
foreach(explode(';',$Sql) as $Requete){
    $Requete = trim($Requete);
    if(!empty($Requete)){
        if (!query($mysqli, $Requete)) {
            echo "Erreur dans la requête : $Requete<br>";
        }
    }
}

mysqli_close($mysqli);