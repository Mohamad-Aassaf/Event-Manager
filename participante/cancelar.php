<?php
include_once '../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$id_part = isset($_POST['id_participante']) ? (int) $_POST['id_participante'] : 0;
$id_event = isset($_POST['id_evento']) ? (int) $_POST['id_evento'] : 0;
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if ($id_part === 0 || $id_event === 0) {
    header('Location: ../ingressos/meus.php?email=' . urlencode($email) . '&error=invalid');
    exit;
}

try {
    $stmt = $conn->prepare('DELETE FROM participante_evento WHERE id_participante = ? AND id_evento = ?');
    $stmt->execute([$id_part, $id_event]);
    header('Location: ../ingressos/meus.php?email=' . urlencode($email) . '&success=canceled');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: ../ingressos/meus.php?email=' . urlencode($email) . '&error=delete_failed');
    exit;
}
