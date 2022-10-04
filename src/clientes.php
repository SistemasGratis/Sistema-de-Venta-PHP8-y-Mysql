<?php
session_start();
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}

if (!empty($_POST)) {
    $mensaje = "";
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        $mensaje = 'Todo los campos son obligatorio';
        $alert = 'danger';
    } else {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $result = 0;
        if (empty($id)) {
            $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE nombre = '$nombre'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $mensaje = 'El cliente ya existe';
                $alert = 'danger';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO cliente(nombre,telefono,direccion) values ('$nombre', '$telefono', '$direccion')");
                if ($query_insert) {
                    $mensaje = 'Cliente registrado';
                    $alert = 'success';
                } else {
                    $mensaje = 'Error al registrar';
                    $alert = 'danger';
                }
            }
        } else {
            $sql_update = mysqli_query($conexion, "UPDATE cliente SET nombre = '$nombre' , telefono = '$telefono', direccion = '$direccion' WHERE idcliente = $id");
            if ($sql_update) {
                $mensaje = 'Cliente Modificado';
                $alert = 'success';
            } else {
                $mensaje = 'Error al modificar';
                $alert = 'danger';
            }
        }
    }
    mysqli_close($conexion);
}
include_once "includes/header.php";
?>
<button class="btn btn-primary mb-3" type="button" id="nuevoRegistro">Nuevo Cliente</button>
<div class="card">
    <div class="card-body">
        <?php if (isset($mensaje)) {
            echo '<div class="alert alert-' . $alert . ' alert-style-light" role="alert">
                <i class="fas fa-times-circle"></i> ' . $mensaje . '
            </div>';
        } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tbl">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "../conexion.php";

                            $query = mysqli_query($conexion, "SELECT * FROM cliente");
                            $result = mysqli_num_rows($query);
                            if ($result > 0) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $data['idcliente']; ?></td>
                                        <td><?php echo $data['nombre']; ?></td>
                                        <td><?php echo $data['telefono']; ?></td>
                                        <td><?php echo $data['direccion']; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-primary editarCliente" id="<?php echo $data['idcliente']; ?>"><i class='fas fa-edit'></i></a>
                                            <form action="eliminar_cliente.php?id=<?php echo $data['idcliente']; ?>" method="post" class="confirmar d-inline">
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
</div>
<div id="modalFormulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Nuevo Cliente</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off" id="formulario">
                    <div class="form-group mb-2">
                        <label for="nombre" class="font-weight-bold">Nombre</label>
                        <input type="text" placeholder="Ingrese Nombre" name="nombre" id="nombre" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label for="telefono" class="font-weight-bold">Teléfono</label>
                        <input type="number" placeholder="Ingrese Teléfono" name="telefono" id="telefono" class="form-control">
                        <input type="hidden" name="id" id="id">
                    </div>
                    <div class="form-group mb-3">
                        <label for="direccion" class="font-weight-bold">Dirección</label>
                        <input type="text" placeholder="Ingrese Direccion" name="direccion" id="direccion" class="form-control">
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