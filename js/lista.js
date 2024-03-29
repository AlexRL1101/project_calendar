function init() {
    // Datapicker 
    $(".datepicker").datepicker({
        "dateFormat": "yy-mm-dd",
        changeYear: true
    });

    $("#btn_search").on("click", function(e) {
        let buscar_inicio = $('#buscar_inicio').val(),
            buscar_fin = $('#buscar_fin').val();
        listar(buscar_inicio, buscar_fin)
    });

    listar();

}

function listar(buscar_inicio, buscar_fin) {

    /* Inicializacion de  Datatables */
    let table = $('#Tabla_personal').DataTable({
        lengthMenu: [5, 10, 25, 75, 100], //mostramos el menú de registros a revisar
        aProcessing: true, //Activamos el procesamiento del datatables
        aServerSide: true, //Paginación y filtrado realizados por el servidor
        dom: "<Bl<f>rtip>", //Definimos los elementos del control de tabla
        buttons: [],
        ajax: {
            url: '../ajax/events.php?op=listar',
            data: { buscar_inicio: buscar_inicio, buscar_fin: buscar_fin },
            type: "POST",
        },
        columns: [
            { data: 'titulo' },
            { data: 'descripcion' },
            { data: 'fecha_inicio' },
            { data: 'departamento' },
        ],
        language: {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron datos",
            "info": "Mostrando _PAGE_ de _PAGES_",
            "infoEmpty": "No se encontraron registros disponibles",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "search": "Buscar",
        },
        bDestroy: true,
        iDisplayLength: 10, //Paginación
        // "bStateSave": true,
        order: [
            [0, "ASC"]
        ], //Ordenar (columna,orden)
    });
}

init();