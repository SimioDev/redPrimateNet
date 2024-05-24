<?php
session_start();

if (!isset($_SESSION["usuario"])) {
  header("Location: LoginController.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  include '../models/Conexion.php';

  $contenido = $_POST["contenido"];
  $usuario_id = $_SESSION["usuario"];

  // Verificar si se ha subido una imagen
  if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
    $imagen = $_FILES["imagen"];
    $nombreArchivo = $imagen["name"];
    $rutaTemporal = $imagen["tmp_name"];
    $rutaDestino = "../assets/images/" . $nombreArchivo;

    // Mover la imagen de la ubicación temporal a la carpeta de imágenes
    if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
      // La imagen se ha movido correctamente, se puede guardar el nombre del archivo en la base de datos
      $sql = "INSERT INTO publicaciones (contenido, imagen, usuario_id, fecha_publicacion) VALUES ('$contenido', '$nombreArchivo', $usuario_id, NOW())";
    } else {
      echo "Error al subir la imagen.";
      exit();
    }
  } else {
    // No se ha subido ninguna imagen
    $sql = "INSERT INTO publicaciones (contenido, usuario_id, fecha_publicacion) VALUES ('$contenido', $usuario_id, NOW())";
  }

  if ($conn->query($sql) === TRUE) {
    header("Location: ../views/panel.php");
    exit();
  } else {
    echo "Error al crear la publicación: " . $conn->error;
  }

  $conn->close();
}
?>
