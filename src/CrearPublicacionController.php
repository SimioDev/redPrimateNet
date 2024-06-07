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

  if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
    $imagen = $_FILES["imagen"];
    $nombreArchivo = $imagen["name"];
    $rutaTemporal = $imagen["tmp_name"];
    $rutaDestino = "../assets/images/" . $nombreArchivo;

    if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
      $sql = "INSERT INTO publicaciones (contenido, imagen, usuario_id, fecha_publicacion) VALUES ('$contenido', '$nombreArchivo', $usuario_id, NOW())";
    } else {
      echo "Error al subir la imagen.";
      exit();
    }
  } else {
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
