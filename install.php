<?php
include 'Donnees.inc.php'; // Ou require 'fichier1.php'


function query($link,$requete)
{
    $resultat=mysqli_query($link,$requete) or die("$requete : ".mysqli_error($link));

    return($resultat);
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
		CREATE TABLE super_categ (nom_hierarchie VARCHAR(255) ,nom_super_categ VARCHAR(255),
		                        PRIMARY KEY(nom_hierarchie,nom_super_categ),
		                        FOREIGN KEY (nom_hierarchie) REFERENCES hierarchie(nom));                      
		".insererRecette($mysqli ,$Recettes).insererIngredient($mysqli,$Recettes).insererHierarchie($mysqli,$Hierarchie).insererSous_categ($mysqli,$Hierarchie).insererSuper_categ($mysqli,$Hierarchie).insererSuper_categ($mysqli,$Hierarchie);
//echo insererIngredient($mysqli,$Recettes);
foreach(explode(';',$Sql) as $Requete){
    if(!empty($Requete)){
        query($mysqli,$Requete);
    }
}


mysqli_close($mysqli);