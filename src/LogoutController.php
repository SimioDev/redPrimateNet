<?php
// Cerrar sesión y redirigir a index.html
session_start();
session_destroy();
header("Location: ../index.php");
exit();
?>
