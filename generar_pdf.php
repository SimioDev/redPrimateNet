<?php
require_once('tcpdf/tcpdf.php');

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

// Crear nueva instancia de TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

// Establecer metadatos del PDF
$pdf->SetCreator('Mi Red Social');
$pdf->SetAuthor('Mi Red Social');
$pdf->SetTitle('Información de Usuario');

// Agregar página
$pdf->AddPage();

// Establecer estilo de fuente
$pdf->SetFont('helvetica', 'B', 14);

// Título del PDF
$pdf->Cell(0, 10, 'Información de Usuario', 0, 1, 'C');

// Espacio
$pdf->Ln(10);

// Establecer estilo de fuente para los datos
$pdf->SetFont('helvetica', '', 12);

// Mostrar imagen de perfil
if ($fotoPerfil != "") {
  $imagePath = "imagenes/" . $fotoPerfil; // Ruta de la imagen de perfil
  $pdf->Image($imagePath, 15, 40, 40, 40, '', '', '', false, 300, '', false, false, 0);
}

// Nombre
$pdf->Cell(0, 10, 'Nombre: ' . $nombre, 0, 1, 'C');

// Email
$pdf->Cell(0, 10, 'Email: ' . $email, 0, 1, 'C');

// Fecha de Registro
$pdf->Cell(0, 10, 'Fecha de Registro: ' . $fechaRegistro, 0, 1, 'C');

// Cantidad de Publicaciones
$pdf->Cell(0, 10, 'Cantidad de Publicaciones: ' . count($publicaciones), 0, 1, 'C');

// Mostrar publicaciones
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Mis publicaciones: ', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

foreach ($publicaciones as $publicacion) {
  $fechaPublicacion = $publicacion["fecha_publicacion"];
  $contenido = $publicacion["contenido"];
  
  $pdf->MultiCell(0, 10, 'Fecha: ' . $fechaPublicacion, 0, 'L');
  $pdf->MultiCell(0, 10, 'Contenido: ' . $contenido, 0, 'L');
  $pdf->Ln(5);
}

// Generar nombre del archivo
$nombreArchivo = 'perfil_' . strtolower($nombre) . '.pdf';

// Salida del PDF con el nombre de archivo personalizado
$pdf->Output($nombreArchivo, 'D');
