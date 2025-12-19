<?php require_once('../auth.php'); include_once '../../config/conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../../css/admin_style.css">
<title>Organizadores - Lista</title>
</head>
<body>
    <header>
                <a href='editar.php?id={$row['id_organizador']}'>Editar</a> |
                <a href='#' class='delete-entity' data-id='{$row['id_organizador']}' data-action='excluir.php'>Excluir</a>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_GET['error'])): ?>
                <?php echo 'Erro ao excluir o organizador. Tente novamente mais tarde.'; ?>
                ?>
            </div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
            <div style="background:#d1e7dd;color:#0f5132;padding:10px;border-radius:4px;margin-bottom:16px;">
                Organizador excluído com sucesso.
            </div>
        <?php endif; ?>

        <h1>Lista de Organizadores</h1>
        
        <div class="button-group">
            <a href="cadastrar.php"><button>Novo Organizador</button></a>
            <a href="../index.php"><button>Voltar</button></a>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = $conn->query("SELECT * FROM Organizador ORDER BY id_organizador DESC");
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id_organizador']}</td>
                        <td>{$row['nome']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['telefone']}</td>
                        <td>
                            <a href='editar.php?id={$row['id_organizador']}'>Editar</a> |
                            <a href='#' class='delete-entity' data-id='{$row['id_organizador']}' data-action='excluir.php'>Excluir</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
    </div>
        <div id="deleteModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:#0008;z-index:1000;align-items:center;justify-content:center;">
            <div style="background:#fff;padding:32px 24px;border-radius:8px;max-width:350px;text-align:center;box-shadow:0 2px 12px #0002;">
                <h2 style="margin-bottom:18px;">Excluir Organizador</h2>
                <p style="margin-bottom:24px;">Você realmente deseja excluir este organizador? Note que não é possível excluir enquanto houver eventos associados.</p>
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
