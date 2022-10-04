<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
if (!empty($_POST)) {
    $alert = "";
    $mensaje = "";
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    if (empty($codigo) || empty($producto) || empty($precio) || $precio <  0 || empty($cantidad) || $cantidad <  0) {
        $mensaje = 'Todo los campos son obligatorios';
        $alert = 'danger';
    } else {
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $mensaje = 'El codigo ya existe';
                $alert = 'danger';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,precio,existencia) values ('$codigo', '$producto', '$precio', '$cantidad')");
                if ($query_insert) {
                    $mensaje = 'Producto registrado';
                    $alert = 'success';
                } else {
                    $mensaje = 'Error al registrar el producto';
                    $alert = 'danger';
                }
            }
        } else {
            $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', precio= $precio, existencia = $cantidad WHERE codproducto = $id");
            if ($query_update) {
                $mensaje = 'Producto Modificado';
                $alert = 'success';
            } else {
                $mensaje = 'Error al modificar';
                $alert = 'danger';
            }
        }
    }
}
include_once "includes/header.php";
?>
<button class="btn btn-primary mb-2" id="nuevoRegistro">Nuevo Producto</button>

<div class="card">
    <div class="card-body">
    <?php if (isset($mensaje)) {
            echo '<div class="alert alert-' . $alert . ' alert-style-light" role="alert">
                <i class="fas fa-times-circle"></i> ' . $mensaje . '
            </div>';
        } ?>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tbl">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../conexion.php";

                        $query = mysqli_query($conexion, "SELECT * FROM producto");
                        $result = mysqli_num_rows($query);
                        if ($result > 0) {
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td><?php echo $data['codproducto']; ?></td>
                                    <td><?php echo $data['codigo']; ?></td>
                                    <td><?php echo $data['descripcion']; ?></td>
                                    <td><?php echo $data['precio']; ?></td>
                                    <td><?php echo $data['existencia']; ?></td>
                                    <td>
                                        <a href="#" id="<?php echo $data['codproducto']; ?>" class="btn btn-primary editarProducto"><i class='fas fa-edit'></i></a>

                                        <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                            <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<div id="modalFormulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title"></h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off" id="formulario">
                    <div class="form-group mb-2">
                        <label for="codigo" class="font-weight-bold"><i class="fas fa-barcode"></i> Código de Barras</label>
                        <input type="text" placeholder="Ingrese código de barras" name="codigo" id="codigo" class="form-control">
                        <input type="hidden" id="id" name="id">
                    </div>
                    <div class="form-group mb-2">
                        <label for="producto" class="font-weight-bold">Producto</label>
                        <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label for="precio" class="font-weight-bold">Precio</label>
                        <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                    </div>
                    <div class="form-group mb-3">
                        <label for="cantidad" class="font-weight-bold">Cantidad</label>
                        <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad">
                    </div>
                    <div class="float-end">
                        <input type="submit" value="Registrar" class="btn btn-primary" id="btnAccion">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>