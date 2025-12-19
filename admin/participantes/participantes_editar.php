<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

if (!isset($_GET['id'])) {
    header("Location: participantes_listar.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM Participante WHERE id_participante = ?");
$stmt->execute([$id]);
$participante = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$participante) {
    echo "Participante não encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $update = $conn->prepare("UPDATE Participante SET nome=?, email=?, telefone=? WHERE id_participante=?");
    $update->execute([$nome, $email, $telefone, $id]);

    header("Location: participantes_listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Editar Participante</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="participantes_listar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Editar Participante</h1>
        <form method="POST">
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($participante['nome']) ?>" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($participante['email']) ?>" required><br><br>

            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($participante['telefone']) ?>"><br><br>

            <input type="submit" value="Atualizar">
            <a href="participantes_listar.php"><button type="button">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
