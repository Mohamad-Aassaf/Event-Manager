<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM Ingresso WHERE id_ingresso = ?");
        $stmt->execute([$id]);
        header("Location: listar.php?success=deleted");
        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: listar.php?error=delete_failed");
        exit;
    }
}

header("Location: listar.php");
exit;
?>
