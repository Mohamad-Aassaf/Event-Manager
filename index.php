<?php
include_once 'config/conexao.php';

$sql = $conn->query("
    SELECT e.id_evento, e.nome_evento, e.data_evento, l.nome_local, o.nome AS nome_organizador
    FROM Evento e
    JOIN Local l ON e.id_local = l.id_local
    JOIN Organizador o ON e.id_organizador = o.id_organizador
    ORDER BY e.data_evento ASC
");
$eventos = $sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="css/admin_style.css">
<title>Eventos</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="admin/index.php">Administrar &rarr;</a>
        </div>
    </header>

    <div class="container">
        <h1>Próximos Eventos</h1>
        <div style="text-align:center;margin-bottom:1.5vh;">
            <a href="ingressos/meus.php"><button>Ver Meus Ingressos</button></a>
        </div>

        <?php if (count($eventos) > 0): ?>
            <ul class="eventos-lista">
                <?php foreach ($eventos as $evento): ?>
                    <li>
                        <h2><?= htmlspecialchars($evento['nome_evento']) ?></h2>
                        <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($evento['data_evento'])) ?></p>
                        <p><strong>Local:</strong> <?= htmlspecialchars($evento['nome_local']) ?></p>
                        <p><strong>Organizador:</strong> <?= htmlspecialchars($evento['nome_organizador']) ?></p>
                        <a href="ingressos/listar.php?evento=<?= $evento['id_evento'] ?>"><button>Participar</button></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p style="text-align: center;">Nenhum evento cadastrado no momento.</p>
        <?php endif; ?>
    </div>
</body>
</html>
