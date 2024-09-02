<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
	// Fetch task details
	$qry = $conn->query("SELECT * FROM task_list WHERE id = " . $_GET['id'])->fetch_array();

	// Fetch associated users for the task
	$users_result = $conn->query(
		"SELECT 
			GROUP_CONCAT(DISTINCT CONCAT(u.firstname, ' ', u.lastname) SEPARATOR ', ') AS assigned_users
        FROM user_productivity up
        JOIN users u ON up.user_id = u.id
        WHERE up.task_id = " . $_GET['id']
	);

	// Create an array to store assigned users
	$assigned_users = [];
	while ($user = $users_result->fetch_assoc()) {
		$assigned_users[] = $user['assigned_users'];
	}

	// Assign fetched users to a variable
	$assigned_users_str = implode(', ', $assigned_users);

	// Assign task details and assigned users to variables with the same names
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
	$assigned_users = $assigned_users_str;
}
?>

<div class="container-fluid">
	<dl>
		<dt><b class="border-bottom border-primary">Task</b></dt>
		<dd><?php echo ucwords($task) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Status</b></dt>

		<dd>
			<?php
			if ($status == 1) {
				echo "<span class='badge badge-secondary'>Pending</span>";
			} elseif ($status == 2) {
				echo "<span class='badge badge-primary'>On-Progress</span>";
			} elseif ($status == 3) {
				echo "<span class='badge badge-success'>Done</span>";
			}
			?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Employee/s</b></dt>
		<dd><?php echo $assigned_users ? $assigned_users : 'N/A'; ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Description</b></dt>
		<dd><?php echo html_entity_decode($description) ?></dd>
	</dl>
	<?php if($remarks != 'N/A') { ?>
	<dl>
		<dt><b class="border-bottom border-primary">Remarks</b></dt>
		<dd><?php echo html_entity_decode($remarks) ?></dd>
	</dl>
	<?php } ?>
</div>