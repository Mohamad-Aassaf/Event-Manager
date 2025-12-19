<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    try {
        $conn->beginTransaction();
        $delAssoc = $conn->prepare("DELETE FROM participante_evento WHERE id_participante = ?");
        $delAssoc->execute([$id]);
        $stmt = $conn->prepare("DELETE FROM Participante WHERE id_participante = ?");
        $stmt->execute([$id]);
        $conn->commit();
        header("Location: participantes_listar.php?success=deleted");
        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        if ($conn->inTransaction()) { $conn->rollBack(); }
        header("Location: participantes_listar.php?error=delete_failed");
        exit;
    }
}

header("Location: participantes_listar.php");
exit;
?>
