<?php
require "../conexion.php";
session_start();
$id_usuario = $_SESSION['idUser'];
$clientes = mysqli_query($conexion, "SELECT * FROM cliente");
$total['clientes'] = mysqli_num_rows($clientes);
$productos = mysqli_query($conexion, "SELECT * FROM producto");
$total['productos'] = mysqli_num_rows($productos);
$ventas = mysqli_query($conexion, "SELECT SUM(total) AS total FROM ventas WHERE id_usuario = $id_usuario");
$resultVenta = mysqli_fetch_assoc($ventas);
include_once "includes/header.php";
?>
<div class="row">
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-warning">
                        <i class="material-icons-outlined">group</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">Clientes</span>
                        <span class="widget-stats-amount"><?php echo $total['clientes']; ?></span>
                        <span class="widget-stats-info"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-danger">
                        <i class="material-icons-outlined">view_list</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">Productos</span>
                        <span class="widget-stats-amount"><?php echo $total['productos']; ?></span>
                        <span class="widget-stats-info"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-primary">
                        <i class="material-icons-outlined">paid</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">Total Ventas</span>
                        <span class="widget-stats-amount">$<?php echo number_format($resultVenta['total'], 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-success">
                        <i class="material-icons-outlined">task_alt</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">Ventas por Mes</span>
                        <span class="widget-stats-amount">$<?php echo number_format($resultVenta['total'], 2); ?></span>
                    </div>
                </div>
                <div class="widget-stats-chart">
                    <div id="widget-stats-chart1"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Productos con Stock Mínimo</h5>
            </div>
            <div class="card-body">
                <canvas id="stockMinimo">Your browser does not support the canvas element.</canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Top Productos</h5>
            </div>
            <div class="card-body">
                <canvas id="topProductos">Your browser does not support the canvas element.</canvas>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>