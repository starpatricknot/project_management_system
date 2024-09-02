<?php
include 'db_connect.php';
session_start();

if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM task_list where id = " . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
}

$logged_in_user_id = $_SESSION['login_id'];
?>
<div class="container-fluid">
	<form action="" id="manage-task">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<!-- <div class="form-group">
			<label for="">Task</label>
			<input type="text" class="form-control form-control-sm" name="task" value="<?php #echo isset($task) ? $task : '' 
																						?>" required>
		</div> -->
		<!-- <div class="form-group">
			<label for="">Description</label>
			<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
				<?php #echo isset($description) ? $description : '' 
				?>
			</textarea>
		</div> -->
		<div class="form-group">
			<label for="" class="control-label">Progress/Activity</label>
			<select class="form-control form-control-sm select2" name="productivity_id">
				<?php
				// Modify the query to filter by user_id and related task_id
				$tasks = $conn->query("SELECT * FROM user_productivity WHERE project_id = {$_GET['pid']} AND user_id = {$logged_in_user_id} AND task_id = {$id} ORDER BY subject ASC");
				if ($tasks->num_rows > 0) :
					while ($row = $tasks->fetch_assoc()) :
				?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($task_id) && $task_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['subject']); #echo $row['id'] 
																																			?></option>
					<?php
					endwhile;
				else :
					?>
					<option value="" disabled selected>You don't have productivity records available</option>
				<?php endif; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="">End Time</label>
			<input type="time" class="form-control form-control-sm" name="end_time" value="<?php echo isset($end_time) ? date("H:i", strtotime("2020-01-01 " . $end_time)) : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Status</label>
			<select name="status" id="status" class="custom-select custom-select-sm" required>
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Pending</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>On-Progress</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Done</option>
			</select>
		</div>
	</form>
</div>

<script>
	$(document).ready(function() {

		$('.summernote').summernote({
			height: 200,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
				['fontname', ['fontname']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ol', 'ul', 'paragraph', 'height']],
				['table', ['table']],
				['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
			]
		})
	})

	// $('#manage-task').submit(function(e) {
	// 	e.preventDefault();
	// 	start_load();
	// 	$.ajax({
	// 		url: 'ajax.php?action=update_task_status',
	// 		data: new FormData($(this)[0]),
	// 		cache: false,
	// 		contentType: false,
	// 		processData: false,
	// 		method: 'POST',
	// 		type: 'POST',
	// 		success: function(resp) {
	// 			if (resp === 1) {
	// 				Swal.fire({
	// 					title: 'Success!',
	// 					text: 'Data successfully saved',
	// 					icon: 'success',
	// 					confirmButtonText: 'OK'
	// 				}).then((result) => {
	// 					if (result.isConfirmed) {
	// 						location.reload();
	// 					}
	// 				});
	// 			}
	// 		}
	// 	});
	// });

	$('#manage-task').submit(function(e) {
		e.preventDefault();
		let valid = true;

		// Check required fields
		$('#manage-task [required]').each(function() {
			if ($(this).val() === '') {
				valid = false;
				$(this).addClass('is-invalid');
			} else {
				$(this).removeClass('is-invalid');
			}
		});

		if (!valid) {
			alert_toast('Please fill all required fields', 'error');
			return false;
		}

		start_load();
		$.ajax({
			url: 'ajax.php?action=update_task_status',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast('Data successfully saved', "success");
					setTimeout(function() {
						location.reload();
					}, 1500);
				}
			}
		});
	});
</script>