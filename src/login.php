<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Preparamos la consulta (sin prepare porque es más simple para este caso)
    $result = pg_query_params($conn,
        "SELECT id, firstname, lastname, password FROM users WHERE email = $1",
        array($correo)
    );

    if ($result && pg_num_rows($result) === 1) {
        $usuario = pg_fetch_assoc($result);

        if (password_verify($password, $usuario['password'])) {
            $_SESSION['user_name'] = $usuario['firstname'] . ' ' . $usuario['lastname'];
            $_SESSION['user_id'] = $usuario['id'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Correo no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center"><h1 class="h4 text-gray-900 mb-4">Bienvenido</h1></div>
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                                    <?php endif; ?>
                                    <form class="user" method="POST" action="login.php">
                                        <div class="form-group">
                                            <input type="email" name="correo" class="form-control form-control-user"
                                                placeholder="Correo electrónico" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                placeholder="Contraseña" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Iniciar sesión</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">¿No tienes cuenta? Regístrate</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>