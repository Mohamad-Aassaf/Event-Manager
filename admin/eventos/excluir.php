<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    try {
        $conn->beginTransaction();
        $delIngressos = $conn->prepare("DELETE FROM Ingresso WHERE id_evento = ?");
        $delIngressos->execute([$id]);
        $delParticipantes = $conn->prepare("DELETE FROM participante_evento WHERE id_evento = ?");
        $delParticipantes->execute([$id]);
        $stmt = $conn->prepare("DELETE FROM Evento WHERE id_evento = ?");
        $stmt->execute([$id]);
        $conn->commit();
        header("Location: eventos_listar.php?success=deleted");
        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        if ($conn->inTransaction()) { $conn->rollBack(); }
        header("Location: eventos_listar.php?error=delete_failed");
        exit;
    }
}

header("Location: eventos_listar.php");
exit;
?>
