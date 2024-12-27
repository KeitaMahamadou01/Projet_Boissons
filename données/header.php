<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <link href="../styles/styleHeader.css" rel="stylesheet">
    <link href="../styles/style.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../javascript/script.js"></script>
</head>
<body>
    <div class="titre">
        <br>
        <h1>Recettes</h1>
        <br>
    </div>
    <nav class="entete">
        <ul class="pages">
            <li><a href="accueil.php" title="Retour à l'accueil">Accueil</a></li>
            <li><a href="mesRecettes.php" title="Voir mes recettes préférées">Mes Recettes Préférées</a></li>
        </ul>
        <div class="bouttons">
            <?php
                session_start();
                if (!isset($_SESSION['username'])) {
                    echo "<button class='connexion' type='button' onclick=\"location.href = 'authentification.php'\">Connexion</button>";
                } else {
                    $username = htmlspecialchars($_SESSION['username']);
                    echo "<button class='connexion' type='button' onclick=\"location.href = 'utilisateur.php'\">$username</button>";
                    echo "<button class='connexion' type='button' onclick=\"location.href = '../données/deconnexion.php'\">Déconnexion</button>";
                }
            ?>
        </div>
    </nav>
</body>
</html>