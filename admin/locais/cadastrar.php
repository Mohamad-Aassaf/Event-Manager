<?php
require_once('../auth.php');
include_once '../../config/conexao.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_local'];
    $endereco = $_POST['endereco'];
    $capacidade = $_POST['capacidade'];

    $stmt = $conn->prepare("INSERT INTO Local (nome_local, endereco, capacidade) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $endereco, $capacidade]);
    header("Location: listar.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/admin_style.css">
    <title>Cadastrar Local</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="listar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Novo Local</h1>
        <form method="POST">
            <label for="nome_local">Nome:</label><br>
            <input type="text" id="nome_local" name="nome_local" required><br><br>

            <label for="endereco">Endereço:</label><br>
            <input type="text" id="endereco" name="endereco" required><br><br>

            <label for="capacidade">Capacidade:</label><br>
            <input type="number" id="capacidade" name="capacidade" required><br><br>

            <input type="submit" value="Salvar">
            <a href="listar.php"><button type="button">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
