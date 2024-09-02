<?php include 'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-secondary">
		<div class="card-header">
			<div class="row pt-1">
				<div class="col">
					<h4><i class="fa fa-tasks"></i> Task List</h4>
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
			<div class="table-responsive">
				<table class="table m-0 table-hover table-condensed border" id="list">

					<div class="mb-3">
						<label for="project_filter">Filter by Project:</label>
						<select id="project_filter" class="form-control form-control-sm w-25">
							<option value="">All Projects</option>
							<?php
							$result = $conn->query("SELECT * FROM project_list ORDER BY name ASC");
							while ($row = $result->fetch_assoc()) {
								echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
							}
							?>
						</select>
					</div>

					<colgroup>
						<col width="5%">
						<col width="15%">
						<col width="20%">
						<col width="15%">
						<col width="15%">
						<col width="10%">
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
							<th>Action</th>
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
						$qry = $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where order by p.name asc");
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
								<td class="text-center">
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
								<td>
									<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										Action
									</button>
									<div class="dropdown-menu">
										<a class="dropdown-item new_productivity" data-pid='<?php echo $row['pid'] ?>' data-tid='<?php echo $row['id'] ?>' data-task='<?php echo ucwords($row['task']) ?>' href="javascript:void(0)">Add Productivity</a>
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
		$('#list').dataTable()

		// Filter projects by status
		$('#project_filter').change(function() {
			var status = $(this).val();
			filterProjects(status);
		})

		// Event delegation for dynamically added .new_productivity buttons
		$(document).on('click', '.new_productivity', function() {
			uni_modal("<i class='fa fa-plus'></i> New Progress for: " + $(this).attr('data-task'), "new_progress.php?pid=" + $(this).attr('data-pid') + "&tid=" + $(this).attr('data-tid'), 'large')
		});
	});

	function filterProjects(projectId) {
		$.ajax({
			url: 'ajax.php?action=filter_tasks',
			method: 'POST',
			data: {
				id: projectId
			},
			success: function(resp) {
				$('#list tbody').html(resp);
			}
		});
	}

	function delete_project($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_project',
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
</script>