<?php
include_once '../config/conexao.php';

if (empty($_GET['id']) && empty($_POST['id'])) {
    echo 'Participante não especificado.';
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) $_POST['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    try {
        $stmt = $conn->prepare("UPDATE Participante SET nome=?, email=?, telefone=? WHERE id_participante=?");
        $stmt->execute([$nome, $email, $telefone, $id]);
        header('Location: ../ingressos/meus.php?email=' . urlencode($email) . '&success=updated');
        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $error = 'Erro ao atualizar participante.';
    }
}

$stmt = $conn->prepare('SELECT * FROM Participante WHERE id_participante = ?');
$stmt->execute([$id]);
$participant = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$participant) {
    echo 'Participante não encontrado.';
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/admin_style.css">
    <title>Editar Participante</title>
</head>
<body>
    <header>
        <div id="config"><a href="../index.php">&larr; Voltar</a></div>
    </header>
    <div class="container">
        <h1>Editar Participante</h1>
        <?php if (!empty($error)): ?>
            <div style="background:#f8d7da;color:#842029;padding:10px;border-radius:4px;margin-bottom:16px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($participant['id_participante']) ?>">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($participant['nome']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($participant['email']) ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" value="<?= htmlspecialchars($participant['telefone']) ?>">

            <div class="button-group">
                <input type="submit" value="Salvar">
                <a href="../ingressos/meus.php?email=<?= urlencode($participant['email']) ?>"><button type="button">Cancelar</button></a>
            </div>
        </form>
    </div>
</body>
</html>
