<!DOCTYPE html>
<html>

<head>
    <title>Utilisateur</title>
    <?php include("../données/header.php"); ?>
</head>
<body>
    <br>
    Vos informations :
    
    <?php
        require("../données/fonctions.php");
        session_start();
        $user = $_SESSION['username'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données soumises par le formulaire
            $nom = htmlspecialchars(trim($_POST['nom']));
            $prenom = htmlspecialchars(trim($_POST['prenom']));
            $adresse = htmlspecialchars(trim($_POST['adresse']));
            $dateNaissance = htmlspecialchars(trim($_POST['dateNaissance']));
            $sexe = trim($_POST['sexe']);
            $email = trim($_POST['adresse_mail']);
            $num_telephone = trim($_POST['num_telephone']);
            $code_postal =htmlspecialchars(trim($_POST['code_postal']));
            $ville = htmlspecialchars(trim($_POST['ville']));
            
            $donnees = array( 'nom' => $nom,
                              'prenom' => $prenom,
                              'adresse_mail' => $email,
                              'num_telephone' => $num_telephone,
                              'adresse' => $adresse,
                              'code_postal' => $code_postal,
                              'ville' => $ville,
                              'dateNaissance' => $dateNaissance,
                              'sexe' => $sexe,
            );

            
            if(modifDonneesUtilisateur($user,$donnees)){
                echo "<br>Informations sauvegardées";
            }
        }
    

        $donnees = donneesUtilisateur($user);
        


    ?>

    <!-- Formulaire -->
    <form action="" method="POST">
        <label for="sexe">Sexe :</label><br>
        <input type="radio" id="sexe_m" name="sexe" value="M" <?= ($donnees['sexe'] === 'M') ? 'checked' : '' ?>>
        <label for="sexe_m">M (Masculin)</label>

        <input type="radio" id="sexe_f" name="sexe" value="F" <?= ($donnees['sexe'] === 'F') ? 'checked' : '' ?>>
        <label for="sexe_f">F (Féminin)</label>

        <input type="radio" id="sexe_autre" name="sexe" value="A" <?= ($donnees['sexe'] === 'A') ? 'checked' : '' ?>>
        <label for="sexe_autre">Autre</label><br><br>

        <label for="nom">Nom :</label><br>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($donnees['nom']) ?>" ><br><br>

        <label for="prenom">Prénom :</label><br>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($donnees['prenom']) ?>" ><br><br>

        <label for="adresse_mail">Adresse mail :</label><br>
        <input type="text" id="adresse_mail" name="adresse_mail" value="<?= htmlspecialchars($donnees['adresse_mail']) ?>" ><br><br>



        <label for="num_telephone">Numéro de téléphone :</label><br>
        <input type="text" id="num_telephone" name="num_telephone" value="<?= htmlspecialchars($donnees['num_telephone']) ?>" ><br><br>


        <label for="adresse">Adresse :</label><br>
        <div class="address-row">
            <input type="text" id="adresse" name="adresse" placeholder="Adresse" value="<?= htmlspecialchars($donnees['adresse']) ?>">
            <input type="text" id="code_postal" name="code_postal" placeholder="Code postal" value="<?= htmlspecialchars($donnees['code_postal']) ?>">
            <input type="text" id="ville" name="ville" placeholder="Ville" value="<?= htmlspecialchars($donnees['ville']) ?>">
        </div><br><br>

        <label for="dateNaissance">Date de naissance :</label><br>
        <input type="date" id="dateNaissance" name="dateNaissance" value="<?= htmlspecialchars($donnees['dateNaissance']) ?>"><br><br>

        <button type="submit">Sauvegarder</button>
    </form>
</body>
</html>