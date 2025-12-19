<?php
require_once('../auth.php');
include_once '../../config/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Ingressos - Lista</title>
</head>
<body>
    <header>
        <div id="config">
            <a href="../index.php">&larr; Voltar</a>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_GET['error'])): ?>
            <div style="background:#f8d7da;color:#842029;padding:10px;border-radius:4px;margin-bottom:16px;">Erro ao excluir o ingresso. Tente novamente mais tarde.</div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
            <div style="background:#d1e7dd;color:#0f5132;padding:10px;border-radius:4px;margin-bottom:16px;">Ingresso excluído com sucesso.</div>
        <?php endif; ?>
        <h1>Lista de Ingressos</h1>
        
        <div class="button-group">
            <a href="cadastrar.php"><button>Novo Ingresso</button></a>
            <a href="../index.php"><button>Voltar</button></a>
        </div>

        <table>
            <tr>
                <th>ID</th> 
                <th>Evento</th>
                <th>Tipo</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = $conn->query("
                SELECT i.id_ingresso, e.nome_evento, i.tipo, i.preco
                FROM Ingresso i
                JOIN Evento e ON i.id_evento = e.id_evento
                ORDER BY i.id_ingresso DESC
            ");
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id_ingresso']}</td>
                        <td>{$row['nome_evento']}</td>
                        <td>{$row['tipo']}</td>
                        <td>{$row['preco']}</td>
                        <td>
                            <a href='editar.php?id={$row['id_ingresso']}'>Editar</a> |
                               <a href='#' class='delete-entity' data-id='{$row['id_ingresso']}' data-action='excluir.php'>Excluir</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
    </div>
            <div id="deleteModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:#0008;z-index:1000;align-items:center;justify-content:center;">
                <div style="background:#fff;padding:32px 24px;border-radius:8px;max-width:350px;text-align:center;box-shadow:0 2px 12px #0002;">
                    <h2 style="margin-bottom:18px;">Excluir Ingresso</h2>
                    <p style="margin-bottom:24px;">Você realmente deseja excluir este ingresso?</p>
                    <form id="deleteForm" method="post" action="excluir.php">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" style="background:#b00;color:#fff;padding:8px 18px;border:none;border-radius:4px;">Sim, excluir</button>
                        <button type="button" id="cancelDelete" style="margin-left:12px;padding:8px 18px;">Cancelar</button>
                    </form>
                </div>
            </div>
            <script>
            document.querySelectorAll('.delete-entity').forEach(function(btn) {
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
