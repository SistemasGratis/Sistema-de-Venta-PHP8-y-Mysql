<?php
include("../conexion.php");
session_start();
$id_usuario = $_SESSION['idUser'];
if ($_POST['action'] == 'ventasTotal') {
    $enero = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 1 AND id_usuario = $id_usuario");
    $febrero = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 2 AND id_usuario = $id_usuario");
    $marzo = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 3 AND id_usuario = $id_usuario");
    $abril = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 4 AND id_usuario = $id_usuario");
    $mayo = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 5 AND id_usuario = $id_usuario");
    $junio = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 6 AND id_usuario = $id_usuario");
    $julio = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 7 AND id_usuario = $id_usuario");
    $agosto = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 8 AND id_usuario = $id_usuario");
    $septiembre = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 9 AND id_usuario = $id_usuario");
    $octubre = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 10 AND id_usuario = $id_usuario");
    $noviembre = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 11 AND id_usuario = $id_usuario");
    $diciembre = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas where month(fecha) = 12 AND id_usuario = $id_usuario");
    
    $ene = mysqli_fetch_assoc($enero);
    $feb = mysqli_fetch_assoc($febrero);
    $mar = mysqli_fetch_assoc($marzo);
    $abr = mysqli_fetch_assoc($abril);
    $may = mysqli_fetch_assoc($mayo);
    $jun = mysqli_fetch_assoc($junio);
    $jul = mysqli_fetch_assoc($julio);
    $ago = mysqli_fetch_assoc($agosto);
    $sep = mysqli_fetch_assoc($septiembre);
    $oct = mysqli_fetch_assoc($octubre);
    $nov = mysqli_fetch_assoc($noviembre);
    $dic = mysqli_fetch_assoc($diciembre);

    $res['ene'] = ($ene['total'] == null) ? number_format(0, 2, '.', '') : number_format($ene['total'], 2, '.', '');
    $res['feb'] = ($feb['total'] == null) ? number_format(0, 2, '.', '') : number_format($feb['total'], 2, '.', '');
    $res['mar'] = ($mar['total'] == null) ? number_format(0, 2, '.', '') : number_format($mar['total'], 2, '.', '');
    $res['abr'] = ($abr['total'] == null) ? number_format(0, 2, '.', '') : number_format($abr['total'], 2, '.', '');
    $res['may'] = ($may['total'] == null) ? number_format(0, 2, '.', '') : number_format($may['total'], 2, '.', '');
    $res['jun'] = ($jun['total'] == null) ? number_format(0, 2, '.', '') : number_format($jun['total'], 2, '.', '');
    $res['jul'] = ($jul['total'] == null) ? number_format(0, 2, '.', '') : number_format($jul['total'], 2, '.', '');
    $res['ago'] = ($ago['total'] == null) ? number_format(0, 2, '.', '') : number_format($ago['total'], 2, '.', '');
    $res['sep'] = ($sep['total'] == null) ? number_format(0, 2, '.', '') : number_format($sep['total'], 2, '.', '');
    $res['oct'] = ($oct['total'] == null) ? number_format(0, 2, '.', '') : number_format($oct['total'], 2, '.', '');
    $res['nov'] = ($nov['total'] == null) ? number_format(0, 2, '.', '') : number_format($nov['total'], 2, '.', '');
    $res['dic'] = ($dic['total'] == null) ? number_format(0, 2, '.', '') : number_format($dc['total'], 2, '.', '');
    echo json_encode($res);
    die();
}
if ($_POST['action'] == 'sales') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT descripcion, existencia FROM producto WHERE existencia <= 10 ORDER BY existencia ASC LIMIT 7");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}
if ($_POST['action'] == 'topProductos') {
    $arreglo = array();
    $query = mysqli_query($conexion, "SELECT SUM(d.cantidad) as total, p.descripcion FROM detalle_venta d INNER JOIN producto p WHERE d.id_producto = p.codproducto group by d.id_producto , p.descripcion ORDER BY total DESC LIMIT 4");
    while ($data = mysqli_fetch_array($query)) {
        $arreglo[] = $data;
    }
    echo json_encode($arreglo);
    die();
}

?>
