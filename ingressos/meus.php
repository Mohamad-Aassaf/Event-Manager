<?php
include_once '../config/conexao.php';

$emailSearch = '';
$emailSearch = '';
$participant = null;
$registrations = [];
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['email'])) {
    if (isset($_GET['email']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $emailSearch = trim($_GET['email']);
    } else {
        $emailSearch = trim($_POST['email']);
    }
    $emailSearch = trim($_POST['email']);
    if (!empty($emailSearch)) {
        $stmt = $conn->prepare("SELECT * FROM Participante WHERE email = ?");
        $stmt->execute([$emailSearch]);
        $participant = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($participant) {
            $sql = $conn->prepare("SELECT pe.id_participante, pe.id_evento, pe.data_inscricao, pe.status, e.nome_evento, e.data_evento, l.nome_local, o.nome AS organizador
                                    FROM participante_evento pe
                                    JOIN Evento e ON pe.id_evento = e.id_evento
                                    LEFT JOIN Local l ON e.id_local = l.id_local
                                    LEFT JOIN Organizador o ON e.id_organizador = o.id_organizador
                                    WHERE pe.id_participante = ?");
            $sql->execute([$participant['id_participante']]);
            $registrations = $sql->fetchAll(PDO::FETCH_ASSOC);
            if (empty($registrations)) {
                $message = 'Nenhuma inscrição encontrada para este email.';
            }
        } else {
            $message = 'Email não encontrado.';
        }
    } else {
        $message = 'Por favor, informe um email.';
    }
}

$notice = null;
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'canceled') { $notice = 'Inscrição cancelada com sucesso.'; }
    if ($_GET['success'] === 'updated') { $notice = 'Informações atualizadas com sucesso.'; }
}

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'delete_failed') { $notice = 'Erro ao cancelar a inscrição.'; }
    if ($_GET['error'] === 'invalid') { $notice = 'Dados inválidos para cancelamento.'; }
}


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/admin_style.css">
    <title>Minhas Inscrições</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="../index.php">&larr; Voltar</a>
        </div>
    </header>
    <div class="container">
        <h1>Minhas Inscrições</h1>
        <form method="post" style="display:flex;gap:8px;justify-content:center;margin-bottom:16px;">
            <input type="email" name="email" placeholder="Informe seu email" required value="<?= htmlspecialchars($emailSearch) ?>">
            <input type="submit" value="Buscar">
        </form>
        <?php if (!empty($notice)): ?>
            <div style="background:#d1e7dd;color:#0f5132;padding:10px;border-radius:4px;margin-bottom:16px;"> <?= htmlspecialchars($notice) ?></div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div style="background:#f8d7da;color:#842029;padding:10px;border-radius:4px;margin-bottom:16px;"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($participant): ?>
            <div style="margin-bottom:16px;text-align:center;">
                <p><strong>Nome:</strong> <?= htmlspecialchars($participant['nome']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($participant['email']) ?></p>
            </div>
            <?php if (!empty($registrations)): ?>
                <table>
                    <tr><th>ID</th><th>Evento</th><th>Data</th><th>Local</th><th>Status</th><th>Ações</th></tr>
                    <?php foreach ($registrations as $reg): ?>
                        <tr>
                            <td><?= htmlspecialchars($reg['id_evento']) ?></td>
                            <td><?= htmlspecialchars($reg['nome_evento']) ?></td>
                            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($reg['data_evento']))) ?></td>
                            <td><?= htmlspecialchars($reg['nome_local']) ?></td>
                            <td><?= htmlspecialchars($reg['status']) ?></td>
                            <td>
                                <a href="../participante/editar.php?id=<?= $participant['id_participante'] ?>&email=<?= urlencode($participant['email']) ?>">Editar Informações</a> |
                                <form method="post" action="../participante/cancelar.php" style="display:inline;">
                                    <input type="hidden" name="id_participante" value="<?= $participant['id_participante'] ?>">
                                    <input type="hidden" name="id_evento" value="<?= $reg['id_evento'] ?>">
                                    <input type="hidden" name="email" value="<?= htmlspecialchars($participant['email']) ?>">
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja cancelar esta inscrição?');">Cancelar Inscrição</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
