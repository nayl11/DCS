<?php
// Paramètres de connexion à la base MariaDB 
$host = 'localhost';
$db   = 'campus_it'; // Nom de la base spécifié dans le sujet [cite: 27]
$user = 'root';      // À confirmer avec l'équipe SISR
$pass = '';          // À confirmer avec l'équipe SISR

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}




?>