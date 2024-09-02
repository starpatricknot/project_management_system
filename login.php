<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('./db_connect.php');
ob_start();
// if(!isset($_SESSION['system'])){

$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach ($system as $k => $v) {
  $_SESSION['system'][$k] = $v;
}
// }
ob_end_flush();
?>
<?php
if (isset($_SESSION['login_id']))
  header("location:index.php?page=home");

?>
<?php include 'header.php' ?>

<body class="hold-transition login-page bg-custom-img">
  <div class="login-box shadow-lg" style="width: 100%; max-width: 30rem;">
    <div class="card">
      <div class="card-header my-3">
        <div class="login-logo">
          <a class="" data-widget="pushmenu" href="" role="button"><img class="img-fluid" src="assets/uploads/pms-logo.png" alt=""></a>
        </div>
      </div>
      <!-- /.login-logo -->
      <div class="card-body login-card-body mt-2 mb-5">
        <form action="" id="login-form">
          <label for="email" class="input-group">Email:</label>
          <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" required placeholder="Enter email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <label for="email" class="form-label">Password:</label>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" required placeholder="Enter password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
            </div>
            <!-- /.col -->
            <div class="col-md-4">
              <button type="submit" class="btn btn-secondary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->
  <script>
    $(document).ready(function() {
      $('#login-form').submit(function(e) {
        e.preventDefault()
        start_load()
        if ($(this).find('.alert-danger').length > 0)
          $(this).find('.alert-danger').remove();
        $.ajax({
          url: 'ajax.php?action=login',
          method: 'POST',
          data: $(this).serialize(),
          error: err => {
            console.log(err)
            end_load();

          },
          success: function(resp) {
            if (resp == 1) {
              location.href = 'index.php?page=home';
            } else {
              $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
              end_load();
            }
          }
        })
      })
    })
  </script>
  <?php include 'footer.php' ?>

</body>

</html>