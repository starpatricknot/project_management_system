<?php if (!isset($conn)) {
	include 'db_connect.php';
} ?>

<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-project">

				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Name</label>
							<input type="text" class="form-control form-control-sm" name="name" placeholder="Please enter project name" required value="<?php echo isset($name) ? $name : '' ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="">Status</label>
							<select name="status" id="status" class="custom-select custom-select-sm" required>
								<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Pending</option>
								<option selected value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Started</option>
								<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>On-Progress</option>
								<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>On-Hold</option>
								<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Done</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Start Date</label>
							<input type="date" class="form-control form-control-sm" autocomplete="off" name="start_date" placeholder="Please enter start date" required value="<?php echo isset($start_date) ? date("Y-m-d", strtotime($start_date)) : '' ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">End Date</label>
							<input type="date" class="form-control form-control-sm" autocomplete="off" name="end_date" placeholder="Please enter end date" required value="<?php echo isset($end_date) ? date("Y-m-d", strtotime($end_date)) : '' ?>">
						</div>
					</div>
				</div>
				<div class="row">
					<?php if ($_SESSION['login_type'] == 1) : ?>
						<div class="col-md-6">
							<div class="form-group">
								<label for="" class="control-label">Project Manager</label>
								<select class="form-control form-control-sm select2" name="manager_id" required>
									<option value="" disabled selected>Select project manager</option>
									<?php
									$managers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 2 order by concat(firstname,' ',lastname) asc ");
									while ($row = $managers->fetch_assoc()) :
									?>
										<option value="<?php echo $row['id'] ?>" <?php echo isset($manager_id) && $manager_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
					<?php else : ?>
						<input type="hidden" name="manager_id" value="<?php echo $_SESSION['login_id'] ?>">
					<?php endif; ?>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Project Team Members</label>
							<select class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]" required>
								<option value="" disabled>Select team members</option>
								<?php
								$employees = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 3 order by concat(firstname,' ',lastname) asc ");
								while ($row = $employees->fetch_assoc()) :
								?>
									<option value="<?php echo $row['id'] ?>" <?php echo isset($user_ids) && in_array($row['id'], explode(',', $user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label for="" class="control-label">Description</label>
							<textarea name="description" id="" cols="30" rows="10" class="summernote form-control" required placeholder="Please enter description"><?php echo isset($description) ? $description : '' ?></textarea>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer border-top border-info">
			<div class="d-flex w-100 justify-content-center align-items-center">
				<button class="btn btn-flat bg-gradient-primary mx-2" form="manage-project">Save</button>
				<button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=project_list'">Cancel</button>
			</div>
		</div>
	</div>
</div>
<script>
	function validateForm() {
		let valid = true;
		$('#manage-project [required]').each(function() {
			if ($(this).val() === '') {
				$(this).addClass('is-invalid');
				valid = false;
			} else {
				$(this).removeClass('is-invalid');
			}
		});
		return valid;
	}

	$('#manage-project').submit(function(e) {
		e.preventDefault();
		if (!validateForm()) {
			alert_toast('Please fill all required fields', 'danger');
			return;
		}
		start_load();
		$.ajax({
			url: 'ajax.php?action=save_project',
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
						location.href = 'index.php?page=project_list';
					}, 1000);
				}
			}
		});
	});
</script>
