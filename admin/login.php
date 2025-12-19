<?php
session_start();

$ADMIN_PASSWORD_HASH = password_hash('admin123', PASSWORD_DEFAULT);

if (isset($_POST['password'])) {
    if (password_verify($_POST['password'], $ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Senha incorreta.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login Admin (senha: admin123)</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        .login-box { max-width: 350px; margin: 80px auto; padding: 24px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; color: #111; }
        .login-box h2 { color: #111; }
        .login-box label { color: #111; }
        .login-box input[type=password] { width: 100%; padding: 8px; margin: 12px 0; color: #111; }
        .login-box button { width: 100%; padding: 10px; background: #333; color: #fff; border: none; border-radius: 4px; }
        .error { color: #b00; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login (senha: admin123)</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
