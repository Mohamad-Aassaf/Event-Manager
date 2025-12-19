<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    try {
        
        $check = $conn->prepare("SELECT COUNT(*) FROM Evento WHERE id_local = ?");
        $check->execute([$id]);
        $count = (int) $check->fetchColumn();

        if ($count > 0) {
            
            header("Location: listar.php?error=has_events");
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM Local WHERE id_local = ?");
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
