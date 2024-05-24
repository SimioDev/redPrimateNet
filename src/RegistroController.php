<?php include '../models/Conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registro</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    /* Estilos personalizados */
    body {
      background-color: #f0f2f5;
    }

    .container {
      background-color: #fff;
      margin-top: 50px;
      padding: 20px;
      border-radius: 5px;
      max-width: 400px; /* Ajusta el ancho máximo del contenedor */
    }

    .form-control {
      border-color: #3b5998;
    }

    .btn-primary {
      background-color: #3b5998;
      border-color: #3b5998;
    }

    .btn-primary:hover {
      background-color: #2d4373;
      border-color: #2d4373;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-primary">
  <a class="navbar-brand" href="../index.php">PrimateNet</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="login.php">Iniciar Sesión</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container">
  <div class="h1 mt-4 mb-3">Registro</div>

  <form action="RegistroController.php" method="POST">
    <div class="form-group">
      <label for="nombre">Nombre:</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Registrarse</button>
  </form>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $fotoPerfil = '../assets/images/default.png';

    $sql = "INSERT INTO usuarios (nombre, email, password, foto_perfil, fecha_registro) VALUES ('$nombre', '$email', '$password', '$fotoPerfil', NOW())";

    if ($conn->query($sql) === TRUE) {
      echo '<div class="alert alert-success mt-4" role="alert">Registro exitoso. <a href="LoginController.php">Inicia sesión aquí</a></div>';
    } else {
      echo '<div class="alert alert-danger mt-4" role="alert">Error al registrar el usuario: ' . $conn->error . '</div>';
    }
  }
  ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>

