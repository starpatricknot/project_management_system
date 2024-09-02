<style>
    .fc-header-toolbar {
        background-color: #343a40;
        /* Set background color */
    }

    .fc-header-toolbar button {
        background-color: #95a5a6;
        /* Change background color */
        color: #ffffff;
        /* Change text color */
        border-color: #95a5a6;
        /* Change border color */
    }

    /* Hover effect for buttons */
    .fc-header-toolbar button:hover {
        background-color: #bdc3c7;
        /* Change background color on hover */
        border-color: #bdc3c7;
        /* Change border color on hover */
    }

    .fc-toolbar-title {
        font-size: 12px;
        color: #FFFFFF;
        /* Change the color to red */
    }
</style>

<div class="container bg-white p-1 rounded shadow-sm" id="page-container">
    <div id="calendar"></div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded">
            <div class="modal-header rounded">
                <h5 class="modal-title">Schedule Details</h5>
                <!-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body rounded">
                <div class="container-fluid">
                    <dl>
                        <dt class="text-muted">Schedule</dt>
                        <dd id="schedule_name" class="fw-bold fs-5"></dd>
                        <dt class="text-muted">Employee Name</dt>
                        <dd id="employee_name" class="fw-bold"></dd>
                        <dt class="text-muted">Start</dt>
                        <dd id="start" class=""></dd>
                        <dt class="text-muted">End</dt>
                        <dd id="end" class=""></dd>
                    </dl>
                </div>
            </div>
            <div class="modal-footer rounded">
                <div class="text-end">
                    <!-- <button type="button" class="btn btn-primary btn-sm rounded" id="edit" data-id="">Edit</button> -->
                    <!-- <button type="button" class="btn btn-danger btn-sm rounded" id="delete" data-id="">Delete</button> -->
                    <!-- <button type="button" class="btn btn-secondary btn-sm rounded" data-bs-dismiss="modal">Close</button> -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<?php
$schedules = $conn->query("SELECT * FROM `employee_schedule`");
$sched_res = [];
foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
    $row['sdate'] = date("F d, Y", strtotime($row['start_date']));
    $row['edate'] = date("F d, Y", strtotime($row['end_date']));
    $sched_res[$row['id']] = $row;
}
?>
<?php
// if (isset($conn)) $conn->close();
?>

<script src="./js/jquery-3.6.0.min.js"></script>
<!-- <script src="./js/bootstrap.min.js"></script> -->
<script src="./fullcalendar/lib/main.min.js"></script>
<script src="./js/script.js"></script>

<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>