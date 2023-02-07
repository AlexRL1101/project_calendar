let divRepetir = $('#repetir_accion');

function init() {
    getEvents();
    $(divRepetir).hide();

    $("#schedule-form").on("submit", function (e) {
        saveEvents(e);
    })
}

function getEvents() {
    var calendar;
    var Calendar = FullCalendar.Calendar;
    var events = [];

    $.ajax({
        url: "ajax/events.php?op=getEvents",
        type: "JSON",
        success: function (scheds) {
            var scheds = $.parseJSON(scheds);

            if (!!scheds) {
                Object.keys(scheds).map(k => {
                    var row = scheds[k]
                    events.push({ id: row.id, title: row.title, start: row.start_datetime, end: row.end_datetime, color: row.color });
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
                eventClick: function (info) {
                    var _details = $('#event-details-modal')
                    var id = info.event.id
                    if (!!scheds[id]) {
                        _details.find('#title').text(scheds[id].title)
                        _details.find('#description').text(scheds[id].description)
                        _details.find('#color').val(scheds[id].color)
                        _details.find('#start').text(scheds[id].sdate)
                        _details.find('#end').text(scheds[id].edate)
                        _details.find('#edit,#edit').attr('data-id', id)
                        _details.find('#edit,#delete').attr('data-id', id)
                        _details.modal('show')
                    } else {
                        alert("Event is undefined");
                    }
                },
                eventDidMount: function (info) {
                    // Do Something after events mounted
                },
                editable: true
            });

            calendar.render();

            // Form reset listener
            $('#schedule-form').on('reset', function () {
                $(this).find('input:hidden').val('')
                $(this).find('input:visible').first().focus()
            })

            // Edit Button
            $('#edit').click(function () {
                $(divRepetir).show();
                var id = $(this).attr('data-id')
                if (!!scheds[id]) {
                    var _form = $('#schedule-form')
                    console.log(String(scheds[id].start_datetime), String(scheds[id].start_datetime).replace(" ", "\\t"))

                    _form.find('[name="id"]').val(id)
                    _form.find('[name="title"]').val(scheds[id].title)
                    _form.find('[name="description"]').val(scheds[id].description)
                    _form.find('[name="color"]').val(scheds[id].color)
                    _form.find('[name="start_datetime"]').val(String(scheds[id].start_datetime).replace(" ", "T"))
                    _form.find('[name="end_datetime"]').val(String(scheds[id].end_datetime).replace(" ", "T"))
                    $('#event-details-modal').modal('hide')
                    _form.find('[name="title"]').focus()

                    let otherText = document.querySelector("#other-text"),
                        select = document.querySelector("#opciones_repetir");

                    select.addEventListener("change", () => {
                        if (select.value == "Otro...") {
                            otherText.style.zIndex = 0 + "";
                            select.style.zIndex = -1 + "";
                        } else {
                            otherText.style.zIndex = -1 + "";
                            select.style.zIndex = 0 + "";
                        }
                    });

                    /* 
                    
                    let otherText = document.querySelector("#other-text"),
                        select = document.querySelector("select");

                        select.addEventListener("change", () => {
                        if (select.value == "Otro...") {
                            otherText.style.zIndex = 0 + "";
                        } else {
                            otherText.style.zIndex = -1 + "";
                        }
                        });
                    */
                } else {
                    alert("Event is undefined");
                }
            })

            // Delete Button / Deleting an Event
            $('#delete').click(function () {
                var id = $(this).attr('data-id')
                if (!!scheds[id]) {
                    bootbox.confirm('Desea realmente eliminar este evento?', function (result) {
                        if (result === true) {
                            $('#event-details-modal').modal('hide')

                            $.ajax({
                                url: "ajax/events.php?op=deleteEvents",
                                type: "POST",
                                data: { id: id },
                                success: function (datos) {
                                    bootbox.alert({
                                        title: "Mensaje",
                                        message: datos,
                                        callback: function () {
                                            // var _details = $('#event-details-modal')
                                            // _details.modal('hide')
                                            getEvents();
                                        }
                                    });
                                }

                            });
                        }
                    });
                } else {
                    alert("Evento no definido");
                }
            })
        }

    });
}

function saveEvents(e) {
    e.preventDefault();
    var formData = new FormData($("#schedule-form")[0]);
    $.ajax({
        url: "ajax/events.php?op=saveEvents",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            bootbox.alert({
                title: "Mensaje",
                message: datos,
                callback: function () {
                    var _details = $('#event-details-modal')
                    _details.modal('hide');
                    $('#schedule-form').trigger("reset");
                    getEvents();
                }
            });
        }

    });
}

init();