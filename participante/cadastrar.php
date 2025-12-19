<?php
include_once '../config/conexao.php';

if (empty($_GET['evento']) && empty($_POST['evento'])) {
    echo 'Evento não especificado.';
    exit;
}

$id_evento = 0;
if (isset($_GET['evento'])) {
    $id_evento = (int) $_GET['evento'];
} elseif (isset($_POST['evento'])) {
    $id_evento = (int) $_POST['evento'];
}
$id_ingresso = 0;
if (isset($_GET['ingresso'])) {
    $id_ingresso = (int) $_GET['ingresso'];
} elseif (isset($_POST['ingresso'])) {
    $id_ingresso = (int) $_POST['ingresso'];
}

$confirmValues = null;

$stmt = $conn->prepare("SELECT * FROM Evento WHERE id_evento = ?");
$stmt->execute([$id_evento]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$evento) {
    echo 'Evento não encontrado.';
    exit;
}

if ($id_ingresso > 0) {
    $ingStmt = $conn->prepare("SELECT * FROM Ingresso WHERE id_ingresso = ? AND id_evento = ?");
    $ingStmt->execute([$id_ingresso, $id_evento]);
    $ingresso = $ingStmt->fetch(PDO::FETCH_ASSOC);
} else {
    $ingresso = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : 'review';
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);

    if (empty($nome) || empty($email)) {
        $error = 'Nome e email são obrigatórios.';
    } else {
        
        if ($action === 'review') {
            
            $confirmValues = [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'ingresso' => $id_ingresso
            ];
        } elseif ($action === 'confirm') {
            try {
                $ins = $conn->prepare("INSERT INTO Participante (nome, email, telefone) VALUES (?, ?, ?)");
                $ins->execute([$nome, $email, $telefone]);
                $id_part = $conn->lastInsertId();
            } catch (PDOException $e) {
                error_log('Participante insert error: ' . $e->getMessage());
                $error = 'Erro ao registrar participação. Tente novamente mais tarde.';
            }

            if (empty($error)) {
                try {
                    $now = date('Y-m-d H:i:s');
                    $status = 'Confirmado';
                    $assoc = $conn->prepare("INSERT INTO participante_evento (id_participante, id_evento, data_inscricao, status) VALUES (?, ?, ?, ?)");
                    $assoc->execute([$id_part, $id_evento, $now, $status]);
                    header('Location: ../ingressos/listar.php?evento=' . urlencode($id_evento) . '&success=registered');
                    exit;
                } catch (PDOException $e) {
                    error_log('Associar participante error: ' . $e->getMessage());
                    $error = 'Erro ao associar participante ao evento.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/admin_style.css">
    <title>Inscrição - <?= htmlspecialchars($evento['nome_evento']) ?></title>
</head>
<body>
    <header>
        <div id="config">
            <a href="../index.php">&larr; Voltar</a>
        </div>
    </header>
    <div class="container">
        <h1>Inscrever-se: <?= htmlspecialchars($evento['nome_evento']) ?></h1>
        <?php if (!empty($error)): ?>
            <div style="background:#f8d7da;color:#842029;padding:10px;border-radius:4px;margin-bottom:16px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($ingresso): ?>
            <div style="background:#e7f7ff;color:#0b3d4a;padding:10px;border-radius:4px;margin-bottom:16px;">
                <strong>Ingresso selecionado:</strong> <?= htmlspecialchars($ingresso['tipo']) ?> — R$ <?= htmlspecialchars(number_format($ingresso['preco'], 2, ',', '.')) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($confirmValues)): ?>
            <h2>Confirme sua inscrição</h2>
            <p><strong>Evento:</strong> <?= htmlspecialchars($evento['nome_evento']) ?></p>
            <?php if ($ingresso): ?>
                <p><strong>Ingresso:</strong> <?= htmlspecialchars($ingresso['tipo']) ?> — R$ <?= htmlspecialchars(number_format($ingresso['preco'], 2, ',', '.')) ?></p>
            <?php endif; ?>
            <p><strong>Nome:</strong> <?= htmlspecialchars($confirmValues['nome']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($confirmValues['email']) ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($confirmValues['telefone']) ?></p>
            <form method="post">
                <input type="hidden" name="evento" value="<?= htmlspecialchars($id_evento) ?>">
                <input type="hidden" name="ingresso" value="<?= htmlspecialchars($confirmValues['ingresso']) ?>">
                <input type="hidden" name="nome" value="<?= htmlspecialchars($confirmValues['nome']) ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($confirmValues['email']) ?>">
                <input type="hidden" name="telefone" value="<?= htmlspecialchars($confirmValues['telefone']) ?>">
                <input type="hidden" name="action" value="confirm">
                <div class="button-group">
                    <input type="submit" value="Confirmar Inscrição">
                    <button type="button" onclick="history.back();">Editar</button>
                </div>
            </form>
        <?php else: ?>
        <form method="post">
            <input type="hidden" name="action" value="review">
            <input type="hidden" name="evento" value="<?= htmlspecialchars($id_evento) ?>">
            <?php if ($ingresso): ?><input type="hidden" name="ingresso" value="<?= htmlspecialchars($id_ingresso) ?>"><?php endif; ?>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required value="<?= htmlspecialchars(isset($nome) ? $nome : '') ?>">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required value="<?= htmlspecialchars(isset($email) ? $email : '') ?>">

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars(isset($telefone) ? $telefone : '') ?>">

            <div class="button-group">
                <input type="submit" value="Próximo">
                <a href="../ingressos/listar.php?evento=<?= urlencode($id_evento) ?>"><button type="button">Cancelar</button></a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
