<?php
session_start();

if (!isset($_SESSION["usuario"])) {
  header("Location: LoginController.php");
  exit();
}

include '../models/conexion.php';

$usuario = $_SESSION["usuario"];

$sql = "SELECT * FROM publicaciones WHERE usuario_id = $usuario ORDER BY fecha_publicacion DESC";
$result = $conn->query($sql);

$publicaciones = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $publicaciones[] = $row;
  }
}

$cantidadPublicaciones = count($publicaciones);

$sql = "SELECT * FROM usuarios WHERE id = {$_SESSION["usuario"]}";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
  $row = $result->fetch_assoc();
  $nombre = $row["nombre"];
  $email = $row["email"];
  $fechaRegistro = $row["fecha_registro"];
  $fotoPerfil = $row["foto_perfil"];
} else {
  echo "No se encontró información del usuario.";
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminarPublicacion"])) {
  $publicacionId = $_POST["eliminarPublicacion"];
  
  $sql = "DELETE FROM publicaciones WHERE id = $publicacionId AND usuario_id = $usuario";
  
  if ($conn->query($sql) === TRUE) {
    header("Location: InformacionUsuarioController.php");
    echo '<div class="alert alert-success mt-4" role="alert">La publicacion se ha borrado correctamente.</div>';
    exit();
  } else {
    echo "Error al eliminar la publicación: " . $conn->error;
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Información de Usuario</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    @font-face {
    font-family: 'Onest-VariableFont_wght';
    src: url('../assets/fonts/Onest-VariableFont_wght.ttf') format("truetype");
    font-weight: normal;
    font-style: normal;
    }
    @font-face {
        font-family: 'SecularOne-Regular';
        src: url('../assets/fonts/SecularOne-Regular.ttf') format("truetype");
        font-weight: normal;
        font-style: normal;
    }
    body {
      font-family: 'Onest-VariableFont_wght';
      background-color: #f0f2f5;
    }

    .navbar {
      background-color: #3b5998;
      color: #fff;
    }

    .navbar-brand {
      color: #fff;
    }

    .btn-logout{
      color: #fff;
    }
    .btn-logout:hover{
      text-decoration: none;
      color: white;
    }

    .navbar-nav .nav-link {
      color: #fff;
    }

    .navbar-nav .nav-link:hover {
      color: #f0f2f5;
    }

    .content {
      margin-top: 20px;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      max-width: 800px; 
      margin: 0 auto; 
    }

    .publicacion {
      background-color: #f0f0f0;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
    }

    .fecha {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .contenido {
      margin: 0;
    }

    .primate{
        font-family: 'SecularOne-Regular';
        transition: 0.5s;
    }

    .perfil {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      margin: 20px auto;
      object-fit: cover;
      display: block;
      border: 3px solid #fff;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

  </style>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-primary">
  <a class="navbar-brand primate" href="../views/panel.php"><b>PrimateNet</b></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="../views/panel.php">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="InformacionUsuarioController.php">Mi Perfil</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <button type="submit" class="btn btn-danger btn-sm float-right"><a class="btn-logout" href="../src/LogoutController.php">Cerrar Sesión</a></button>
      </li>
    </ul>
  </div>
</nav>

<div class="container content mt-4">
  <div class="h3 mb-4">Tu información:</div>
    <img src="<?php echo $fotoPerfil; ?>" class="perfil" alt="Foto de perfil">
    <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><strong>Fecha de Registro:</strong> <?php echo $fechaRegistro; ?></p>
    <p><strong>Cantidad de Publicaciones:</strong> <?php echo $cantidadPublicaciones; ?></p>
    <div class="text-center">
        <a href="#" class="btn btn-primary mt-3" data-toggle="modal" data-target="#editarFotoModal">Editar Foto de Perfil</a>
        <a href="GenerarPDFController.php" target="_blank" class="btn btn-secondary mt-3">Descargar la información en PDF</a>
    </div>
</div>

<div class="modal fade" id="editarFotoModal" tabindex="-1" role="dialog" aria-labelledby="editarFotoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarFotoModalLabel">Editar Foto de Perfil</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="ActualizarFotoController.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="nuevaFoto">Selecciona una nueva foto:</label>
            <input type="file" class="form-control-file" id="nuevaFoto" name="nuevaFoto" accept="image/*" required>
          </div>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="container content mt-3">
    <div class="h3">Mis publicaciones:</div>

    <?php 

    if ($cantidadPublicaciones > 0) {
      foreach ($publicaciones as $publicacion):    
    ?>
    <div class="publicacion mt-4">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <input type="hidden" name="eliminarPublicacion" value="<?php echo $publicacion["id"]; ?>">
          <button type="submit" style="border: none;" class="float-right"><span class="badge rounded-pill bg-danger p-2 text-white">Eliminar Publicación</span></button>
        </form>
        <p class="fecha"><span class="badge rounded-pill bg-primary p-2 text-white">Fecha de subida: <?php echo $publicacion["fecha_publicacion"]; ?></span></p>
        <p class="contenido mt-4"><?php echo $publicacion["contenido"]; ?></p>
    </div>
    <?php endforeach;     }else{?>
      <p>Actualmente no tienes ningúna publicación.</p>
    <?php }?>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
