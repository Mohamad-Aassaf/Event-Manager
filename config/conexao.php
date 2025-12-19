<?php
$host = "localhost";
$user = "root";
$pass = "root";
$dbname = "eventos_gerenciador";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro: " . $e->getMessage();
    exit;
}
?>
