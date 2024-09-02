<!DOCTYPE html>
<html lang="en">

<?php
if (!isset($_SESSION['login_id']))
    header('location:login.php');
include 'db_connect.php';
ob_start();
if (!isset($_SESSION['system'])) {

    $system = $conn->query("SELECT * FROM system_settings")->fetch_array();
    foreach ($system as $k => $v) {
        $_SESSION['system'][$k] = $v;
    }
}
ob_end_flush();
?>

<?php include 'header.php' ?>

<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
<link rel="stylesheet" href="./css/bootstrap.min.css">
<link rel="stylesheet" href="./fullcalendar/lib/main.min.css">
<style>
    :root {
        --bs-success-rgb: 71, 222, 152 !important;
    }

    html,
    body {
        height: 100%;
        width: 100%;
    }

    .btn-info.text-light:hover,
    .btn-info.text-light:focus {
        background: #000;
    }

    table,
    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
        border-color: #ededed !important;
        border-style: solid;
        border-width: 1px !important;
    }

    .fc-header-toolbar {
        background-color: #757d85;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.10);
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
        color: #FFFFFF;
        /* Change the color to red */
    }
</style>

<body class="bg-light">
    <div class="container" id="page-container">
        <div class="row bg-white px-3 py-4 rounded">
            <div class="col-md-9">
                <div class="bg-light shadow-sm" id="calendar"></div>
            </div>
            <div class="col-md-3">
                <div class="card rounded bg-light shadow-sm">
                    <div class="card-header bg-gradient bg-secondary text-light">
                        <h5 class="card-title mt-2 fw-bold">Add Schedule</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="save_schedule.php" method="post" id="schedule-form">
                                <input type="hidden" name="id" value="">
                                <div class="form-group mb-2">
                                    <label for="schedule_name" class="control-label">Schedule</label>
                                    <!-- <input type="text" class="form-control form-control-sm rounded" name="title" id="title" required> -->
                                    <select class="form-select" aria-label="Select Schedule" id="schedule_name" name="schedule_name">
                                        <option value="Day Shift">Day Shift</option>
                                        <option value="Mid Shift">Mid Shift</option>
                                        <option value="Night Shift">Night Shift</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="description" class="control-label">Employee</label>
                                    <select class="form-control form-control-sm" name="employee_id">
                                        <?php
                                        $employees = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 3 order by concat(firstname,' ',lastname) asc ");
                                        while ($row = $employees->fetch_assoc()) :
                                        ?>
                                            <option value="<?php echo $row['id'] ?>" <?php echo isset($employee_id) && in_array($row['id'], explode(',', $employee_id)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="start_date" class="control-label">Start</label>
                                    <input type="date" class="form-control form-control-sm rounded" name="start_date" id="start_date" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="end_date" class="control-label">End</label>
                                    <input type="date" class="form-control form-control-sm rounded" name="end_date" id="end_date" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm rounded" type="submit" form="schedule-form"><i class="fa fa-save"></i> Save</button>
                            <button class="btn btn-default border btn-sm rounded" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Cancel</button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-gradient bg-secondary text-light">
                        <h5 class="card-title mt-2 fw-bold">Schedule Guidelines</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">

                            <div class="row gx-1">
                                <label class="form-label">Day Shift:</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="8AM" disabled>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="5PM" disabled>
                                </div>
                            </div>

                            <div class="row gx-1">
                                <label class="form-label mt-3">Mid Shift:</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="3PM" disabled>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="12AM" disabled>
                                </div>
                            </div>

                            <div class="row gx-1 mb-3">
                                <label class="form-label mt-3">Night Shift:</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="9PM" disabled>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="6AM" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header rounded">
                    <h5 class="modal-title">Schedule Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded">
                    <div class="container-fluid">
                        <dl>
                            <dt class="text-muted">Schedule</dt>
                            <dd id="schedule_name" class="fw-bold fs-5"></dd>
                            <dt class="text-muted">Employee Name</dt>
                            <dd id="employee_name" class="fw-bold"></dd>
                            <dt class="text-muted">Shift Start</dt>
                            <dd id="start" class=""></dd>
                            <dt class="text-muted">Shift End</dt>
                            <dd id="end" class=""></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded">
                    <div class="text-end">
                        <button type="button" class="btn btn-primary btn-sm rounded" id="edit" data-id="">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm rounded" id="delete" data-id="">Delete</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded" data-bs-dismiss="modal">Close</button>
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
    if (isset($conn)) $conn->close();
    ?>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./fullcalendar/lib/main.min.js"></script>
    <script src="./js/script.js"></script>

    <script>
        var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
    </script>
</body>

</html>