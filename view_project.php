<?php
include 'db_connect.php';
$stat = array("Pending", "Started", "On-Progress", "On-Hold", "Over Due", "Done");
$qry = $conn->query("SELECT * FROM project_list where id = " . $_GET['id'])->fetch_array();
foreach ($qry as $k => $v) {
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM task_list where project_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM task_list where project_id = {$id} and status = 3")->num_rows;
$prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
$prog = $prog > 0 ?  number_format($prog, 2) : $prog;
$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$id}")->num_rows;

// Update project status based on conditions
if ($status == 0 && (new DateTime())->format('Y-m-d') >= $start_date) {
	if ($prod > 0 || $cprog > 0) {
		$status = 2; // Set status to "On-Progress"
		$conn->query("UPDATE project_list SET status = 2 WHERE id = {$id}");
	} else {
		$status = 1; // Set status to "Started"
		$conn->query("UPDATE project_list SET status = 1 WHERE id = {$id}");
	}
} elseif ($status == 0 && (new DateTime())->format('Y-m-d') > $end_date) {
	$status = 4; // Set status to "Over Due"
	$conn->query("UPDATE project_list SET status = 4 WHERE id = {$id}");
}

// Update project status based on task statuses only if the project is not "On-Hold"
if ($status != 3) {
	// Check if all tasks are done or if any task is in progress
	$all_done = true;
	$any_in_progress = false;
	$tasks = $conn->query("SELECT * FROM task_list WHERE project_id = {$id}");
	while ($task = $tasks->fetch_assoc()) {
		if ($task['status'] != 3) {
			$all_done = false;
			if ($task['status'] == 2) {
				$any_in_progress = true;
			}
		}
	}

	// Update project status based on task statuses
	if ($all_done) {
		$status = 5; // Set status to "Done"
	} elseif ($any_in_progress) {
		$status = 2; // Set status to "On-Progress"
	}
	// Update project status in the database
	$conn->query("UPDATE project_list SET status = $status WHERE id = {$id}");
}


$conn->query("UPDATE project_list SET status = $status WHERE id = {$id}");

// // Check if all tasks are done
// if ($tprog > 0) {
// 	if ($tprog == $cprog) {
// 		$status = 5; // Set status to "Done"
// 	} elseif ($cprog > 0) {
// 		$status = 2; // Set status to "On-Progress" if some tasks are completed
// 	}
// 	$conn->query("UPDATE project_list SET status = $status WHERE id = {$id}");
// }

$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();
?>
<div class="col-lg-12">
	<div class="row">

		<div class="col-md-12">
			<div class="callout callout-info">
				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-6">
							<dl>
								<dt><b class="border-bottom border-primary">Project Name</b></dt>
								<dd><?php echo ucwords($name) ?></dd>
								<dt><b class="border-bottom border-primary">Description</b></dt>
								<dd><?php echo html_entity_decode($description) ?></dd>
							</dl>
						</div>
						<div class="col-md-5">
							<dl>
								<dt><b class="border-bottom border-primary">Start Date</b></dt>
								<dd><?php echo date("F d, Y", strtotime($start_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">End Date</b></dt>
								<dd><?php echo date("F d, Y", strtotime($end_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Status</b></dt>
								<dd>
									<?php
									if ($stat[$status] == 'Pending') {
										echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Started') {
										echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'On-Progress') {
										echo "<span class='badge badge-info'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'On-Hold') {
										echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Over Due') {
										echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
									} elseif ($stat[$status] == 'Done') {
										echo "<span class='badge badge-success'>{$stat[$status]}</span>";
									}
									?>
								</dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Project Manager</b></dt>
								<dd>
									<?php if (isset($manager['id'])) : ?>
										<div class="d-flex align-items-center mt-1">
											<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $manager['avatar'] ?>" alt="Avatar">
											<b><?php echo ucwords($manager['name']) ?></b>
										</div>
									<?php else : ?>
										<small><i>Manager Deleted from Database</i></small>
									<?php endif; ?>
								</dd>
							</dl>
						</div>
						<div class="col-md-1">
							<div class="row justify-content-end">
								<div class="mb-4">
									<?php if ($_SESSION['login_type'] != 3) : ?>
										<a href="./index.php?page=edit_project&id=<?php echo $qry['id'] ?>" class="btn btn-primary bg-gradient-primary btn-sm text-light" style="text-decoration:none">Edit <i class="fa fa-edit"></i></a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Team Member/s:</b></span>
					<div class="card-tools">
						<!-- <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="manage_team">Manage</button> -->
					</div>
				</div>
				<div class="card-body">
					<ul class="users-list clearfix">
						<?php
						if (!empty($user_ids)) :
							$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
							while ($row = $members->fetch_assoc()) :
						?>
								<li>
									<img src="assets/uploads/<?php echo $row['avatar'] ?>" alt="User Image">
									<a class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></a>
									<!-- <span class="users-list-date">Today</span> -->
								</li>
						<?php
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Task List:</b></span>
					<?php if ($_SESSION['login_type'] != 3) : ?>
						<div class="card-tools">
							<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_task"><i class="fa fa-plus"></i> New Task</button>
						</div>
					<?php endif; ?>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-condensed m-0 table-hover">
							<colgroup>
								<col width="5%">
								<col width="25%">
								<col width="30%">
								<col width="15%">
								<col width="15%">
							</colgroup>
							<thead>
								<th>#</th>
								<th>Task</th>
								<th>Description</th>
								<th>Remarks</th>
								<th>Employee/s</th>
								<th>Status</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php
								$i = 1;
								// Fetch tasks along with users assigned to each task
								$tasks = $conn->query(
									"SELECT tl.*, 
											GROUP_CONCAT(DISTINCT CONCAT(u.firstname, ' ', u.lastname) SEPARATOR ', ') AS assigned_users
									FROM task_list tl
									LEFT JOIN user_productivity up ON tl.id = up.task_id
									LEFT JOIN users u ON up.user_id = u.id
									WHERE tl.project_id = {$id}
									GROUP BY tl.id
									ORDER BY tl.task ASC"
								);
								while ($row = $tasks->fetch_assoc()) :
									$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
									unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
									$desc = strtr(html_entity_decode($row['description']), $trans);
									$desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);

									foreach ($tasks as $task) {
								?>
										<tr>
											<td class="text-center"><?php echo $i++ ?></td>
											<td class=""><b><?php echo ucwords($task['task']) ?></b></td>
											<td class="">
												<p class="truncate"><?php echo strip_tags($task['description']) ?></p>
											</td>
											<td class="">
												<p class="truncate"><?php echo strip_tags($task['remarks'] ?? 'N/A'); ?></p>

											</td>
											<td>
												<?php
												// Display all assigned users for this task
												echo $task['assigned_users'] ?? 'N/A';
												?>
											</td>
											<td>
												<?php
												if ($task['status'] == 1) {
													echo "<span class='badge badge-secondary'>Pending</span>";
												} elseif ($task['status'] == 2) {
													echo "<span class='badge badge-primary'>On-Progress</span>";
												} elseif ($task['status'] == 3) {
													echo "<span class='badge badge-success'>Done</span>";
												}
												?>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
													Action
												</button>
												<div class="dropdown-menu">
													<a class="dropdown-item view_task" href="javascript:void(0)" data-id="<?php echo $task['id'] ?>" data-task="<?php echo $task['task'] ?>">View</a>
													<div class="dropdown-divider"></div>
													<?php if ($_SESSION['login_type'] == 3) : ?>
														<a class="dropdown-item update_task_status" href="javascript:void(0)" data-id="<?php echo $task['id'] ?>" data-task="<?php echo $task['task'] ?>">Update Task Status</a>
													<?php endif; ?>
													<?php if ($_SESSION['login_type'] != 3) : ?>
														<a class="dropdown-item edit_task" href="javascript:void(0)" data-id="<?php echo $task['id'] ?>" data-task="<?php echo $task['task'] ?>">Edit</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $task['id'] ?>">Delete</a>
													<?php endif; ?>
												</div>
											</td>
										</tr>
								<?php
									}
								endwhile;
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-header">
			<b>Members Progress/Activity</b>
			<div class="card-tools">
				<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_productivity"><i class="fa fa-plus"></i> New Productivity</button>
			</div>
		</div>
		<div class="card-body">

			<?php
			$progress = $conn->query(
				"SELECT p.*,concat(u.firstname,' ',u.lastname) as uname,u.avatar,t.task 
             FROM user_productivity p 
             inner join users u on u.id = p.user_id 
             inner join task_list t on t.id = p.task_id 
             where p.project_id = $id 
             order by unix_timestamp(p.date_created) desc "
			);
			while ($row = $progress->fetch_assoc()) :
			?>
				<div class="card">
					<div class="post">
						<div class="user-block">
							<div class="card-header">
								<?php if ($_SESSION['login_id'] == $row['user_id'] || $_SESSION['login_id'] == 1) : ?>
									<span class="btn-group dropleft float-right">
										<span class="btndropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
											<i class="fa fa-ellipsis-v"></i>
										</span>
										<div class="dropdown-menu">
											<a class="dropdown-item manage_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['task'] ?>">Edit</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item delete_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
										</div>
									</span>
								<?php endif; ?>
								<img class="img-circle img-bordered-sm" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="user image">
								<span class="username">
									<?php if ($_SESSION['login_id'] == $row['user_id'] || $_SESSION['login_id'] == 1) : ?>
										<a class="manage_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['task'] ?>"><?php echo ucwords($row['uname']) ?>[ <?php echo ucwords($row['task']) ?> ]</a>
									<?php else : ?>
										<a class="text-primary"><?php echo ucwords($row['uname']) ?>[ <?php echo ucwords($row['task']) ?> ]</a>
									<?php endif; ?>
								</span>

								<span class="description mt-1">
									<span class="fa fa-calendar-day"></span>
									<span><b><?php echo date('M d, Y', strtotime($row['date'])) ?></b> | </span>
									<span class="fa fa-user-clock"></span>
									<span> Start: <b><?php echo date('h:i A', strtotime($row['date'] . ' ' . $row['start_time'])) ?></b></span>
									<?php if ($row['end_time'] != NULL) { ?>
										<span> | </span>
										<span> End: <b><?php echo date('h:i A', strtotime($row['date'] . ' ' . $row['end_time'])) ?></b></span>
									<?php } ?>
								</span>
							</div>
						</div>
						<div class="px-3">
							<?php echo html_entity_decode($row['comment']); ?>
						</div>
						<p>
							<!-- <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a> -->
						</p>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
</div>
<style>
	.users-list>li img {
		border-radius: 50%;
		height: 67px;
		width: 67px;
		object-fit: cover;
	}

	.users-list>li {
		width: 33.33% !important
	}

	.truncate {
		-webkit-line-clamp: 1 !important;
	}
</style>
<script>
	$('#new_task').click(function() {
		uni_modal("New Task For <?php echo ucwords($name) ?>", "manage_task.php?pid=<?php echo $id ?>", "mid-large")
	})
	$('.edit_task').click(function() {
		uni_modal("Edit Task: " + $(this).attr('data-task'), "manage_task.php?pid=<?php echo $id ?>&id=" + $(this).attr('data-id'), "mid-large")
	})
	$('.update_task_status').click(function() {
		uni_modal("Update Task Status: " + $(this).attr('data-task'), "update_task_status.php?pid=<?php echo $id ?>&id=" + $(this).attr('data-id'), "mid-large")
	})
	$('.view_task').click(function() {
		console.log("View task clicked");
		console.log("Task ID:", $(this).attr('data-id'));
		uni_modal_view("Task Details", "view_task.php?id=" + $(this).attr('data-id'), "mid-large")
	})
	$('#new_productivity').click(function() {
		uni_modal("<i class='fa fa-plus'></i> New Progress", "manage_progress.php?pid=<?php echo $id ?>", 'large')
	})
	$('.manage_progress').click(function() {
		uni_modal("<i class='fa fa-edit'></i> Edit Progress", "manage_progress.php?pid=<?php echo $id ?>&id=" + $(this).attr('data-id'), 'large')
	})
	$('.delete_progress').click(function() {
		_conf("Are you sure to delete this progress?", "delete_progress", [$(this).attr('data-id')])
	})
	$('.delete_task').click(function() {
		_conf("Are you sure to delete this task?", "delete_task", [$(this).attr('data-id')])
	})

	function delete_progress($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_progress',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}

	function delete_task($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_task',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}

	function uni_modal_view(title = '', url = '', size = 'lg') {
		$.ajax({
			url: url,
			error: err => {
				console.log(err)
				alert("An error occurred while loading the content.")
			},
			success: function(resp) {
				$('#uni_modal_view .modal-title').html(title)
				$('#uni_modal_view .modal-body').html(resp)
				$('#uni_modal_view .modal-dialog').removeClass('modal-md modal-xl modal-lg').addClass('modal-lg')
				$('#uni_modal_view').modal('show')
			}
		})
	}
</script>