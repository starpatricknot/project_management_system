<?php include('db_connect.php') ?>
<?php
$twhere = "";
if ($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>
<!-- Info boxes -->
<div class="col-12 pb-1">
  <div class="card">
    <div class="card-body">
      Welcome <?php echo $_SESSION['login_name'] ?>!
    </div>
  </div>
</div>
<hr>
<?php

$where = "";
if ($_SESSION['login_type'] == 2) {
  $where = " where manager_id = '{$_SESSION['login_id']}' ";
} elseif ($_SESSION['login_type'] == 3) {
  $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
$where2 = "";
if ($_SESSION['login_type'] == 2) {
  $where2 = " where p.manager_id = '{$_SESSION['login_id']}' ";
} elseif ($_SESSION['login_type'] == 3) {
  $where2 = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
?>

<div class="col-md-12">
  <div class="row">
    <div class="col-12 col-sm-6 col-md-2">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3><?php echo $conn->query("SELECT * FROM project_list $where")->num_rows; ?></h3>
          <div class="mb-2">
            <a href="./index.php?page=project_list" class="text-dark">Total Projects</a>
          </div>
        </div>
        <div class="icon">
          <i class="fas fa-solid fa-building"></i>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3>
            <?php
            $task_count_query = "SELECT COUNT(t.id) AS task_count FROM task_list t INNER JOIN project_list p ON p.id = t.project_id WHERE t.status = 3 $where2";
            $task_count_result = $conn->query($task_count_query);
            if ($task_count_result) {
              $task_count_row = $task_count_result->fetch_assoc();
              $task_count = $task_count_row['task_count'];
            } else {
              $task_count = 0;
            }
            echo $task_count;
            ?>
          </h3>
          <div class="mb-2">
            <a href="./index.php?page=task_list" class="text-dark">Task Done</a>
          </div>
        </div>
        <div class="icon">
          <i class="fa fa-tasks"></i>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3><?php echo $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where2")->num_rows; ?></h3>
          <div class="mb-2">
            <a href="./index.php?page=task_list" class="text-dark">Total Tasks</a>
          </div>
        </div>
        <div class="icon">
          <i class="fa fa-list"></i>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3>
            <?php
            $pending_task_count_query = "SELECT COUNT(t.id) AS pending_task_count FROM task_list t INNER JOIN project_list p ON p.id = t.project_id WHERE t.status != 3 $where2";
            $pending_task_count_result = $conn->query($pending_task_count_query);
            $pending_task_count = ($pending_task_count_result && $pending_task_count_result->num_rows > 0) ? $pending_task_count_result->fetch_assoc()['pending_task_count'] : 0;
            echo $pending_task_count;
            ?>
          </h3>
          <div class="mb-2">
            <a href="./index.php?page=task_list" class="text-dark">Pending Task</a>
          </div>
        </div>
        <div class="icon">
          <i class="fa fa-hourglass-half"></i>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3>
            <?php
            $total_employees_query = "SELECT COUNT(*) AS total_employees FROM users WHERE type IN (2, 3)";
            $total_employees_result = $conn->query($total_employees_query);
            $total_employees = ($total_employees_result && $total_employees_result->num_rows > 0) ? $total_employees_result->fetch_assoc()['total_employees'] : 0;
            echo $total_employees;
            ?>
          </h3>
          <div class="mb-2">
            <a href="./index.php?page=user_list" class="text-dark">Total Employee</a>
          </div>
        </div>
        <div class="icon">
          <i class="fa fa-users"></i>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-2">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3>
            <?php
            $completed_project_count = "SELECT COUNT(p.id) AS completed_projects FROM project_list p  WHERE status = 5";
            $completed_project_count_result = $conn->query($completed_project_count);
            $completed_project_count = ($completed_project_count_result && $completed_project_count_result->num_rows > 0) ? $completed_project_count_result->fetch_assoc()['completed_projects'] : 0;
            echo $completed_project_count;
            ?>
          </h3>
          <div class="mb-2">
            <a href="./index.php?page=project_list" class="text-dark">Completed Projects</a>
          </div>
        </div>
        <div class="icon">
          <i class="fa fa-check"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-5">
    <?php include 'home_schedule.php'; ?>
  </div>
  <div class="col-md-7">
    <div class="card card-dark">
      <div class="card-header">
        <div class="row pt-1">
          <div class="col">
            <h4><i class="fas fa-solid fa-building"></i> Project Progress</h4>
          </div>
          <div class="col-auto">
            <a class="btn btn-sm btn-default border-secondary text-dark" href="./index.php?page=project_list">View More</a>
          </div>
        </div>
      </div>
      <div class="card-body p-3 ">
        <div class="table-responsive">
          <table class="table m-0 table-hover table-condensed border">
            <colgroup>
              <col width="5%">
              <col width="30%">
              <col width="35%">
              <col width="15%">
              <col width="15%">
            </colgroup>
            <thead>
              <th>#</th>
              <th>Project</th>
              <th>Progress</th>
              <th>Status</th>
              <th></th>
            </thead>
            <tbody>
              <?php

              $i = 1;
              $stat = array("Pending", "Started", "On-Progress", "On-Hold", "Over Due", "Done");
              $where = "";
              if ($_SESSION['login_type'] == 2) {
                $where = " where manager_id = '{$_SESSION['login_id']}' ";
              } elseif ($_SESSION['login_type'] == 3) {
                $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
              }
              $qry = $conn->query("SELECT * FROM project_list $where order by name asc LIMIT 6");
              while ($row = $qry->fetch_assoc()) :
                $prog = 0;
                $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 3")->num_rows;
                $prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
                $prog = $prog > 0 ?  number_format($prog, 2) : $prog;
                $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;

                // Check if today's date is after the due date
                if ((new DateTime())->format('Y-m-d') > $row['end_date']) {
                  $row['status'] = 4; // Set status to "Over Due"
                  $conn->query("UPDATE project_list SET status = 4 WHERE id = {$row['id']}");
                }
                // Check if all tasks are done
                if ($tprog > 0 && $tprog == $cprog) {
                  $row['status'] = 5; // Set status to "Done"
                  $conn->query("UPDATE project_list SET status = 5 WHERE id = {$row['id']}");
                }
              ?>


                <tr>
                  <td>
                    <?php echo $i++ ?>
                  </td>
                  <td>
                    <a>
                      <?php echo ucwords($row['name']) ?>
                    </a>
                    <br>
                    <small>
                      Due: <?php echo date("Y-m-d", strtotime($row['end_date'])) ?>
                    </small>
                  </td>
                  <td class="project_progress">
                    <div class="progress progress-sm">
                      <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                      </div>
                    </div>
                    <small>
                      <?php echo $prog ?>% Complete
                    </small>
                  </td>
                  <td class="project-state">
                    <?php
                    if ($stat[$row['status']] == 'Pending') {
                      echo "<span class='badge p-2 badge-secondary'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'Started') {
                      echo "<span class='badge p-2 badge-primary'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'On-Progress') {
                      echo "<span class='badge p-2 badge-info'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'On-Hold') {
                      echo "<span class='badge p-2 badge-warning'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'Over Due') {
                      echo "<span class='badge p-2 badge-danger'>{$stat[$row['status']]}</span>";
                    } elseif ($stat[$row['status']] == 'Done') {
                      echo "<span class='badge p-2 badge-success'>{$stat[$row['status']]}</span>";
                    }
                    ?>
                  </td>
                  <td>
                    <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>">
                      <i class="fas fa-folder">
                      </i>
                      View
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-12">
    <hr>
    <div class="card card-secondary mt-4">
      <div class="card-header">
        <div class="row">
          <div class="col">
            <h4><i class="fa fa-tasks"></i> Task List</h4>
          </div>
          <div class="col-md-1"><a class="btn btn-block btn-sm btn-default border-secondary text-dark" href="./index.php?page=task_list">View More</a></div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table m-0 table-hover table-condensed border" id="list">
            <colgroup>
              <col width="5%">
              <col width="15%">
              <col width="20%">
              <col width="15%">
              <col width="15%">
              <col width="10%">
              <col width="10%">
            </colgroup>
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Project</th>
                <th>Task</th>
                <th>Project Start</th>
                <th>Project Due Date</th>
                <th>Project Status</th>
                <th>Task Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $where = "";
              if ($_SESSION['login_type'] == 2) {
                $where = " where p.manager_id = '{$_SESSION['login_id']}' ";
              } elseif ($_SESSION['login_type'] == 3) {
                $where = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
              }

              $stat = array("Pending", "Started", "On-Progress", "On-Hold", "Over Due", "Done");
              $qry = $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where order by p.name asc LIMIT 5");
              while ($row = $qry->fetch_assoc()) :
                $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
                unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                $desc = strtr(html_entity_decode($row['description']), $trans);
                $desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
                $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']}")->num_rows;
                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']} and status = 3")->num_rows;
                $prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
                $prog = $prog > 0 ?  number_format($prog, 2) : $prog;
                $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['pid']}")->num_rows;
                if ($row['pstatus'] == 0 && (new DateTime())->format('Y-m-d') >= $row['start_date']) {
                  if ($prod > 0 || $cprog > 0) {
                    $row['pstatus'] = 2; // Set status to "Started"
                  } else {
                    $row['pstatus'] = 1; // Set status to "Pending"
                  }
                } elseif ($row['pstatus'] == 0 && (new DateTime())->format('Y-m-d') > $row['end_date']) {
                  $row['pstatus'] = 4; // Set status to "Over Due"
                }


              ?>
                <tr>
                  <td class="text-center"><?php echo $i++ ?></td>
                  <td>
                    <a href="./index.php?page=view_project&id=<?php echo $row['pid'] ?>"><b><?php echo ucwords($row['pname']) ?></b></a>
                  </td>
                  <td>
                    <p><b><?php echo ucwords($row['task']) ?></b></p>
                    <p class="truncate"><?php echo strip_tags($desc) ?></p>
                  </td>
                  <td><b><?php echo date("M d, Y", strtotime($row['start_date'])) ?></b></td>
                  <td><b><?php echo date("M d, Y", strtotime($row['end_date'])) ?></b></td>
                  <td class="">
                    <?php
                    if ($stat[$row['pstatus']] == 'Pending') {
                      echo "<span class='badge p-2 badge-secondary'>{$stat[$row['pstatus']]}</span>";
                    } elseif ($stat[$row['pstatus']] == 'Started') {
                      echo "<span class='badge p-2 badge-primary'>{$stat[$row['pstatus']]}</span>";
                    } elseif ($stat[$row['pstatus']] == 'On-Progress') {
                      echo "<span class='badge p-2 badge-info'>{$stat[$row['pstatus']]}</span>";
                    } elseif ($stat[$row['pstatus']] == 'On-Hold') {
                      echo "<span class='badge p-2 badge-warning'>{$stat[$row['pstatus']]}</span>";
                    } elseif ($stat[$row['pstatus']] == 'Over Due') {
                      echo "<span class='badge p-2 badge-danger'>{$stat[$row['pstatus']]}</span>";
                    } elseif ($stat[$row['pstatus']] == 'Done') {
                      echo "<span class='badge p-2 badge-success'>{$stat[$row['pstatus']]}</span>";
                    }
                    ?>
                  </td>
                  <td>
                    <?php
                    if ($row['status'] == 1) {
                      echo "<span class='badge p-2 badge-secondary'>Pending</span>";
                    } elseif ($row['status'] == 2) {
                      echo "<span class='badge p-2 badge-primary'>On-Progress</span>";
                    } elseif ($row['status'] == 3) {
                      echo "<span class='badge p-2 badge-success'>Done</span>";
                    }
                    ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- <script>
  $(document).ready(function() {
    $('#list').dataTable();
  });
</script> -->