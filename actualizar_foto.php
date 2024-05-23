<?php
session_start();

if (!isset($_SESSION["usuario"])) {
  header("Location: login.php");
  exit();
}

include 'conexion.php';

$usuario = $_SESSION["usuario"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Verificar si se ha seleccionado una nueva foto
  if (isset($_FILES["nuevaFoto"]) && $_FILES["nuevaFoto"]["error"] === 0) {
    $foto = $_FILES["nuevaFoto"]["tmp_name"];
    $fotoNombre = $_FILES["nuevaFoto"]["name"];
    
    // Mover la foto al directorio de imágenes
    $directorioImagenes = "imagenes/";
    $rutaFoto = $directorioImagenes . $fotoNombre;
    move_uploaded_file($foto, $rutaFoto);
    
    // Actualizar la foto de perfil en la base de datos
    $sql = "UPDATE usuarios SET foto_perfil = '$rutaFoto' WHERE id = $usuario";
    if ($conn->query($sql) === TRUE) {
      // Redireccionar a la página de información de usuario
      header("Location: informacion_usuario.php");
      exit();
    } else {
      echo "Error al actualizar la foto de perfil: " . $conn->error;
    }
  } else {
    echo "No se ha seleccionado una nueva foto.";
  }
} else {
  header("Location: informacion_usuario.php");
  exit();
}
