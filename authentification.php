<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link rel="stylesheet" href="styleAuthentification.css">
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
    <h2>Authentification</h2>

    <?php
    include 'affichage.php';

    // Initialisation des variables
    $username = "";
    $errors = [];

    // Gestion de la soumission du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données
        $username = htmlspecialchars(trim($_POST['username']));
        $password = trim($_POST['password']);

        // Vérifications
        if (empty($username) || empty($password)) {
            $errors[] = "Tous les champs doivent être remplis.";
        } else {
            // Vérification des informations d'identification
            if (!authentification($username, $password)) {
                $errors[] = "Nom d'utilisateur ou mot de passe incorrect.";
            } else {
                // Authentification réussie
                echo "<p class='success-message'>Connexion réussie. Bienvenue, " . htmlspecialchars($username) . ".</p>";
                // Vous pouvez ici rediriger l'utilisateur ou démarrer une session :
                // header('Location: tableau_de_bord.php');
                exit;
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

    <!-- Formulaire d'authentification -->
    <form action="" method="POST">
        <label for="username">Nom d'utilisateur :</label><br>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required><br>

        <label for="password">Mot de passe :</label><br>
        <div class="password-container">
            <input type="password" id="password" name="password" required><br>
            <button type="button" id="toggleConfirmPassword" aria-label="Afficher/masquer le mot de passe">👁️</button>
        </div>

        <button type="submit">Se connecter</button>
    </form>

    <!-- Lien pour créer un compte -->
    <p>Vous n'avez pas de compte ? <a href="inscription.php">Créer un compte</a></p>
</div>

</body>
</html>
