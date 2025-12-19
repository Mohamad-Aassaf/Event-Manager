<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id = (int) $_GET['id'];

 
$stmt = $conn->prepare("SELECT * FROM Local WHERE id_local = ?");
$stmt->execute([$id]);
$local = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$local) {
    echo "Local não encontrado.";
    exit;
}

 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_local'];
    $endereco = $_POST['endereco'];
    $capacidade = $_POST['capacidade'];

    $update = $conn->prepare("UPDATE Local SET nome_local=?, endereco=?, capacidade=? WHERE id_local=?");
    $update->execute([$nome, $endereco, $capacidade, $id]);

    header("Location: listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Editar Local</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="listar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Editar Local</h1>
        <form method="POST">
            <label for="nome_local">Nome do Local:</label><br>
            <input type="text" id="nome_local" name="nome_local" value="<?= htmlspecialchars($local['nome_local']) ?>" required><br><br>

            <label for="endereco">Endereço:</label><br>
            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($local['endereco']) ?>" required><br><br>

            <label for="capacidade">Capacidade:</label><br>
            <input type="number" id="capacidade" name="capacidade" value="<?= htmlspecialchars($local['capacidade']) ?>" required><br><br>

            <input type="submit" value="Atualizar">
            <a href="listar.php"><button type="button">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
