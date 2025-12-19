<?php
require_once('../auth.php');
include('../../config/conexao.php');

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM Ingresso WHERE id_ingresso = ?");
$stmt->execute([$id]);
$ingresso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ingresso) {
    echo "Ingresso não encontrado.";
    exit;
}

$eventos = $conn->query("SELECT * FROM Evento")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_evento = $_POST['id_evento'];
    $tipo = $_POST['tipo'];
    $preco = $_POST['preco'];

    $update = $conn->prepare("UPDATE Ingresso SET id_evento=?, tipo=?, preco=? WHERE id_ingresso=?");
    $update->execute([$id_evento, $tipo, $preco, $id]);

    header("Location: listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Editar Ingresso</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="listar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Editar Ingresso</h1>
        <form method="POST">
            <label for="id_evento">Evento:</label><br>
            <select id="id_evento" name="id_evento" required>
                <option value="">Selecione um evento</option>
                <?php foreach($eventos as $e): ?>
                    <option value="<?= $e['id_evento'] ?>" <?= $e['id_evento'] == $ingresso['id_evento'] ? 'selected' : '' ?>><?= htmlspecialchars($e['nome_evento']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="tipo">Tipo de Ingresso:</label><br>
            <input type="text" id="tipo" name="tipo" value="<?= htmlspecialchars($ingresso['tipo']) ?>" required><br><br>

            <label for="preco">Preço:</label><br>
            <input type="number" step="0.01" id="preco" name="preco" value="<?= htmlspecialchars($ingresso['preco']) ?>" required><br><br>

            <input type="submit" value="Atualizar">
            <a href="listar.php"><button type="button">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
