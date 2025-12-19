<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM Organizador WHERE id_organizador = ?");
$stmt->execute([$id]);
$org = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$org) {
    echo "Organizador não encontrado.";
    exit;
}

 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $update = $conn->prepare("UPDATE Organizador SET nome=?, email=?, telefone=? WHERE id_organizador=?");
    $update->execute([$nome, $email, $telefone, $id]);

    header("Location: listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Editar Organizador</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="listar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Editar Organizador</h1>
        <form method="POST">
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($org['nome']) ?>" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($org['email']) ?>" required><br><br>

            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($org['telefone']) ?>"><br><br>

            <input type="submit" value="Atualizar">
            <a href="listar.php"><button type="button">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
