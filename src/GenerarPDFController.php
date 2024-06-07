<?php
require_once('../lib/tcpdf/tcpdf.php');

session_start();

if (!isset($_SESSION["usuario"])) {
  header("Location: LoginController.php");
  exit();
}

include '../models/Conexion.php';

$usuario = $_SESSION["usuario"];

$sql = "SELECT * FROM publicaciones WHERE usuario_id = $usuario ORDER BY fecha_publicacion DESC";
$result = $conn->query($sql);

$publicaciones = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $publicaciones[] = $row;
  }
}

$sql = "SELECT * FROM usuarios WHERE id = {$_SESSION["usuario"]}";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
  $row = $result->fetch_assoc();
  $nombre = $row["nombre"];
  $email = $row["email"];
  $fechaRegistro = $row["fecha_registro"];
  $fotoPerfil = $row["foto_perfil"];
} else {
  echo "No se encontró ninguna información de este usuario.";
  exit();
}

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

$pdf->SetCreator('Mi Red Social');
$pdf->SetAuthor('Mi Red Social');
$pdf->SetTitle('Información de Usuario');

$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 14);

$pdf->Cell(0, 10, 'Información de Usuario', 0, 1, 'C');

$pdf->Ln(10);

$pdf->SetFont('helvetica', '', 12);

if ($fotoPerfil != "") {
  $imagePath = "imagenes/" . $fotoPerfil; // Ruta de la imagen de perfil
  $pdf->Image($imagePath, 15, 40, 40, 40, '', '', '', false, 300, '', false, false, 0);
}

// Nombre
$pdf->Cell(0, 10, 'Nombre: ' . $nombre, 0, 1, 'C');

// Email
$pdf->Cell(0, 10, 'Email: ' . $email, 0, 1, 'C');

$pdf->Cell(0, 10, 'Fecha de Registro: ' . $fechaRegistro, 0, 1, 'C');

$pdf->Cell(0, 10, 'Cantidad de Publicaciones: ' . count($publicaciones), 0, 1, 'C');

$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);

if (count($publicaciones) > 0) {
  $pdf->Cell(0, 10, 'Mis publicaciones: ', 0, 1, 'L');
  $pdf->SetFont('helvetica', '', 10);

  foreach ($publicaciones as $publicacion) {
    $fechaPublicacion = $publicacion["fecha_publicacion"];
    $contenido = $publicacion["contenido"];
    
    $pdf->MultiCell(0, 10, 'Fecha: ' . $fechaPublicacion, 0, 'L');
    $pdf->MultiCell(0, 10, 'Contenido: ' . $contenido, 0, 'L');
    $pdf->Ln(5);
  }
}else{
  $pdf->Cell(0, 10, 'Sin publicaciones actualmente. ', 0, 1, 'L');
  $pdf->SetFont('helvetica', '', 10);
}


$nombreArchivo = 'perfil_' . strtolower($nombre) . '.pdf';

$pdf->Output($nombreArchivo, 'D');
