<?php
// Fetch notifications for the logged-in user from the database
// Assuming you have a database connection and the user's ID is stored in $_SESSION['login_id']
// Modify this query according to your database schema
$notifications_query = $conn->query("SELECT * FROM notifications WHERE user_id = {$_SESSION['login_id']}");
$notifications_count = $notifications_query->num_rows;
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-dark pb-3 mb-5">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <?php if (isset($_SESSION['login_id'])) : ?>
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="" role="button">
          <img src="assets/images/pms-logo-2.png" alt="" class="img-fluid" style="max-height: 30px; max-width: 400px;">
        </a>
      </li>
    <?php endif; ?>
    <li>
      <!-- <a class="nav-link text-white"  href="./" role="button"> <large><b><?php #echo $_SESSION['system']['name'] 
                                                                              ?></b></large>  </a> -->
    </li>
  </ul>


  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="position-relative">
          <i class="fas fa-bell"></i>
          <?php if ($notifications_count > 0) : ?>
            <span class="badge badge-pill badge-danger"><?php echo $notifications_count; ?></span>
          <?php endif; ?>
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <?php if ($notifications_count > 0) : ?>
          <?php while ($notification = $notifications_query->fetch_assoc()) : ?>
            <div class="notification-item d-flex justify-content-between align-items-center">
              <a class="dropdown-item" href="./index.php?page=view_project&id=<?php echo $notification['project_id'] ?>"><?php echo $notification['message']; ?></a>
              <span class="remove-notification" data-notification-id="<?php echo $notification['id']; ?>"><i class="fas fa-times px-3" style=""></i></span>
            </div>
          <?php endwhile; ?>
        <?php else : ?>
          <a class="dropdown-item" href="#">You don't have any notifications</a>
        <?php endif; ?>
      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
        <span>
          <div class="d-felx badge-pill">
            <span class="fa fa-user mr-2"></span>
            <span><b><?php echo ucwords($_SESSION['login_firstname']) ?></b></span>
            <span class="fa fa-angle-down ml-2"></span>
          </div>
        </span>
      </a>
      <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
        <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage Account</a>
        <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

<script>
  // JavaScript to handle notification removal
  $(document).ready(function() {
    $(".remove-notification").click(function(e) {
      e.stopPropagation(); // Prevent dropdown from closing

      var notificationId = $(this).data("notification-id");

      // Send AJAX request to delete notification from the database
      $.ajax({
        url: "ajax.php?action=remove_notification",
        method: "POST",
        data: {
          notification_id: notificationId
        },
        success: function(response) {
          if (response == "success") {
            // Remove the notification item from the UI
            $(this).closest(".notification-item").remove();

            // Update notification count in the UI
            var notificationCount = parseInt($("#navbarDropdown .badge").text());
            $("#navbarDropdown .badge").text(notificationCount - 1);
            // Reload the page
            location.reload();
          } else {
            // Handle error if needed
            alert("Failed to remove notification.");
          }
        }
      });
    });

    // Prevent dropdown from closing when clicking on remove icon
    $(".remove-notification").click(function(e) {
      e.stopPropagation();
    });

    // Redirect to project page when clicking on notification link
    $(".notification-link").click(function() {
      var projectUrl = $(this).attr("href");
      window.location.href = projectUrl;
    });
  });

  $('#manage_account').click(function() {
    uni_modal('Manage Account', 'manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
  });
</script>