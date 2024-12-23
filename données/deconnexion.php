<?php
session_start();
unset($_SESSION['username']);
header("Location: ../pages/accueil.php");
exit();
?>