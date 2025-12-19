<?php
include_once '../config/conexao.php';

if (empty($_GET['evento'])) {
    echo 'Evento não especificado.';
    exit;
}

$id_evento = (int) $_GET['evento'];

$stmt = $conn->prepare("SELECT * FROM Evento WHERE id_evento = ?");
$stmt->execute([$id_evento]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$evento) {
    echo 'Evento não encontrado.';
    exit;
}

$sql = $conn->prepare("SELECT i.id_ingresso, i.tipo, i.preco FROM Ingresso i WHERE i.id_evento = ? ORDER BY i.id_ingresso DESC");
$sql->execute([$id_evento]);
$ingressos = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/admin_style.css">
    <title>Ingressos - <?= htmlspecialchars($evento['nome_evento']) ?></title>
</head>
<body>
    <header>
        <div id="config">
            <a href="../index.php">&larr; Voltar</a>
        </div>
    </header>
    <div class="container">
        <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
            <div style="background:#d1e7dd;color:#0f5132;padding:10px;border-radius:4px;margin-bottom:16px;">Inscrição realizada com sucesso.</div>
        <?php endif; ?>
        <h1>Ingressos: <?= htmlspecialchars($evento['nome_evento']) ?></h1>
        <?php if (empty($ingressos)): ?>
            <p>Nenhum ingresso disponível para este evento.</p>
        <?php else: ?>
            <table>
                <tr><th>ID</th><th>Tipo</th><th>Preço</th><th>Ação</th></tr>
                <?php foreach($ingressos as $i): ?>
                    <tr>
                        <td><?= htmlspecialchars($i['id_ingresso']) ?></td>
                        <td><?= htmlspecialchars($i['tipo']) ?></td>
                        <td><?= htmlspecialchars($i['preco']) ?></td>
                        <td><a href="../participante/cadastrar.php?evento=<?= urlencode($id_evento) ?>&ingresso=<?= urlencode($i['id_ingresso']) ?>">Participar</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
