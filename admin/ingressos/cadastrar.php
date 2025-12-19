<?php
require_once('../auth.php');
include_once '../../config/conexao.php';

$eventos = $conn->query("SELECT * FROM Evento")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_evento = $_POST['id_evento'];
    $tipo = $_POST['tipo'];
    $preco = $_POST['preco'];

    $stmt = $conn->prepare("INSERT INTO Ingresso (id_evento, tipo, preco) VALUES (?, ?, ?)");
    $stmt->execute([$id_evento, $tipo, $preco]);

    header("Location: listar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Cadastrar Ingresso</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="listar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Cadastrar Ingresso</h1>
        <form method="POST">
            <label for="id_evento">Evento:</label><br>
            <select id="id_evento" name="id_evento" required>
                <option value="">Selecione um evento</option>
                <?php foreach($eventos as $e): ?>
                    <option value="<?= $e['id_evento'] ?>"><?= htmlspecialchars($e['nome_evento']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="tipo">Tipo de Ingresso:</label><br>
            <input type="text" id="tipo" name="tipo" required><br><br>

            <label for="preco">Preço:</label><br>
            <input type="number" step="0.01" id="preco" name="preco" required><br><br>

            <input type="submit" value="Salvar">
            <a href="listar.php"><button type="button">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
