<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';
$exito = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['firstname'];
    $apellido = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $check = pg_query_params($conn, "SELECT 1 FROM users WHERE email = $1", array($email));
    if (pg_num_rows($check) > 0) {
        $error = "El correo ya está registrado.";
    } else {
        $query = "
            INSERT INTO users (firstname, lastname, email, password)
            VALUES ($1, $2, $3, $4)
        ";

        $params = array($nombre, $apellido, $email, $hashedPassword);
        $result = pg_query_params($conn, $query, $params);

        if ($result) {
            $exito = "Usuario registrado correctamente. <a href='login.php'>Iniciar sesión</a>";
        } else {
            $error = "Error al registrar el usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Crear una cuenta</h1>
                                    </div>
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                                    <?php elseif ($exito): ?>
                                        <div class="alert alert-success"><?php echo $exito; ?></div>
                                    <?php endif; ?>
                                    <form class="user" method="POST" action="register.php">
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" name="firstname" class="form-control form-control-user" placeholder="Nombre" required>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="lastname" class="form-control form-control-user" placeholder="Apellido" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user" placeholder="Correo electrónico" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user" placeholder="Contraseña" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">Registrar cuenta</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="login.php">¿Ya tienes una cuenta? Inicia sesión</a>
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