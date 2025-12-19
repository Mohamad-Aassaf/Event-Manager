<?php
require_once('../auth.php');
include_once '../../config/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Participantes - Lista</title>
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
                <?php echo 'Erro ao excluir o participante. Tente novamente mais tarde.'; ?>
            </div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
            <div style="background:#d1e7dd;color:#0f5132;padding:10px;border-radius:4px;margin-bottom:16px;">
                Participante excluído com sucesso.
            </div>
        <?php endif; ?>
                <div id="deleteModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:#0008;z-index:1000;align-items:center;justify-content:center;">
                        <div style="background:#fff;padding:32px 24px;border-radius:8px;max-width:350px;text-align:center;box-shadow:0 2px 12px #0002;">
                            <h2 style="margin-bottom:18px;">Excluir Participante</h2>
                            <p style="margin-bottom:24px;">Você realmente deseja excluir este participante e todas as participações relacionadas?</p>
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

        <h1>Lista de Participantes</h1>
        
        <div class="button-group">
            <a href="cadastrar.php"><button>Novo Participante</button></a>
            <a href="../index.php"><button>Voltar</button></a>
        </div>

        <table>
            <tr>
                  <th>ID</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = $conn->query("SELECT * FROM Participante ORDER BY id_participante DESC");
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id_participante']}</td>
                        <td>{$row['nome']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['telefone']}</td>
                        <td>
                            <a href='participantes_editar.php?id={$row['id_participante']}'>Editar</a> |
                            <a href='#' class='delete-entity' data-id='{$row['id_participante']}' data-action='excluir.php'>Excluir</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
