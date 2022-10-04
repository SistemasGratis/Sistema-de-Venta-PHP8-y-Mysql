$(document).ready(function() {
    "use strict";
    if ($('#widget-stats-chart1').length > 0) {
        ventasMes();
        stockMinimo();
        topProductos();
    }
});

function ventasMes() {
    //ventas
    $.ajax({
        url: "chart.php",
        type: "POST",
        data: {
            action: "ventasTotal",
        },
        async: true,
        success: function(response) {
            const res = JSON.parse(response);
            console.log(res);
            var options2 = {
                chart: {
                    id: "sparkline1",
                    type: "area",
                    height: 150,
                },
                series: [{
                    name: "Sales",
                    data: [
                        res.ene,
                        res.feb,
                        res.mar,
                        res.abr,
                        res.may,
                        res.jun,
                        res.jul,
                        res.ago,
                        res.sep,
                        res.oct,
                        res.nov,
                        res.dic,
                    ],
                }, ],
                labels: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                colors: ["#FFDDB8"],
            };

            var chart2 = new ApexCharts(
                document.querySelector("#widget-stats-chart1"),
                options2
            );
            chart2.render();
        },
        error: function(error) {
            console.log(error);
        },
    });
}

function stockMinimo() {
    //stock minimo
    $.ajax({
        url: "chart.php",
        type: "POST",
        data: {
            action: "sales",
        },
        async: true,
        success: function(response) {
            if (response != 0) {
                var data = JSON.parse(response);
                var nombre = [];
                var cantidad = [];
                for (var i = 0; i < data.length; i++) {
                    nombre.push(data[i]["descripcion"]);
                    cantidad.push(data[i]["existencia"]);
                }
                new Chart(document.getElementById("stockMinimo"), {
                    type: "bar",
                    data: {
                        labels: nombre,
                        datasets: [{
                            label: "Productos con stock mínimo",
                            data: cantidad,
                            fill: false,
                            backgroundColor: [
                                "rgba(255, 99, 132, 0.2)",
                                "rgba(255, 159, 64, 0.2)",
                                "rgba(255, 205, 86, 0.2)",
                                "rgba(75, 192, 192, 0.2)",
                                "rgba(54, 162, 235, 0.2)",
                                "rgba(153, 102, 255, 0.2)",
                                "rgba(201, 203, 207, 0.2)",
                            ],
                            borderColor: [
                                "rgb(255, 99, 132)",
                                "rgb(255, 159, 64)",
                                "rgb(255, 205, 86)",
                                "rgb(75, 192, 192)",
                                "rgb(54, 162, 235)",
                                "rgb(153, 102, 255)",
                                "rgb(201, 203, 207)",
                            ],
                            borderWidth: 1,
                        }, ],
                    },
                    options: { scales: { yAxes: [{ ticks: { beginAtZero: true } }] } },
                });
            }
        },
        error: function(error) {
            console.log(error);
        },
    });
}

function topProductos() {
    //top productos
    $.ajax({
        url: "chart.php",
        type: "POST",
        data: {
            action: "topProductos",
        },
        async: true,
        success: function(response) {
            if (response != 0) {
                var data = JSON.parse(response);
                var nombre = [];
                var cantidad = [];
                for (var i = 0; i < data.length; i++) {
                    nombre.push(data[i]["descripcion"]);
                    cantidad.push(data[i]["total"]);
                }
                new Chart(document.getElementById("topProductos"), {
                    type: "doughnut",
                    data: {
                        labels: nombre,
                        datasets: [{
                            label: "Productos",
                            data: cantidad,
                            backgroundColor: [
                                "rgb(255, 99, 132)",
                                "rgb(54, 162, 235)",
                                "rgb(255, 205, 86)",
                                "rgb(0, 205, 86)",
                            ],
                        }, ],
                    },
                });
            }
        },
        error: function(error) {
            console.log(error);
        },
    });
}