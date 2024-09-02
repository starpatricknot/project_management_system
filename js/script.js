var calendar;
var Calendar = FullCalendar.Calendar;
var events = [];
$(function () {
    if (!!scheds) {
        Object.keys(scheds).map(k => {
            var row = scheds[k];
            var startDate = row.start_date;
            var endDate = new Date(row.end_date);
            
            endDate.setDate(endDate.getDate() + 1);
            startDate = startDate.split('T')[0];
            endDate = endDate.toISOString().split('T')[0];

            events.push({
                id: row.id,
                title: row.employee_fname + ' ' + row.employee_lname + ' - ' + row.schedule_name,
                start: startDate,
                end: endDate
            });
        });
    }
    var date = new Date()
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear()

    calendar = new Calendar(document.getElementById('calendar'), {
        headerToolbar: {
            left: 'prev,next today',
            right: 'dayGridMonth,dayGridWeek,list',
            center: 'title',
        },
        selectable: true,
        themeSystem: 'bootstrap',
        // Random default events
        events: events,
        eventClick: function (info) {
            var _details = $('#event-details-modal');
            var id = info.event.id;
            if (!!scheds[id]) {
                _details.find('#schedule_name').text(scheds[id].schedule_name);
                // Fetch employee name using AJAX
                $.ajax({
                    url: 'ajax.php?action=get_employee_name', // Replace with the actual PHP file to fetch employee name
                    method: 'POST',
                    data: { employee_id: scheds[id].employee_id },
                    success: function (response) {
                        // Update employee name in the modal
                        _details.find('#employee_name').text(response);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
                _details.find('#start').text(scheds[id].sdate);
                _details.find('#end').text(scheds[id].edate);
                _details.find('#edit,#delete').attr('data-id', id);
                _details.modal('show');
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
        var id = $(this).attr('data-id')
        if (!!scheds[id]) {
            var _form = $('#schedule-form')
            console.log(String(scheds[id].start_date), String(scheds[id].start_date).replace(" ", "\\t"))
            _form.find('[name="id"]').val(id)
            _form.find('[name="schedule_name"]').val(scheds[id].schedule_name)
            _form.find('[name="employee_id"]').val(scheds[id].employee_id)
            _form.find('[name="start_date"]').val(String(scheds[id].start_date).replace(" ", "T"))
            _form.find('[name="end_date"]').val(String(scheds[id].end_date).replace(" ", "T"))
            $('#event-details-modal').modal('hide')
            _form.find('[name="schedule_name"]').focus()
        } else {
            alert("Event is undefined");
        }
    })

    // Delete Button / Deleting an Event
    $('#delete').click(function () {
        var id = $(this).attr('data-id')
        if (!!scheds[id]) {
            var _conf = confirm("Are you sure to delete this scheduled event?");
            if (_conf === true) {
                location.href = "./delete_schedule.php?id=" + id;
            }
        } else {
            alert("Event is undefined");
        }
    })
})