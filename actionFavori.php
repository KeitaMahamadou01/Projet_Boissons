<?php
header('Content-Type: application/json');
require("affichage.php");
$recette = $_POST['recette'];
$utilisateur = $_POST['utilisateur'];
$etat = filter_var($_POST['etat'], FILTER_VALIDATE_BOOLEAN);
    

if ($etat) {
    $result = ajouterFavori($recette, $utilisateur);
} else {
    $result = enleverFavori($recette, $utilisateur);
}

if ($result) {
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>