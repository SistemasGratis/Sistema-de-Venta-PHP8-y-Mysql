<?php
session_start();
$permiso = 'usuarios';
$id_user = $_SESSION['idUser'];
include "../conexion.php";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
}
if (!empty($_POST)) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['correo'];
    $user = $_POST['usuario'];
    $mensaje = "";
    $alert = "";
    if (empty($nombre) || empty($email) || empty($user)) {
        $mensaje = 'Todo los campos son obligatorio';
        $alert = 'danger';
    } else {
        if (empty($id)) {
            $clave = $_POST['clave'];
            if (empty($clave)) {
                $mensaje = 'La contraseña es requerido';
                $alert = 'danger';
            } else {
                $clave = md5($_POST['clave']);
                $query = mysqli_query($conexion, "SELECT * FROM usuario where correo = '$email'");
                $result = mysqli_fetch_array($query);
                if ($result > 0) {
                    $mensaje = 'El correo ya existe';
                    $alert = 'danger';
                } else {
                    $query_insert = mysqli_query($conexion, "INSERT INTO usuario(nombre,correo,usuario,clave) values ('$nombre', '$email', '$user', '$clave')");
                    if ($query_insert) {
                        $mensaje = 'Usuario Registrado';
                        $alert = 'success';
                    } else {
                        $mensaje = 'Error al registrar';
                        $alert = 'danger';
                    }
                }
            }
        } else {
            $sql_update = mysqli_query($conexion, "UPDATE usuario SET nombre = '$nombre', correo = '$email' , usuario = '$user' WHERE idusuario = $id");
            if ($sql_update) {
                $mensaje = 'Usuario Modificado';
                $alert = 'success';
            } else {
                $mensaje = 'Error al modificar';
                $alert = 'danger';
            }
        }
    }
}
include "includes/header.php";
?>

<button class="btn btn-primary mb-2" type="button" id="nuevoRegistro">Nuevo usuario</button>

<div class="card">
    <div class="card-body">
    <?php if (isset($mensaje)) {
            echo '<div class="alert alert-' . $alert . ' alert-style-light" role="alert">
                <i class="fas fa-times-circle"></i> ' . $mensaje . '
            </div>';
        } ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered mt-2" id="tbl">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conexion, "SELECT * FROM usuario");
                    $result = mysqli_num_rows($query);
                    if ($result > 0) {
                        while ($data = mysqli_fetch_assoc($query)) { ?>
                            <tr>

                                <td><?php echo $data['idusuario']; ?></td>
                                <td><?php echo $data['nombre']; ?></td>
                                <td><?php echo $data['correo']; ?></td>
                                <td><?php echo $data['usuario']; ?></td>
                                <td>
                                    <a href="rol.php?id=<?php echo $data['idusuario']; ?>" class="btn btn-warning"><i class='fas fa-key'></i></a>
                                    <a href="#" id="<?php echo $data['idusuario']; ?>" class="btn btn-success editarUsuario"><i class='fas fa-edit'></i></a>
                                    <form action="eliminar_usuario.php?id=<?php echo $data['idusuario']; ?>" method="post" class="confirmar d-inline">
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
                    <div class="form-group">
                        <label for="nombre mb-2">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ingrese Nombre" name="nombre" id="nombre">
                        <input type="hidden" id="id" name="id">
                    </div>
                    <div class="form-group mb-2">
                        <label for="correo">Correo</label>
                        <input type="email" class="form-control" placeholder="Ingrese Correo Electrónico" name="correo" id="correo">
                    </div>
                    <div class="form-group mb-2">
                        <label for="usuario">Usuario</label>
                        <input type="text" class="form-control" placeholder="Ingrese Usuario" name="usuario" id="usuario">
                    </div>
                    <div class="form-group mb-3">
                        <label for="clave">Contraseña</label>
                        <input type="password" class="form-control" placeholder="Ingrese Contraseña" name="clave" id="clave">
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