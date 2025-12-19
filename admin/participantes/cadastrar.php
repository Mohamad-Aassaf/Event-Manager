<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $stmt = $conn->prepare("INSERT INTO Participante (nome, email, telefone) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $telefone]);

    header("Location: participantes_listar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/admin_style.css">
    <title>Cadastrar Participante</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="participantes_listar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Cadastrar Participante</h1>
        <form method="POST">
            <div class="input-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required>
            </div>

            <div class="button-group">
                <input type="submit" value="Salvar">
                <a href="participantes_listar.php"><button type="button">Cancelar</button></a>
            </div>
        </form>
    </div>
</body>
</html>
