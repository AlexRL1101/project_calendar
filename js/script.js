function init() {
    getEvents();
    getNotification();

    $("#schedule-form").on("submit", function(e) {
        saveEvents(e);
    });

    setInterval(function() { getNotification(); }, 10000);

    $('#edit').click(function() {
        let id = $(this).attr('data-id');
        editEvents(id);
    });

    $('#delete').click(function() {
        let id = $(this).attr('data-id');
        deleteEvents(id);
    });

    $('#hecho').click(function() {
        let id = $(this).attr('data-id');
        finishEvent(id);
    });
}

function getEvents() {
    var calendar;
    var Calendar = FullCalendar.Calendar;
    var events = [];

    $.ajax({
        url: "ajax/events.php?op=obtenerEventos",
        type: "JSON",
        success: function(scheds) {
            var scheds = $.parseJSON(scheds);

            if (!!scheds) {
                Object.keys(scheds).map(k => {
                    var row = scheds[k]
                    events.push({ id: row.id, title: row.title, start: row.start_datetime, end: row.end_datetime, color: row.color, dpto: row.dpto }); /////////////
                })
            }

            var date = new Date()
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear()

            calendar = new Calendar(document.getElementById('calendar'), {
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    right: 'dayGridMonth,dayGridWeek,list',
                    center: 'title',
                },
                selectable: true,
                themeSystem: 'bootstrap',
                //Random default events
                events: events,
                eventClick: function(info) {
                    var _details = $('#event-details-modal')
                    var id = info.event.id
                    if (!!scheds[id]) {
                        _details.find('#title').text(scheds[id].title)
                        _details.find('#description').text(scheds[id].description)
                        _details.find('#color').val(scheds[id].color)
                        _details.find('#start').text(scheds[id].sdate)
                        _details.find('#end').text(scheds[id].edate)
                        _details.find('#dpto').text(scheds[id].dpto) ////////
                        _details.find('#edit,#edit').attr('data-id', id)
                        _details.find('#edit,#delete, #hecho').attr('data-id', id)
                        _details.modal('show')

                        let usuario_creador = scheds[id].idusuario,
                            session_actual = $("#idusuario").val();
                        if (usuario_creador != session_actual) {
                            $("#hecho").hide();
                            $("#edit").hide();
                            $("#delete").hide();
                        } else {
                            $("#hecho").show();
                            $("#edit").show();
                            $("#delete").show();
                        }
                    } else {
                        alert("Event is undefined");
                    }
                },
                eventDidMount: function(info) {
                    // Do Something after events mounted
                },
                editable: true
            });

            calendar.render();

            // Form reset listener
            $('#schedule-form').on('reset', function() {
                $(this).find('input:hidden').val('')
                $(this).find('input:visible').first().focus()
            })

        }

    });
}

function saveEvents(e) {
    e.preventDefault();
    var formData = new FormData($("#schedule-form")[0]);
    $.ajax({
        url: "ajax/events.php?op=guardarEvento",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            bootbox.alert({
                title: "Mensaje",
                message: datos,
                callback: function() {
                    var _details = $('#event-details-modal')
                    _details.modal('hide');
                    $('#schedule-form').trigger("reset");
                    getEvents();
                }
            });
        }

    });
}

function getNotification() {
    if (!Notification) {
        bootbox.alert('Este navegador no soporta las notificaciones')
        return;
    }

    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    } else {
        $.ajax({
            url: "./ajax/events.php?op=traeFechasNotificaciones",
            type: "POST",
            success: function(res, textStatus, jqXHR) {
                if (res != 300) {
                    let respuesta = jQuery.parseJSON(res);

                    if (respuesta.result == true) {

                        let notificationDetails = respuesta.notif;

                        for (let i = notificationDetails.length - 1; i >= 0; i--) {

                            let notificationUrl = notificationDetails[i]['url'],
                                notificationObj = new Notification(notificationDetails[i]['title'], {
                                    icon: notificationDetails[i]['icon'],
                                    body: notificationDetails[i]['message'],
                                });

                            notificationObj.onclick = function() {
                                window.open(notificationUrl);
                                notificationObj.close();
                            };
                        };
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }
};

function editEvents(id) {
    let _form = $('#schedule-form')
    $.ajax({
        url: "ajax/events.php?op=obtenerEvento",
        type: "POST",
        data: { id: id },
        success: function(datos) {
            var datos = $.parseJSON(datos);
            _form.find('[name="id"]').val(id)
            _form.find('[name="title"]').val(datos[id].title)
            _form.find('[name="description"]').val(datos[id].description)
            _form.find('[name="color"]').val(datos[id].color)
            _form.find('[name="start_datetime"]').val(String(datos[id].start_datetime).replace(" ", "T"))
            _form.find('[name="end_datetime"]').val(String(datos[id].end_datetime).replace(" ", "T"))
            _form.find('[name="dpto"]').val(datos[id].dpto) //////////////

            _form.find('[name="numero_repite"]').val(datos[id].repite)
            _form.find('[name="opciones_repetir"]').val(datos[id].formato_repite)
            _form.find('[name="otra_tiempo_notifica"]').val(datos[id].notifica)
            _form.find('[name="notifica_antes"]').val(datos[id].formato_notifica)
            _form.find('[name="idbitacora_repetir"]').val(datos[id].idbitacora_repetir)

            $('#event-details-modal').modal('hide')
            _form.find('[name="title"]').focus()

        }
    });
}


function deleteEvents(id) {
    bootbox.confirm('Desea realmente eliminar este evento?', function(result) {
        if (result === true) {
            $('#event-details-modal').modal('hide')

            $.ajax({
                url: "ajax/events.php?op=eliminarEvento",
                type: "POST",
                data: { id: id },
                success: function(datos) {
                    bootbox.alert({
                        title: "Mensaje",
                        message: datos,
                        callback: function() {
                            getEvents();
                        }
                    });
                }

            });
        }
    });
}

function finishEvent(id) {
    $('#event-details-modal').modal('hide')
    $.ajax({
        url: "ajax/events.php?op=finalizarEvento",
        type: "POST",
        data: { id: id },
        success: function(datos) {
            bootbox.alert({
                title: "Mensaje",
                message: datos,
                callback: function() {
                    getEvents();
                }
            });
        }
    });
}


init();