<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Usuario y contraseña definidos (puedes cambiarlo o usar BD)
    $usuarioValido = 'luismurillo';
    $passwordValido = 'Gdl2251!1';

    if ($usuario === $usuarioValido && $password === $passwordValido) {
        $_SESSION['autenticado'] = true;
        $_SESSION['usuario'] = $usuario;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar sesión</title>
<style>
    body { font-family: Arial; background: #f4f6f8; }
    .container { max-width: 400px; margin: 100px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    h1 { text-align: center; color: #0066cc; }
    input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
    .btn { width: 100%; padding: 12px; background: #0066cc; color: #fff; font-size: 16px; border: none; border-radius: 4px; cursor: pointer; }
    .btn:hover { background: #004999; }
    .error { color: red; text-align: center; margin-bottom: 10px; }
</style>
</head>
<body>
<div class="container">
    <h1>Iniciar sesión</h1>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" class="btn">Entrar</button>
    </form>
</div>
</body>
</html>