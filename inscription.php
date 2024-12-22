<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
    <link rel="stylesheet" href="styleInscription.css">

</head>
<body>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmPasswordField = document.getElementById('confirm_password');
            const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
            confirmPasswordField.type = type;
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    });
</script>

<div class="container">
    <h2>Formulaire d'inscription</h2>

    <?php
    include 'affichage.php';

    // Initialisation des variables
    $nom = $prenom = $username = $adresse = $code_postal = $ville  = $sexe = $email = $num_telephone = "";
    $dateNaissance=null;
    $errors = [];


    // Gestion de la soumission du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $username = htmlspecialchars(trim($_POST['username']));
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $adresse = htmlspecialchars(trim($_POST['adresse']));
        $dateNaissance = htmlspecialchars(trim($_POST['dateNaissance']));
        $sexe = trim($_POST['sexe']);
        $email = trim($_POST['adresse_mail']);
        $num_telephone = trim($_POST['num_telephone']);
        $code_postal =htmlspecialchars(trim($_POST['code_postal']));
        $ville = htmlspecialchars(trim($_POST['ville']));

        // Vérifications
        if (empty($username) || empty($password) || empty($confirm_password)) {
            $errors[] = "Tous les champs obligatoires doivent être remplis.";
        } elseif (nomUtilisateurExist($username)) {
            $errors[] = "Ce nom d'utilisateur existe déjà.";
        } elseif ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }elseif (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        } elseif (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = "Le mot de passe ne doit pas contenir d'espaces ou de caractères spéciaux.";
        }


        // Si aucune erreur, ajouter l'utilisateur
        if (empty($errors)) {
            $resultat = ajouterUtilisateur($nom, $prenom, $username, $password, $email, $num_telephone, $adresse,$code_postal , $ville, $dateNaissance, $sexe);
            if ($resultat) {
                echo "<p class='success-message'>Utilisateur ajouté avec succès. Bienvenue, " . htmlspecialchars($prenom) . " " . htmlspecialchars($nom) . ".</p>";
                // Redirection vers la page d'authentification après l'inscription
                header('Location: authentification.php');
                exit;
            } else {
                $errors[] = "Erreur lors de l'ajout de l'utilisateur.";
            }
        }
    }

    // Affichage des erreurs
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error-message'>$error</p>";
        }
    }
    ?>

    <!-- Formulaire -->
    <form action="" method="POST">
        <label for="sexe">Sexe :</label><br>
        <input type="radio" id="sexe_m" name="sexe" value="M" <?= ($sexe === 'M') ? 'checked' : '' ?>>
        <label for="sexe_m">M (Masculin)</label>

        <input type="radio" id="sexe_f" name="sexe" value="F" <?= ($sexe === 'F') ? 'checked' : '' ?>>
        <label for="sexe_f">F (Féminin)</label>

        <input type="radio" id="sexe_autre" name="sexe" value="A" <?= ($sexe === 'A') ? 'checked' : '' ?>>
        <label for="sexe_autre">Autre</label><br><br>

        <label for="nom">Nom :</label><br>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" ><br><br>

        <label for="prenom">Prénom :</label><br>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>" ><br><br>

        <label for="adresse_mail">Adresse mail :</label><br>
        <input type="text" id="adresse_mail" name="adresse_mail" value="<?= htmlspecialchars($email) ?>" ><br><br>



        <label for="num_telephone">Numéro de téléphone :</label><br>
        <input type="text" id="num_telephone" name="num_telephone" value="<?= htmlspecialchars($num_telephone) ?>" ><br><br>


        <label for="adresse">Adresse :</label><br>
        <div class="address-row">
            <input type="text" id="adresse" name="adresse" placeholder="Adresse" value="<?= htmlspecialchars($adresse) ?>">
            <input type="text" id="code_postal" name="code_postal" placeholder="Code postal" value="<?= htmlspecialchars($code_postal) ?>">
            <input type="text" id="ville" name="ville" placeholder="Ville" value="<?= htmlspecialchars($ville ) ?>">
        </div><br><br>

        <label for="dateNaissance">Date de naissance :</label><br>
        <input type="date" id="dateNaissance" name="dateNaissance" value="<?= htmlspecialchars($dateNaissance) ?>"><br><br>

        <label for="username">Nom d'utilisateur : (*) </label><br>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required><br><br>

        <label for="password">Mot de passe : (*) </label><br>
        <div class="password-container">
            <input type="password" id="password" name="password" required><br><br>
            <button type="button" id="togglePassword" aria-label="Afficher/masquer le mot de passe">👁️</button>
        </div>

        <label for="confirm_password">Confirmer le mot de passe : (*)</label><br>
        <div class="password-container">
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>
            <button type="button" id="toggleConfirmPassword" aria-label="Afficher/masquer le mot de passe">👁️</button>
        </div>
        <button type="submit">S'inscrire</button>
    </form>

    <p>Vous avez déjà un compte ? <a href="authentification.php">Se connecter</a></p>
</div>

</body>
</html>
