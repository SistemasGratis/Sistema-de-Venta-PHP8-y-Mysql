<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 0, 0);
$pdf->SetTitle("Ventas");
$pdf->SetFont('Arial', 'B', 12);
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
$ventas = mysqli_query($conexion, "SELECT d.*, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id");
$pdf->MultiCell(65, 5, utf8_decode($datos['nombre']), 0, 'C');
$pdf->image("../../assets/images/logo.png", 60, 15, 15, 15, 'PNG');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, $datos['telefono'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(40, 5, utf8_decode($datos['direccion']), 0, 'L');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(15, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(40, 5, utf8_decode($datos['email']), 0, 'L');
$pdf->Ln();

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, "==============================================", 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(70, 5, "Datos del cliente", 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, "==============================================", 0, 1, 'C');

$pdf->Cell(30, 5, utf8_decode('Nombre'), 0, 0, 'L');
$pdf->Cell(18, 5, utf8_decode('Teléfono'), 0, 0, 'L');
$pdf->Cell(25, 5, utf8_decode('Dirección'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(30, 5, utf8_decode($datosC['nombre']), 0, 0, 'L');
$pdf->Cell(18, 5, utf8_decode($datosC['telefono']), 0, 0, 'L');
$pdf->MultiCell(25, 5, utf8_decode($datosC['direccion']), 0, 'L');
$pdf->Ln(3);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, "==============================================", 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(70, 5, "Detalle de Producto", 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, "==============================================", 0, 1, 'C');

$pdf->Cell(30, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(10, 5, 'Cant.', 0, 0, 'L');
$pdf->Cell(15, 5, 'Precio', 0, 0, 'L');
$pdf->Cell(15, 5, 'Sub Total.', 0, 1, 'L');
$pdf->SetFont('Arial', '', 7);
$total = 0.00;
$desc = 0.00;
while ($row = mysqli_fetch_assoc($ventas)) {
    $pdf->Cell(30, 5, $row['descripcion'], 0, 0, 'L');
    $pdf->Cell(10, 5, $row['cantidad'], 0, 0, 'L');
    $pdf->Cell(15, 5, $row['precio'], 0, 0, 'L');
    $sub_total = $row['total'];
    $total = $total + $sub_total;
    $pdf->Cell(15, 5, number_format($sub_total, 2, '.', ','), 0, 1, 'L');
}
$pdf->Ln();
$pdf->Cell(70, 5, "=========================", 0, 1, 'R');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(70, 5, 'Total Pagar', 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(70, 5, number_format($total, 2, '.', ','), 0, 1, 'R');

$pdf->Output("ventas.pdf", "I");

?>