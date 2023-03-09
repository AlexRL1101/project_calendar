<?php
session_start();

$_SESSION['idusuario'] = 2;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario Web</title>
    <link rel="stylesheet" href="./css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./fullcalendar/lib/main.min.css">
    <link rel="stylesheet" href="./css/style.css">

    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./fullcalendar/lib/main.min.js"></script>
    <script src="js/bootbox.min.js"></script>
    <script src="./fullcalendar/lib/locales/es.js"></script>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
        <div class="container">
            <a class="navbar-brand" href="#">
                Calendario
            </a>

            <div>
                <b class="text-light">Calendario de Eventos</b>
            </div>
        </div>
    </nav>
    <div class="container py-5" id="page-container">
        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
                <div class="cardt rounded-0 shadow">
                    <div class="card-header bg-gradient bg-primary text-light">
                        <h5 class="card-title">Eventos</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form method="POST" id="schedule-form" name="schedule-form">
                                <input type="hidden" name="id" value="">
                                <div class="form-group mb-2">
                                    <label for="title" class="control-label">Titulo</label>
                                    <input type="text" class="form-control form-control-sm rounded-0" name="title" id="title" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="description" class="control-label">Descripción</label>
                                    <textarea rows="3" class="form-control form-control-sm rounded-0" name="description" id="description" required></textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="color" class="control-label">Color</label>
                                    <input class="form-control" id="color" type="color" name="color">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="start_datetime" class="control-label">Inicio</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="start_datetime" id="start_datetime" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="end_datetime" class="control-label">Fin</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="end_datetime" id="end_datetime" required>
                                </div>
                                <input type="hidden" id="idbitacora_repetir" name="idbitacora_repetir" />

                                <div class="form-group mb-2">
                                    <label for="end_datetime" class="control-label">Repetir cada</label>
                                    <input type="number" for="variacion-repite" class="form-control form-control-sm rounded-0" min="0" value="3" id="numero_repite" name="numero_repite" required />
                                </div>
                                <div class="form-group mb-2">
                                    <select for="select-repite" class="form-control form-control-sm rounded-0" id="opciones_repetir" name="opciones_repetir" required>
                                        <option value="Minutos">Minutos</option>
                                        <option value="Horas">Horas</option>
                                        <option value="Dias">Dias</option>
                                        <option value="Semanas">Semanas</option>
                                        <option value="Meses">Meses</option>
                                    </select>
                                </div>

                                <div class="form-group mb-2">
                                    <label for="end_datetime" class="control-label">Durante (meses)</label>
                                    <input type="number" for="variacion-repite" class="form-control form-control-sm rounded-0" min="0" placeholder="Cantidad de meses" id="durante_tiempo" name="durante_tiempo" />
                                </div>

                                <div class="form-group mb-2">
                                    <label for="end_datetime" class="control-label">Notifica faltando</label>
                                    <input type="number" for="variacion-repite" class="form-control form-control-sm rounded-0" min="0" value="5" id="otra_tiempo_notifica" name="otra_tiempo_notifica" required />
                                </div>
                                <div class="form-group mb-2">
                                    <select for="select-repite" class="form-control form-control-sm rounded-0" id="notifica_antes" name="notifica_antes" required>
                                        <option value="Minutos">Minutos</option>
                                        <option value="Horas">Horas</option>
                                        <option value="Dias">Dias</option>
                                        <option value="Semanas">Semanas</option>
                                        <option value="Meses">Meses</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header rounded-0">
                    <h5 class="modal-title">Detalles del evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded-0">
                    <div class="container-fluid">
                        <dl>
                            <dt class="text-muted">Titulo</dt>
                            <dd id="title" class="fw-bold fs-4"></dd>
                            <dt class="text-muted">Descripcion</dt>
                            <dd id="description" class=""></dd>
                            <dt class="text-muted">Color</dt>
                            <input type="color" id="color" class="" />
                            <dt class="text-muted">Inicio</dt>
                            <dd id="start" class=""></dd>
                            <dt class="text-muted">Fin</dt>
                            <dd id="end" class=""></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <div class="text-end">
                        <button type="button" class="btn btn-warning btn-sm rounded-0" id="edit" data-id="">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Eliminar</button>
                        <button type="button" class="btn btn-info btn-sm rounded-0" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->

</body>
<script src="./js/script.js"></script>

</html>