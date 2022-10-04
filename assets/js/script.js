$(document).ready(function() {
    $("#tbl").DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json",
        },
        order: [
            [0, "desc"]
        ],
    });
    $(".confirmar").submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Esta seguro de eliminar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "SI, Eliminar!",
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
    $("#nom_cliente").autocomplete({
        minLength: 3,
        source: function(request, response) {
            $.ajax({
                url: "ajax.php",
                dataType: "json",
                data: {
                    q: request.term,
                },
                success: function(data) {
                    response(data);
                },
            });
        },
        select: function(event, ui) {
            $("#idcliente").val(ui.item.id);
            $("#nom_cliente").val(ui.item.label);
            $("#tel_cliente").val(ui.item.telefono);
            $("#dir_cliente").val(ui.item.direccion);
        },
    });
    $("#producto").autocomplete({
        minLength: 3,
        source: function(request, response) {
            $.ajax({
                url: "ajax.php",
                dataType: "json",
                data: {
                    pro: request.term,
                },
                success: function(data) {
                    response(data);
                },
            });
        },
        select: function(event, ui) {
            $("#id").val(ui.item.id);
            $("#producto").val(ui.item.value);
            $("#precio").val(ui.item.precio);
            $("#cantidad").focus();
        },
    });

    $("#btn_generar").click(function(e) {
        e.preventDefault();
        var rows = $("#tblDetalle tr").length;
        if (rows > 2) {
            var action = "procesarVenta";
            var id = $("#idcliente").val();
            $.ajax({
                url: "ajax.php",
                async: true,
                data: {
                    procesarVenta: action,
                    id: id,
                },
                success: function(response) {
                    const res = JSON.parse(response);
                    if (response != "error") {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Venta Generada",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        setTimeout(() => {
                            generarPDF(res.id_cliente, res.id_venta);
                            location.reload();
                        }, 300);
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Error al generar la venta",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    }
                },
                error: function(error) {},
            });
        } else {
            Swal.fire({
                position: "top-end",
                icon: "warning",
                title: "No hay producto para generar la venta",
                showConfirmButton: false,
                timer: 2000,
            });
        }
    });

    $("#nuevoRegistro").click(function() {
        $("#formulario").trigger("reset");
        $("#title").html("NUEVO REGISTRO");
        $("#btnAccion").val("Registrar");
        $("#id").val("");
        $("#modalFormulario").modal("show");
    });

    $(".editarCliente").click(function() {
        const id = $(this).attr("id");
        const action = "editarCliente";
        $.ajax({
            url: "ajax.php",
            type: "GET",
            async: true,
            data: {
                editarCliente: action,
                id: id,
            },
            success: function(response) {
                const datos = JSON.parse(response);
                $("#nombre").val(datos.nombre);
                $("#telefono").val(datos.telefono);
                $("#direccion").val(datos.direccion);
                $("#id").val(datos.idcliente);
                $("#btnAccion").val("Modificar");
                $("#title").html("MODIFICAR CLIENTE");
                $("#modalFormulario").modal("show");
            },
            error: function(error) {
                console.log(error);
            },
        });
    });

    $(".editarUsuario").click(function() {
        const action = "editarUsuario";
        const id = $(this).attr("id");
        $.ajax({
            url: "ajax.php",
            type: "GET",
            async: true,
            data: {
                editarUsuario: action,
                id: id,
            },
            success: function(response) {
                const datos = JSON.parse(response);
                $("#nombre").val(datos.nombre);
                $("#usuario").val(datos.usuario);
                $("#correo").val(datos.correo);
                $("#id").val(datos.idusuario);
                $("#btnAccion").val("Modificar");
                $("#title").html("MODIFICAR USUARIO");
                $("#modalFormulario").modal("show");
            },
            error: function(error) {
                console.log(error);
            },
        });
    });

    $(".editarProducto").click(function() {
        const id = $(this).attr("id");
        const action = "editarProducto";
        $.ajax({
            url: "ajax.php",
            type: "GET",
            async: true,
            data: {
                editarProducto: action,
                id: id,
            },
            success: function(response) {
                const datos = JSON.parse(response);
                $("#codigo").val(datos.codigo);
                $("#producto").val(datos.descripcion);
                $("#precio").val(datos.precio);
                $("#cantidad").val(datos.existencia);
                $("#id").val(datos.codproducto);
                $("#btnAccion").val("Modificar");
                $("#title").html("MODIFICAR PRODUCTO");
                $("#modalFormulario").modal("show");
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    if ($("#tblDetalle").length > 0) {
        listar();
    }
    $("#cantidad-venta").change(function(e) {
        e.preventDefault();
        const cant = $("#cantidad-venta").val();
        const precio = $("#precio").val();
        const total = cant * precio;
        $("#sub_total").val(total);
        if (cant > 0 && cant != "") {
            const id = $("#id").val();
            registrarDetalle(e, id, cant, precio);
            $("#producto").focus();
        } else {
            $("#cantidad-venta").focus();
            return false;
        }
    });
    //cambiar clave
    $("#btnCredenciales").click(function() {
        const actual = $("#actual").val();
        const nueva = $("#nueva").val();
        if (actual == "" || nueva == "") {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Los campos estan vacios",
                showConfirmButton: false,
                timer: 2000,
            });
        } else {
            const cambio = "pass";
            $.ajax({
                url: "ajax.php",
                type: "POST",
                data: {
                    actual: actual,
                    nueva: nueva,
                    cambio: cambio,
                },
                success: function(response) {
                    if (response == "ok") {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Contraseña modificado",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        document.querySelector("#frmPass").reset();
                        $("#nuevo_pass").modal("hide");
                    } else if (response == "dif") {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "La contraseña actual incorrecta",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Error al modificar la contraseña",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    }
                },
            });
        }
    });
});

function listar() {
    let html = "";
    let detalle = "detalle";
    $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
            detalle: detalle,
        },
        success: function(response) {
            response.forEach((row) => {
                html += `<tr>
                <td>${row["id"]}</td>
                <td>${row["descripcion"]}</td>
                <td>${row["cantidad"]}</td>
                <td>${row["precio_venta"]}</td>
                <td>${row["sub_total"]}</td>
                <td><button class="btn btn-danger eliminar-venta" type="button" id="${row["id"]}">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_venta").innerHTML = html;
            calcular();
            $('.eliminar-venta').click(function() {
                let detalle = "Eliminar";
                const id = $(this).attr("id");
                $.ajax({
                    url: "ajax.php",
                    data: {
                        id: id,
                        delete_detalle: detalle,
                    },
                    success: function(response) {
                        if (response == "restado") {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "Producto Descontado",
                                showConfirmButton: false,
                                timer: 2000,
                            });
                            $("#producto").val('');
                            $("#producto").focus();
                            listar();
                        } else if (response == "ok") {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "Producto Eliminado",
                                showConfirmButton: false,
                                timer: 2000,
                            });
                            $("#producto").val('');
                            $("#producto").focus();
                            listar();
                        } else {
                            Swal.fire({
                                position: "top-end",
                                icon: "error",
                                title: "Error al eliminar el producto",
                                showConfirmButton: false,
                                timer: 2000,
                            });
                        }
                    },
                });
            })
        },
    });
}

function registrarDetalle(e, id, cant, precio) {
    if (document.getElementById("producto").value != "") {
        if (id != null) {
            let action = "regDetalle";
            $.ajax({
                url: "ajax.php",
                type: "POST",
                dataType: "json",
                data: {
                    id: id,
                    cant: cant,
                    regDetalle: action,
                    precio: precio,
                },
                success: function(response) {
                    if (response == "registrado") {
                        $("#cantidad").val("");
                        $("#precio").val("");
                        $("#producto").val("");
                        $("#sub_total").val("");
                        $("#producto").focus();
                        listar();
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Producto Ingresado",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    } else if (response == "actualizado") {
                        $("#cantidad").val("");
                        $("#precio").val("");
                        $("#producto").val("");
                        $("#producto").focus();
                        listar();
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Producto Actualizado",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "Error al ingresar el producto",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    }
                },
            });
        }
    }
}

function calcular() {
    // obtenemos todas las filas del tbody
    var filas = document.querySelectorAll("#tblDetalle tbody tr");

    var total = 0;

    // recorremos cada una de las filas
    filas.forEach(function(e) {
        // obtenemos las columnas de cada fila
        var columnas = e.querySelectorAll("td");

        // obtenemos los valores de la cantidad y importe
        var importe = parseFloat(columnas[4].textContent);

        total += importe;
    });

    // mostramos la suma total
    var filas = document.querySelectorAll("#tblDetalle tfoot tr td");
    filas[1].textContent = total.toFixed(2);
}

function generarPDF(cliente, id_venta) {
    url = "pdf/generar.php?cl=" + cliente + "&v=" + id_venta;
    window.open(url, "_blank");
}