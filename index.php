<?php
  include 'conexion.php';

  $sqlUsuarios = "SELECT COUNT(*) AS cantidad FROM usuarios";
  $resultUsuarios = $conn->query($sqlUsuarios);
  $cantidadUsuarios = 0;

  if ($resultUsuarios->num_rows > 0) {
    $rowUsuarios = $resultUsuarios->fetch_assoc();
    $cantidadUsuarios = $rowUsuarios["cantidad"];
  }

  // Consulta para obtener el último usuario registrado
  $sqlUltimoUsuario = "SELECT nombre FROM usuarios ORDER BY id DESC LIMIT 1";
  $resultUltimoUsuario = $conn->query($sqlUltimoUsuario);
  $ultimoUsuario = "";

  if ($resultUltimoUsuario->num_rows > 0) {
    $rowUltimoUsuario = $resultUltimoUsuario->fetch_assoc();
    $ultimoUsuario = $rowUltimoUsuario["nombre"];
  }

  // Consulta para obtener la cantidad de publicaciones
  $sqlPublicaciones = "SELECT COUNT(*) AS cantidad FROM publicaciones";
  $resultPublicaciones = $conn->query($sqlPublicaciones);
  $cantidadPublicaciones = 0;

  if ($resultPublicaciones->num_rows > 0) {
    $rowPublicaciones = $resultPublicaciones->fetch_assoc();
    $cantidadPublicaciones = $rowPublicaciones["cantidad"];
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PrimateNet</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

  <style>
      body {
        background-color: #f0f2f5;
      }

      .container {
        background-color: #fff;
        margin-top: 50px;
        padding: 20px;
        border-radius: 5px;
      }

      .card {
        margin-bottom: 2px;
      }
  </style>
  
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="index.php">PrimateNet</a>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="registro.php">Registrarse</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login.php">Iniciar sesión</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container">
  <h1 class="text-center">Bienvenido a la red social PrimateNet</h1>
  <p class="mb-4 text-center">Regístrate o inicia sesión para conectarte con amigos y compartir contenido en conjunto.</p>

  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Cantidad de Usuarios</h5>
          <p class="card-text"><?php echo $cantidadUsuarios; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Último Usuario Registrado</h5>
          <p class="card-text"><?php echo $ultimoUsuario; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Cantidad de Publicaciones</h5>
          <p class="card-text"><?php echo $cantidadPublicaciones; ?></p>
        </div>
      </div>
    </div>
  </div>

</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
