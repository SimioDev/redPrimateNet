<?php include '../models/Conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Iniciar Sesión</title>
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
        <a class="nav-link" href="RegistroController.php">Registrarse</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container">
  <h1 class="mt-4 mb-4">Iniciar Sesión</h1>
  
  <form action="LoginController.php" method="POST">
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
  </form>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      if (password_verify($password, $row["password"])) {
        session_start();
        $_SESSION["usuario"] = $row["id"];
        header("Location: ../views/panel.php");
        exit();
      } else {
        echo '<div class="alert alert-danger mt-4" role="alert">Contraseña incorrecta.</div>';
      }
    } else {
      echo '<div class="alert alert-danger mt-4" role="alert">El usuario no existe.</div>';
    }
  }
  ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
