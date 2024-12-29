<?php
header('Content-Type: application/json');
require("fonctions.php");
session_start();
$recette = $_POST['recette'];
$utilisateur = $_POST['utilisateur'];
$etat = filter_var($_POST['etat'], FILTER_VALIDATE_BOOLEAN);
    
if($utilisateur != "visiteur"){
    if ($etat) {
        $result = ajouterFavori($recette, $utilisateur);
    } else {
        $result = enleverFavori($recette, $utilisateur);
    }
}else{
    if ($etat) {
        $_SESSION['favorisVisiteur'][] = $recette;
    } else {
        unset($_SESSION['favorisVisiteur'][array_search($recette, $_SESSION['favorisVisiteur'])]);
    }
    $result = true;
}

if ($result) {
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>