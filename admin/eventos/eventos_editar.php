<?php
require_once('../auth.php');
include('../../config/conexao.php');

if (!isset($_GET['id'])) {
    header("Location: eventos_listar.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM Evento WHERE id_evento = ?");
$stmt->execute([$id]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    echo "Evento não encontrado.";
    exit;
}

$locais = $conn->query("SELECT * FROM Local")->fetchAll(PDO::FETCH_ASSOC);
$organizadores = $conn->query("SELECT * FROM Organizador")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_evento'];
    $data = $_POST['data_evento'];
    $id_local = $_POST['id_local'];
    $id_organizador = $_POST['id_organizador'];

    $update = $conn->prepare("UPDATE Evento SET nome_evento=?, data_evento=?, id_local=?, id_organizador=? WHERE id_evento=?");
    $update->execute([$nome, $data, $id_local, $id_organizador, $id]);

    header("Location: eventos_listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Editar Evento</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="eventos_listar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Editar Evento</h1>
        <form method="POST">
            <label for="nome_evento">Nome do Evento:</label><br>
            <input type="text" id="nome_evento" name="nome_evento" value="<?= htmlspecialchars($evento['nome_evento']) ?>" required><br><br>

            <label for="data_evento">Data do Evento:</label><br>
            <input type="datetime-local" id="data_evento" name="data_evento" value="<?= date('Y-m-d\TH:i', strtotime($evento['data_evento'])) ?>" required><br><br>

            <label for="id_local">Local:</label><br>
            <select id="id_local" name="id_local" required>
                <option value="">Selecione um local</option>
                <?php foreach($locais as $l): ?>
                    <option value="<?= $l['id_local'] ?>" <?= $l['id_local'] == $evento['id_local'] ? 'selected' : '' ?>><?= htmlspecialchars($l['nome_local']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="id_organizador">Organizador:</label><br>
            <select id="id_organizador" name="id_organizador" required>
                <option value="">Selecione um organizador</option>
                <?php foreach($organizadores as $o): ?>
                    <option value="<?= $o['id_organizador'] ?>" <?= $o['id_organizador'] == $evento['id_organizador'] ? 'selected' : '' ?>><?= htmlspecialchars($o['nome']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <input type="submit" value="Atualizar">
            <a href="eventos_listar.php"><button type="button">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
