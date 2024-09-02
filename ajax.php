<?php
include 'db_connect.php';

ob_start();
date_default_timezone_set("Asia/Manila");

$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if ($action == 'login') {
	$login = $crud->login();
	if ($login) echo $login;
}
if ($action == 'login2') {
	$login = $crud->login2();
	if ($login) echo $login;
}
if ($action == 'logout') {
	$logout = $crud->logout();
	if ($logout) echo $logout;
}
if ($action == 'logout2') {
	$logout = $crud->logout2();
	if ($logout) echo $logout;
}

if ($action == 'signup') {
	$save = $crud->signup();
	if ($save) echo $save;
}
if ($action == 'save_user') {
	$save = $crud->save_user();
	if ($save) echo $save;
}
if ($action == 'update_user') {
	$save = $crud->update_user();
	if ($save) echo $save;
}
if ($action == 'delete_user') {
	$save = $crud->delete_user();
	if ($save) echo $save;
}
if ($action == 'save_project') {
	$save = $crud->save_project();
	if ($save) echo $save;
}
if ($action == 'delete_project') {
	$save = $crud->delete_project();
	if ($save) echo $save;
}
if ($action == 'save_task') {
	$save = $crud->save_task();
	if ($save) echo $save;
}
if ($action == 'update_task_status') {
	$save = $crud->update_task_status();
	if ($save) echo $save;
}
if ($action == 'delete_task') {
	$save = $crud->delete_task();
	if ($save) echo $save;
}
if ($action == 'save_progress') {
	$save = $crud->save_progress();
	if ($save) echo $save;
}
if ($action == 'delete_progress') {
	$save = $crud->delete_progress();
	if ($save) echo $save;
}
if ($action == 'get_report') {
	$get = $crud->get_report();
	if ($get) echo $get;
}

if ($action == 'get_employee_name') {
	$get = $crud->get_employee_name();
	if ($get) echo $get;
}

if ($action == 'filter_projects') {
	$status = $_POST['status'];
	$where = "";
	if ($_SESSION['login_type'] == 2) {
		$where = " WHERE manager_id = '{$_SESSION['login_id']}' ";
	} elseif ($_SESSION['login_type'] == 3) {
		$where = " WHERE concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
	}

	if ($status !== "") {
		if ($where === "") {
			$where = " WHERE status = '$status' ";
		} else {
			$where .= " AND status = '$status' ";
		}
	}

	$qry = $conn->query("SELECT * FROM project_list $where ORDER BY name ASC");
	$i = 1; // Initialize $i here 
	while ($row = $qry->fetch_assoc()) {
		$status_text = '';
		switch ($row['status']) {
			case 0:
				if (strtotime(date('Y-m-d')) >= strtotime($row['start_date'])) {
					$status_text = 'Started';
				} elseif (strtotime(date('Y-m-d')) > strtotime($row['end_date'])) {
					$status_text = 'Over Due';
				} else {
					$status_text = 'Pending';
				}
				break;
			case 1:
				$status_text = 'Started';
				break;
			case 2:
				$status_text = 'On-Progress';
				break;
			case 3:
				$status_text = 'On-Hold';
				break;
			case 4:
				$status_text = 'Over Due';
				break;
			case 5:
				$status_text = 'Done';
				break;
		}

		echo '<tr>';
		echo '<th class="text-center">' . $i++ . '</th>';
		echo '<td>';
		echo '<a href="./index.php?page=view_project&id=' . $row["id"] . '"><b>' . ucwords($row['name']) . '</b></a>';
		echo '<p class="truncate">' . strip_tags(html_entity_decode($row['description'])) . '</p>';
		echo '</td>';
		echo '<td><b>' . date("M d, Y", strtotime($row['start_date'])) . '</b></td>';
		echo '<td><b>' . date("M d, Y", strtotime($row['end_date'])) . '</b></td>';
		echo '<td class="text-center">';
		echo '<span class="badge p-2 badge-' . ($row['status'] == 5 ? 'success' : ($row['status'] == 4 ? 'danger' : ($row['status'] == 3 ? 'warning' : ($row['status'] == 2 ? 'info' : ($row['status'] == 1 ? 'primary' : 'secondary'))))) . '">' . $status_text . '</span>';
		echo '</td>';
		echo '<td class="text-center">';
		echo '<div class="dropdown">';
		echo '<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Action</button>';
		echo '<div class="dropdown-menu" style="">';
		echo '<a class="dropdown-item view_project" href="./index.php?page=view_project&id=' . $row['id'] . '" data-id="' . $row['id'] . '">View</a>';
		echo '<div class="dropdown-divider"></div>';
		if ($_SESSION['login_type'] != 3) {
			echo '<a class="dropdown-item" href="./index.php?page=edit_project&id=' . $row['id'] . '">Edit</a>';
			echo '<div class="dropdown-divider"></div>';
			echo '<a class="dropdown-item delete_project" href="javascript:void(0)" data-id="' . $row['id'] . '">Delete</a>';
		}
		echo '</div>';
		echo '</div>';
		echo '</td>';
		echo '</tr>';
	}
}

if ($action == 'filter_tasks') {
	$project_id = isset($_POST['id']) ? $_POST['id'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';

	$where = "";
	if ($_SESSION['login_type'] == 2) {
		$where = " WHERE manager_id = '{$_SESSION['login_id']}' ";
	} elseif ($_SESSION['login_type'] == 3) {
		$where = " WHERE concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
	}

	// Append status condition if provided
	$statusCondition = $status !== "" ? " and t.status = $status" : "";

	$qry = $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid FROM task_list t INNER JOIN project_list p ON p.id = t.project_id $where $statusCondition ORDER BY p.name ASC");

	if ($qry) {
		$filtered_tasks = [];
		while ($row = $qry->fetch_assoc()) {
			if (empty($project_id) || $row['pid'] == $project_id) {
				$filtered_tasks[] = $row;
			}
		}

		foreach ($filtered_tasks as $row) {
			$status_text = '';
			switch ($row['pstatus']) {
				case 0:
					if (strtotime(date('Y-m-d')) >= strtotime($row['start_date'])) {
						$status_text = 'Started';
					} elseif (strtotime(date('Y-m-d')) > strtotime($row['end_date'])) {
						$status_text = 'Over Due';
					} else {
						$status_text = 'Pending';
					}
					break;
				case 1:
					$status_text = 'Started';
					break;
				case 2:
					$status_text = 'On-Progress';
					break;
				case 3:
					$status_text = 'On-Hold';
					break;
				case 4:
					$status_text = 'Over Due';
					break;
				case 5:
					$status_text = 'Done';
					break;
			}

			echo '<tr>';
			echo '<th class="text-center">' . $i++ . '</th>';
			echo '<td>';
			echo '<a href="./index.php?page=view_project&id=' . $row["pid"] . '"><b>' . ucwords($row['pname']) . '</b></a>';
			echo '<p class="truncate">' . strip_tags(html_entity_decode($row['description'])) . '</p>';
			echo '</td>';
			echo '<td><b>' . $row['task'] . '</b></td>';
			echo '<td><b>' . date("M d, Y", strtotime($row['start_date'])) . '</b></td>';
			echo '<td><b>' . date("M d, Y", strtotime($row['end_date'])) . '</b></td>';
			echo '<td class="text-center">';
			echo '<span class="badge p-2 badge-' . ($row['pstatus'] == 5 ? 'success' : ($row['pstatus'] == 4 ? 'danger' : ($row['pstatus'] == 3 ? 'warning' : ($row['pstatus'] == 2 ? 'info' : ($row['pstatus'] == 1 ? 'primary' : 'secondary'))))) . '">' . $status_text . '</span>';
			echo '</td>';
			echo '<td class="text-center">';
			if ($row['status'] == 1) {
				echo "<span class='badge p-2 badge-secondary'>Pending</span>";
			} elseif ($row['status'] == 2) {
				echo "<span class='badge p-2 badge-primary'>On-Progress</span>";
			} elseif ($row['status'] == 3) {
				echo "<span class='badge p-2 badge-success'>Done</span>";
			}
			echo '</td>';
			echo '<td class="text-center">';
			echo '<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Action</button>';
			echo '<div class="dropdown-menu" style="">';
			echo '<a class="dropdown-item new_productivity" data-pid="' . $row['pid'] . '" data-tid="' . $row['id'] . '" data-task="' . ucwords($row['task']) . '" href="javascript:void(0)">Add Productivity</a>';
			echo '</div>';
			echo '</td>';
			echo '</tr>';
		}
	} else {
		echo "Error executing query: " . $conn->error;
	}
}

if ($action == 'remove_notification') {
		$notification_id = $_POST['notification_id'];

		// Perform notification removal query
		$delete_notification_query = $conn->query("DELETE FROM notifications WHERE id = $notification_id");

		if ($delete_notification_query) {
			// Return success message
			echo "success";
			exit; // Terminate script execution after sending response
		} else {
			// Return error message
			echo "error";
			exit; // Terminate script execution after sending response
		}
}

if ($action == 'filter_user_progress') {
	$project_id = isset($_POST['id']) ? $_POST['id'] : '';

	$where = "";
	if ($_SESSION['login_type'] == 2) {
		$where = " WHERE manager_id = '{$_SESSION['login_id']}' ";
	} elseif ($_SESSION['login_type'] == 3) {
		$where = " WHERE concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
	}

	// Reset filter conditions if no project_id is provided
	if (empty($project_id)) {
		$where = ""; // Reset the WHERE clause
	}

	// Apply filters
	$filters = array();
	if (!empty($_GET['employee'])) {
		$filters[] = "u.id = '" . $_GET['employee'] . "'";
	}
	if (!empty($_GET['month'])) {
		$filters[] = "MONTH(up.date) = MONTH('" . $_GET['month'] . "')";
	}
	if (!empty($_GET['date'])) {
		$filters[] = "up.date = '" . $_GET['date'] . "'";
	}
	if (!empty($_GET['status'])) {
		$filters[] = "tl.status = '" . $_GET['status'] . "'";
	}

	if (!empty($filters)) {
		$where .= " AND " . implode(" AND ", $filters);
	}

	$qry = $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid FROM task_list t INNER JOIN project_list p ON p.id = t.project_id $where ORDER BY p.name ASC");
	echo "SQL Query: " . $qry;
	if ($qry) {
		$filtered_tasks = [];
		while ($row = $qry->fetch_assoc()) {
			if (empty($project_id) || $row['pid'] == $project_id) {
				$filtered_tasks[] = $row;
			}
		}

		foreach ($filtered_tasks as $row) {
			// Fetch user productivity data
			$user_productivity_query = $conn->query("SELECT 
                                                        up.comment, 
                                                        up.subject, 
                                                        up.date, 
                                                        up.time_rendered,
                                                        tl.status AS task_status,
                                                        -- u.firstname AS employee_firstname, 
                                                        -- u.lastname AS employee_lastname
                                                    FROM 
                                                        user_productivity AS up 
                                                    INNER JOIN 
                                                        users AS u ON up.user_id = u.id 
                                                    LEFT JOIN 
                                                        task_list AS tl ON up.task_id = tl.id 
                                                    LEFT JOIN 
                                                        project_list AS pl ON tl.project_id = pl.id
                                                    WHERE 
                                                        up.task_id = '{$row['id']}' $where");

			// Display user productivity data
			if ($user_productivity_query) {
				while ($user_productivity_row = $user_productivity_query->fetch_assoc()) {
					// Display user productivity data in the table row format
					echo '<tr>';
					echo '<td class="text-center">' . $i++ . '</td>';
					echo '<td>' . $user_productivity_row['employee_firstname'] . ' ' . $user_productivity_row['employee_lastname'] . '</td>';
					echo '<td>' . $row['pname'] . '</td>';
					echo '<td>' . $row['task'] . '</td>';
					echo '<td>' . $user_productivity_row['subject'] . '</td>';
					echo '<td>' . $user_productivity_row['date'] . '</td>';
					echo '<td>' . $user_productivity_row['time_rendered'] . ' Hours</td>';
					echo '<td>';
					// Determine task status and display badge
					if ($user_productivity_row['task_status'] == 1) {
						echo "<span class='badge p-2 badge-secondary'>Pending</span>";
					} elseif ($user_productivity_row['task_status'] == 2) {
						echo "<span class='badge p-2 badge-primary'>On-Progress</span>";
					} elseif ($user_productivity_row['task_status'] == 3) {
						echo "<span class='badge p-2 badge-success'>Done</span>";
					}
					echo '</td>';
					echo '</tr>';
				}
			}
		}
	} else {
		echo "Error executing query: " . $conn->error;
	}
}
