<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $stmt = $conn->prepare("INSERT INTO Organizador (nome, email, telefone) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $telefone]);

    header("Location: listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Cadastrar Organizador</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="listar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Cadastrar Organizador</h1>
        <form method="POST">
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" name="telefone"><br><br>

            <input type="submit" value="Salvar">
            <a href="listar.php"><button type="button">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
