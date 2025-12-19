<?php
require_once('../auth.php');
include('../../config/conexao.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Eventos - Lista</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="../index.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_GET['error'])): ?>
            <div style="background:#f8d7da;color:#842029;padding:10px;border-radius:4px;margin-bottom:16px;">
                <?php echo 'Erro ao excluir o evento. Tente novamente mais tarde.'; ?>
            </div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
            <div style="background:#d1e7dd;color:#0f5132;padding:10px;border-radius:4px;margin-bottom:16px;">
                Evento excluído com sucesso.
            </div>
        <?php endif; ?>

        <h1>Lista de Eventos</h1>
        
            <div class="button-group">
            <a href="eventos_cadastrar.php"><button>Novo Evento</button></a>
            <a href="../index.php"><button>Voltar</button></a>
        </div>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Data</th>
                <th>Local</th>
                <th>Organizador</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = $conn->query("SELECT e.id_evento, e.nome_evento, e.data_evento, l.nome_local, o.nome AS organizador
                                FROM Evento e
                                LEFT JOIN Local l ON e.id_local = l.id_local
                                LEFT JOIN Organizador o ON e.id_organizador = o.id_organizador
                                ORDER BY e.id_evento DESC");

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $dataFormatada = $row['data_evento'] ? date('d/m/Y H:i', strtotime($row['data_evento'])) : '';
                $id = htmlspecialchars($row['id_evento']);
                $nome = htmlspecialchars($row['nome_evento']);
                $dataOut = htmlspecialchars($dataFormatada);
                $local = htmlspecialchars($row['nome_local']);
                $organizador = htmlspecialchars($row['organizador']);

                echo "<tr>"
                    . "<td>{$id}</td>"
                    . "<td>{$nome}</td>"
                    . "<td>{$dataOut}</td>"
                    . "<td>{$local}</td>"
                    . "<td>{$organizador}</td>"
                    . "<td><a href='eventos_editar.php?id={$id}'>Editar</a> | "
                        . "<a href='#' class='delete-event' data-id='{$id}'>Excluir</a></td>"
                    . "</tr>";
            }
            ?>
        </table>
    </div>
        
        <div id="deleteModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:#0008;z-index:1000;align-items:center;justify-content:center;">
            <div style="background:#fff;padding:32px 24px;border-radius:8px;max-width:350px;text-align:center;box-shadow:0 2px 12px #0002;">
                <h2 style="margin-bottom:18px;">Excluir Evento</h2>
                <p style="margin-bottom:24px;">Você realmente deseja excluir o evento e todos os dados relacionados?</p>
                <form id="deleteForm" method="post" action="excluir.php">
                    <input type="hidden" name="id" id="deleteId">
                    <button type="submit" style="background:#b00;color:#fff;padding:8px 18px;border:none;border-radius:4px;">Sim, excluir</button>
                    <button type="button" id="cancelDelete" style="margin-left:12px;padding:8px 18px;">Cancelar</button>
                </form>
            </div>
        </div>
        <script>
        document.querySelectorAll('.delete-event').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('deleteId').value = btn.getAttribute('data-id');
                document.getElementById('deleteModal').style.display = 'flex';
            });
        });
        document.getElementById('cancelDelete').onclick = function() {
            document.getElementById('deleteModal').style.display = 'none';
        };
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') document.getElementById('deleteModal').style.display = 'none';
        });
        </script>
</body>
</html>
