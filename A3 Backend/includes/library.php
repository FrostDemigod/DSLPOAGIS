<?php
// library.php

function connectDB() {
    $host = 'localhost';
    $dbname = 'dslpoa_gis'; 
    $user = 'postgres';   
    $pass = 'COIS4000'; // Replace with your actual DB password

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Could not connect to the database $dbname :" . $e->getMessage());
    }
}
?>
