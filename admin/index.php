<?php
require_once('auth.php');
include_once '../config/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../css/admin_style.css">
<title>Painel Administrativo</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="voltar.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <h1>Painel Administrativo</h1>
        <nav>
            <ul>
                <li><a href="locais/listar.php">Locais</a></li>
                <li><a href="organizadores/listar.php">Organizadores</a></li>
                <li><a href="eventos/eventos_listar.php">Eventos</a></li>
                <li><a href="participantes/participantes_listar.php">Participantes</a></li>
                <li><a href="ingressos/listar.php">Ingressos</a></li>
            </ul>
        </nav>
        
    </div>
</body>
</html>
