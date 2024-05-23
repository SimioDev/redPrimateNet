<?php
session_start();

if (!isset($_SESSION["usuario"])) {
  header("Location: login.php");
  exit();
}

include 'conexion.php';

$usuario = $_SESSION["usuario"];

// Obtener todas las publicaciones del usuario actual ordenadas por fecha de forma descendente
$sql = "SELECT * FROM publicaciones WHERE usuario_id = $usuario ORDER BY fecha_publicacion DESC";
$result = $conn->query($sql);

$publicaciones = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $publicaciones[] = $row;
  }
}

// Obtener la cantidad de publicaciones
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

// Procesar el borrado de una publicación si se ha enviado una solicitud
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["eliminarPublicacion"])) {
  $publicacionId = $_POST["eliminarPublicacion"];
  
  // Realizar la consulta SQL para eliminar la publicación
  $sql = "DELETE FROM publicaciones WHERE id = $publicacionId AND usuario_id = $usuario";
  
  if ($conn->query($sql) === TRUE) {
    // La publicación se eliminó correctamente
    header("Location: informacion_usuario.php");
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
    /* Estilos personalizados */
    body {
      background-color: #f0f2f5;
    }

    .navbar {
      background-color: #3b5998;
      color: #fff;
    }

    .navbar-brand {
      color: #fff;
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
      max-width: 800px; /* Ajusta el ancho máximo del contenido */
      margin: 0 auto; /* Centra el contenido en la página */
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

<nav class="navbar navbar-expand-md">
  <a class="navbar-brand" href="#"><b>PrimateNet</b></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="panel.php">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="informacion_usuario.php">Mi Perfil</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Cerrar Sesión</a>
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
        <a href="generar_pdf.php" target="_blank" class="btn btn-secondary mt-3">Descargar la información en PDF</a>
    </div>
</div>

<!-- Ventana emergente para editar la foto de perfil -->
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
        <form action="actualizar_foto.php" method="post" enctype="multipart/form-data">
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

    <?php foreach ($publicaciones as $publicacion): ?>
    <div class="publicacion mt-4">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <input type="hidden" name="eliminarPublicacion" value="<?php echo $publicacion["id"]; ?>">
          <button type="submit" class="btn btn-danger btn-sm float-right">Eliminar</button>
        </form>
        <p class="fecha"><?php echo $publicacion["fecha_publicacion"]; ?></p>
        <p class="contenido"><?php echo $publicacion["contenido"]; ?></p>
    </div>
    <?php endforeach; ?>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>
</html>
