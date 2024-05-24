<?php
session_start();

if (!isset($_SESSION["usuario"])) {
  header("Location: ../src/LoginController.php");
  exit();
}

include '../models/Conexion.php';

// Obtener todas las publicaciones de todos los usuarios ordenadas por fecha de forma descendente
$sql = "SELECT publicaciones.*, usuarios.nombre, usuarios.foto_perfil FROM publicaciones INNER JOIN usuarios ON publicaciones.usuario_id = usuarios.id ORDER BY publicaciones.fecha_publicacion DESC";
$result = $conn->query($sql);

$publicaciones = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $publicaciones[] = $row;
  }
}

// Aquí muestro la fecha de publicación de cada post
function getTimeElapsedString($datetime, $full = false) {
    date_default_timezone_set('America/Bogota');
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'año',
        'm' => 'mes',
        'w' => 'semana',
        'd' => 'día',
        'h' => 'hora',
        'i' => 'minuto',
        's' => 'segundo',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . '.' : 'un momento.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Panel de Usuario</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

  <style>
    @font-face {
        font-family: 'SecularOne-Regular';
        src: url('../assets/fonts/SecularOne-Regular.ttf') format("truetype");
        font-weight: normal;
        font-style: normal;
    }
    body {
      background-color: #f0f2f5;
    }

    .navbar-brand {
      color: #fff;
    }

    .navbar-nav .nav-link:hover {
      color: #f0f2f5;
    }

    .btn-logout{
      color: #fff;
    }
    .btn-logout:hover{
      text-decoration: none;
      color: white;
    }

    .content {
      margin-top: 20px;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      margin: 0 auto;
    }

    .publicacion {
      margin-bottom: 20px;
      padding: 20px;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      width: 70%;
    }

    .publicacion .autor {
      display: flex;
      align-items: center;
      margin-bottom: 5px;
    }

    .publicacion .autor img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .publicacion .autor .nombre {
      font-weight: bold;
      margin-bottom: 0;
    }

    .publicacion .autor .fecha{
      
      margin-bottom: 0;
    }

    .publicacion .contenido {
      margin-top: 10px;
      word-wrap: break-word;
      color: #343334;
    }

    .no-publicaciones {
      margin-top: 20px;
      text-align: center;
      color: #888;
    }

    .primate{
        font-family: 'SecularOne-Regular';
    }
  </style>
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-primary">
  <a class="navbar-brand primate" href="#"><b>PrimateNet</b></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link disabled" href="panel.php">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../src/InformacionUsuarioController.php">Mi Perfil</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <button type="submit" class="btn btn-danger btn-sm float-right"><a class="btn-logout" href="../src/LogoutController.php">Cerrar Sesión</a></button>
      </li>
    </ul>
  </div>
</nav>


<div class="container content mt-4 mb-4">

  <div class="d-flex align-content-start flex-wrap px-3">
    <div class="h3">Revisa las últimas publicaciones!</div>

    <button type="button" class="btn btn-primary mx-4 px-4 mb-4" data-toggle="modal" data-target="#crearPublicacionModal">
      Crear nueva publicación
    </button>
  </div>

  <div class="row justify-content-center">
    <?php if (!empty($publicaciones)): ?>
      <?php foreach ($publicaciones as $publicacion): ?>
        <div class="publicacion col-auto">

          <div class="autor">

            <img src="<?php echo $publicacion["foto_perfil"]; ?>" alt="Foto de perfil">
            <h6 class="nombre"><?php echo $publicacion["nombre"]; ?></h6>
            <p class="ml-auto fecha"><span class="badge rounded-pill bg-primary p-2 text-white">Hace <?php echo getTimeElapsedString($publicacion["fecha_publicacion"]); ?></span></p>

          </div>
          
          <div>
            <p class="contenido"><?php echo $publicacion["contenido"]; ?></p>
          </div>

          <?php if (!empty($publicacion["imagen"])): ?>
          <div class="text-center img-fluid">
            <img src="../assets/images/<?php echo $publicacion["imagen"]; ?>" alt="Imagen de la publicación" style="max-width: 476px; max-height: 476px;"> <!-- Establecer el tamaño máximo de la imagen -->
          </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="no-publicaciones">Aún no hay publicaciones.</p>
    <?php endif; ?>
  </div>


</div>




<!-- Modal donde puedo crear una nueva Publicación -->
<div class="modal fade" id="crearPublicacionModal" tabindex="-1" role="dialog" aria-labelledby="crearPublicacionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="crearPublicacionModalLabel">Crear nueva publicación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="../src/CrearPublicacionController.php" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="contenido">Contenido:</label>
            <textarea class="form-control" name="contenido" id="contenido" rows="3" required></textarea>
          </div>
          <div class="form-group">
            <label for="imagen">Subir imagen (opcional):</label>
            <input type="file" class="form-control-file" name="imagen" id="imagen">
          </div>
          <button type="submit" class="btn btn-primary">Publicar</button>
        </form>
      </div>
    </div>
  </div>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
