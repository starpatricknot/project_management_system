<?php include 'db_connect.php' ?>
<div class="col-lg-12">
    <div class="card card-success">
        <div class="card-header">
            <div class="row pt-1">
                <div class="col">
                    <h4> <i class="nav-icon fas fa-users"></i> Employee Progress Report</h4>
                </div>
                <?php if ($_SESSION['login_type'] != 3) : ?>
                    <div class="col-auto">
                        <div class="card-tools">
                            <button class="btn btn-block btn-sm btn-default border-success text-dark" id="print"><i class="fa fa-print"></i> Print</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="printable">
                <table class="table m-0 table-hover table-condensed border" id="list">

                    <div class="filters-row">
                        <div class="row">
                            <!-- <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="project_filter">Filter by Project:</label>
                                <select id="project_filter" class="form-control form-control-sm">
                                    <option value="">All Projects</option>
                                    <?php
                                    // $result = $conn->query("SELECT * FROM project_list ORDER BY name ASC");
                                    // while ($row = $result->fetch_assoc()) {
                                    //     echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    // }
                                    ?>
                                </select>
                            </div>
                        </div> -->
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="employee_filter">Filter by Employee:</label>
                                    <select id="employee_filter" class="form-control form-control-sm">
                                        <option value="">All Employees</option>
                                        <?php
                                        $result = $conn->query("SELECT DISTINCT u.id, CONCAT(u.firstname, ' ', u.lastname) AS name FROM users u INNER JOIN user_productivity up ON u.id = up.user_id ORDER BY name ASC");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="month_filter">Filter by Month:</label>
                                    <input id="month_filter" type="month" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="date_filter">Filter by Date:</label>
                                    <input id="date_filter" type="date" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="status_filter">Filter by Task Status:</label>
                                    <select id="status_filter" class="form-control form-control-sm">
                                        <option value="">All Statuses</option>
                                        <option value="1">Pending</option>
                                        <option value="2">On-Progress</option>
                                        <option value="3">Done</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-1">
                                <div class="py-1">
                                    <button id="resetFilters" class="btn btn-md btn-secondary">Reset Filters</button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Table Header -->
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Employee</th>
                            <th>Project</th>
                            <th>Task</th>
                            <!-- <th>Comment</th> -->
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Time Rendered</th>
                            <th>Task Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        // Your SQL query
                        $query = "SELECT 
                                    u.firstname AS employee_firstname, 
                                    u.lastname AS employee_lastname, 
                                    pl.name AS project_name,
                                    tl.task AS task_name, 
                                    tl.status AS task_status, 
                                    up.comment, 
                                    up.subject, 
                                    up.date, 
                                    up.time_rendered
                                FROM 
                                    user_productivity AS up 
                                INNER JOIN 
                                    users AS u ON up.user_id = u.id 
                                LEFT JOIN 
                                    task_list AS tl ON up.task_id = tl.id 
                                LEFT JOIN 
                                    project_list AS pl ON tl.project_id = pl.id
                                WHERE 1 ";

                        // Filter by project
                        if (isset($_GET['project']) && $_GET['project'] != '') {
                            $query .= "AND pl.id = '" . $_GET['project'] . "' ";
                        }

                        // Filter by employee
                        if (isset($_GET['employee']) && $_GET['employee'] != '') {
                            $query .= "AND u.id = '" . $_GET['employee'] . "' ";
                        }

                        // Filter by month
                        if (isset($_GET['month']) && $_GET['month'] != '') {
                            // Extract the month and year from the selected value
                            $selected_month = date('m', strtotime($_GET['month']));
                            $selected_year = date('Y', strtotime($_GET['month']));
                            $query .= "AND MONTH(up.date) = '$selected_month' AND YEAR(up.date) = '$selected_year' ";
                        }

                        // Filter by date
                        if (isset($_GET['date']) && $_GET['date'] != '') {
                            $query .= "AND up.date = '" . $_GET['date'] . "' ";
                        }

                        // Filter by status
                        if (isset($_GET['status']) && $_GET['status'] != '') {
                            $query .= "AND tl.status = '" . $_GET['status'] . "' ";
                        }

                        // Execute the query
                        $result = $conn->query($query);

                        // Fetch and display the results
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td><?php echo $row['employee_firstname'] . ' ' . $row['employee_lastname'] ?></td>
                                    <td><?php echo $row['project_name'] ?></td>
                                    <td><?php echo $row['task_name'] ?></td>
                                    <!-- <td><?php #echo $row['comment'] 
                                                ?></td> -->
                                    <td><?php echo $row['subject'] ?></td>
                                    <td><?php echo $row['date'] ?></td>
                                    <td><?php echo $row['time_rendered'] ?> Hours</td>
                                    <td>
                                        <?php
                                        $task_status_label = '';
                                        $task_status_badge_class = '';
                                        if ($row['task_status'] == 1) {
                                            $task_status_label = 'Pending';
                                            $task_status_badge_class = 'badge-secondary';
                                        } elseif ($row['task_status'] == 2) {
                                            $task_status_label = 'On-Progress';
                                            $task_status_badge_class = 'badge-primary';
                                        } elseif ($row['task_status'] == 3) {
                                            $task_status_label = 'Done';
                                            $task_status_badge_class = 'badge-success';
                                        }
                                        echo "<span class='badge p-2 $task_status_badge_class'>$task_status_label</span>";
                                        ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='8'>No data available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    table p {
        margin: unset !important;
    }

    table td {
        vertical-align: middle !important
    }
</style>
<script>
    $('#print').click(function() {
        start_load();
        var _h = $('head').clone();
        var _p = $('#printable').clone();
        // Remove filter row
        _p.find('.filters-row').remove();
        var _d = "<p class='text-center'><b>Employee Progress Report as of (<?php echo date("F d, Y") ?>)</b></p>";
        _p.prepend(_d);
        _p.prepend(_h);
        var nw = window.open("", "", "width=900,height=600");
        nw.document.write(_p.html());
        nw.document.close();
        nw.print();
        setTimeout(function() {
            nw.close();
            end_load();
        }, 750);
    });

    $('#resetFilters').click(function() {
        // Define the URL of the page where you want to redirect
        var resetPageUrl = 'index.php?page=reports_employee'; // Change this to the desired reset page URL

        // Redirect to the reset page
        window.location.href = resetPageUrl;
    });

    $(document).ready(function() {
        $('#list').dataTable()

        // Filter projects by status
        $('#project_filter').change(function() {
            var status = $(this).val();
            filterProjects(status);
        })

        // Filter by employee
        $('#employee_filter').change(function() {
            var employee = $(this).val();
            applyFilters('employee', employee);
        })

        // Filter by month
        $('#month_filter').change(function() {
            var month = $(this).val();
            applyFilters('month', month);
        })

        // Filter by date
        $('#date_filter').change(function() {
            var date = $(this).val();
            applyFilters('date', date);
        })

        // Filter by status
        $('#status_filter').change(function() {
            var status = $(this).val();
            applyFilters('status', status);
        })
    });

    function applyFilters(filterType, filterValue) {
        var urlParams = new URLSearchParams(window.location.search);
        urlParams.set(filterType, filterValue);
        window.location.search = urlParams.toString();
    }

    function filterProjects(projectId) {
        $.ajax({
            url: 'ajax.php?action=filter_user_progress',
            method: 'POST',
            data: {
                id: projectId
            },
            success: function(resp) {
                $('#list tbody').html(resp);
            }
        });
    }
</script>