<?php
session_start();

if (!isset($_SESSION["usuario"])) {
  header("Location: LoginController.php");
  exit();
}

include '../models/Conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $publicacion_id = $_POST["publicacion_id"];
  $usuario_id = $_SESSION["usuario"]["id"];

  // Verificar si el usuario ya dio "me gusta" a la publicación
  $sql = "SELECT * FROM likes WHERE usuario_id = '$usuario_id' AND publicacion_id = '$publicacion_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // El usuario ya dio "me gusta", eliminar el "me gusta"
    $sql = "DELETE FROM likes WHERE usuario_id = '$usuario_id' AND publicacion_id = '$publicacion_id'";
    $conn->query($sql);
  } else {
    // El usuario no ha dado "me gusta", agregar el "me gusta"
    $sql = "INSERT INTO likes (usuario_id, publicacion_id) VALUES ('$usuario_id', '$publicacion_id')";
    $conn->query($sql);
  }
}

// Redireccionar de vuelta a la página de panel.php después de dar "me gusta" o eliminar "me gusta"
header("Location: ../views/panel.php");
exit();
?>
