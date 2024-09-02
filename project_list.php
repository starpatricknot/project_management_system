<?php include 'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-dark">
		<div class="card-header">
			<div class="row pt-1">
				<div class="col">
					<h4><i class="fas fa-solid fa-building"></i> Project List</h4>
				</div>
				<?php if ($_SESSION['login_type'] != 3) : ?>
					<div class="col-auto">
						<div class="card-tools">
							<a class="btn btn-block btn-sm btn-default border-secondary text-dark" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Add New project</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="card-body">
			<div class="mb-3">
				<label for="status_filter">Filter by Status:</label>
				<select id="status_filter" class="form-control form-control-sm w-25">
					<option value="">All Status</option>
					<option value="0">Pending</option>
					<option value="1">Started</option>
					<option value="2">On-Progress</option>
					<option value="3">On-Hold</option>
					<option value="4">Over Due</option>
					<option value="5">Done</option>
				</select>
			</div>
			<div class="table-responsive">
				<table class="table m-0 table-hover table-condensed border" id="list">
					<colgroup>
						<col width="5%">
						<col width="35%">
						<col width="15%">
						<col width="15%">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Project</th>
							<th>Date Start</th>
							<th>Due Date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
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
						$qry = $conn->query("SELECT * FROM project_list $where order by name asc");
						while ($row = $qry->fetch_assoc()) :
							$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
							unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
							$desc = strtr(html_entity_decode($row['description']), $trans);
							$desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);

							$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
							$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 3")->num_rows;
							$prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
							$prog = $prog > 0 ?  number_format($prog, 2) : $prog;
							$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;

							if ($row['status'] == 0 && (new DateTime())->format('Y-m-d') > $row['end_date']) {
								$row['status'] = 4;
								$conn->query("UPDATE project_list SET status = 4 WHERE id = {$row['id']}");
							}
							if ($tprog > 0 && $tprog == $cprog) {
								$row['status'] = 5;
								$conn->query("UPDATE project_list SET status = 5 WHERE id = {$row['id']}");
							}
						?>
							<tr>
								<th class=""><?php echo $i++ ?></th>
								<td>
									<a href="./index.php?page=view_project&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><b><?php echo ucwords($row['name']) ?></b></a>
									<p class="truncate"><?php echo strip_tags($desc) ?></p>
								</td>
								<td><b><?php echo date("M d, Y", strtotime($row['start_date'])) ?></b></td>
								<td><b><?php echo date("M d, Y", strtotime($row['end_date'])) ?></b></td>
								<td class="">
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
								<td class="text-center">
									<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										Action
									</button>
									<div class="dropdown-menu">
										<a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>">View</a>
										<div class="dropdown-divider"></div>
										<?php if ($_SESSION['login_type'] != 3) : ?>
											<a class="dropdown-item" href="./index.php?page=edit_project&id=<?php echo $row['id'] ?>">Edit</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endwhile; ?>
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
	$(document).ready(function() {
		$('#list').dataTable();

		// Filter projects by status
		$('#status_filter').change(function() {
			var status = $(this).val();
			filterProjects(status);
		});

		// Event delegation for dynamically added delete buttons
		$(document).on('click', '.delete_project', function() {
			_conf("Are you sure to delete this project?", "delete_project", [$(this).attr('data-id')]);
		});
	});

	function filterProjects(status) {
		$.ajax({
			url: 'ajax.php?action=filter_projects',
			method: 'POST',
			data: {
				status: status
			},
			success: function(resp) {
				$('#list tbody').html(resp);
			}
		});
	}

	function delete_project($id) {
		start_load();
		$.ajax({
			url: 'ajax.php?action=delete_project',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success');
					setTimeout(function() {
						location.reload();
					}, 1500);
				}
			}
		});
	}
</script>