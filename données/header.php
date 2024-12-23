<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <link href="../styles/style.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../javascript/script.js"></script>
    <div class="entete">
        <h1>Recettes</h1>
        <div class=en_tete>
            <p>
                <a href="accueil.php">Accueil<a>
                <a href="mesRecettes.php">Mes Recettes Préférées<a>
            <p>
        </div>
        <?php
            session_start();
            if(!isset($_SESSION['username'])){
                echo "<button class='connexion' type='button' onclick=\"location.href = 'authentification.php'\">Connexion</button>";
            }else{
                $username = $_SESSION['username'];
                echo "<button class='connexion' type='button' onclick=\"location.href = 'utilisateur.php'\">$username</button>";
            }
        ?>
        
    <div>
</head>
<body>