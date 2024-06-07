<?php
session_start();

if (!isset($_SESSION["usuario"])) {
  header("Location: LoginController.php");
  exit();
}

include '../models/Conexion.php';

$usuario = $_SESSION["usuario"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_FILES["nuevaFoto"]) && $_FILES["nuevaFoto"]["error"] === 0) {
    $foto = $_FILES["nuevaFoto"]["tmp_name"];
    $fotoNombre = $_FILES["nuevaFoto"]["name"];
    
    $directorioImagenes = "../assets/images/";
    $rutaFoto = $directorioImagenes . $fotoNombre;
    move_uploaded_file($foto, $rutaFoto);
    
    $sql = "UPDATE usuarios SET foto_perfil = '$rutaFoto' WHERE id = $usuario";
    if ($conn->query($sql) === TRUE) {
      header("Location: InformacionUsuarioController.php");
      exit();
    } else {
      echo "Error al actualizar la foto de perfil: " . $conn->error;
    }
  } else {
    echo "No se ha seleccionado una nueva foto.";
  }
} else {
  header("Location: InformacionUsuarioController.php");
  exit();
}
