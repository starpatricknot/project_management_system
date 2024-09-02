  <aside class="main-sidebar sidebar-dark-light bg-custom-img elevation-4">
    <div class="dropdown">
      <a href="./" class="brand-link bg-custom-img">
        <?php if ($_SESSION['login_type'] == 1) : ?>
          <h3 class="text-center p-0 m-0"><i class=" fas fa-solid fa-user-tie"></i><b> ADMIN</b></h3>
        <?php else : ?>
          <h3 class="text-center p-0 m-0"><i class="fas fa-solid fa-user"></i><b> EMPLOYEE</b></h3>
        <?php endif; ?>

      </a>

    </div>
    <div class="sidebar pb-4 mb-4">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-solid fa-chart-line"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_project nav-view_project">
              <i class="nav-icon fas fa-solid fa-building"></i>
              <p>
                Projects
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if ($_SESSION['login_type'] != 3) : ?>
                <li class="nav-item">
                  <a href="./index.php?page=new_project" class="nav-link nav-new_project tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>Add New</p>
                  </a>
                </li>
              <?php endif; ?>
              <li class="nav-item">
                <a href="./index.php?page=project_list" class="nav-link nav-project_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="./index.php?page=task_list" class="nav-link nav-task_list">
              <i class="fas fa-tasks nav-icon"></i>
              <p>Task</p>
            </a>
          </li>
          <?php if ($_SESSION['login_type'] != 3) : ?>
            <li class="nav-item">
              <a href="./index.php?page=schedule" class="nav-link nav-schedule tree-item">
                <i class="fas fa-solid fa-calendar nav-icon"></i>
                <p>Schedule</p>
              </a>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['login_type'] != 3) : ?>
            <!-- <li class="nav-item">
              <a href="./index.php?page=reports" class="nav-link nav-reports">
                <i class="fas fa-solid fa-print nav-icon"></i>
                <p>Report</p>
              </a>
            </li> -->
            <li class="nav-item">
              <a href="#" class="nav-link nav-link nav-reports">
                <i class="fas fa-solid fa-print nav-icon"></i>
                <p>
                  Report
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./index.php?page=reports" class="nav-link nav-reports tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>Project Progress Report</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./index.php?page=reports_employee" class="nav-link nav-reports_employee tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>Employee Progress Report</p>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['login_type'] == 1) : ?>
            <li class="nav-item">
              <a href="#" class="nav-link nav-edit_user">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Employees
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>Add New</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                    <i class="fas fa-angle-right nav-icon"></i>
                    <p>List</p>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>
          <li class="d-block d-sm-none nav-item">
            <a href="ajax.php?action=logout" class="nav-link nav-logout">
              <i class="fas fa-power-off nav-icon"></i>
              <p>Logout</p>
            </a>
          </li>

        </ul>
      </nav>
    </div>
  </aside>
  <script>
    $(document).ready(function() {
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
      var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if (s != '')
        page = page + '_' + s;
      if ($('.nav-link.nav-' + page).length > 0) {
        $('.nav-link.nav-' + page).addClass('active')
        if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
          $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
          $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
        }
        if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
          $('.nav-link.nav-' + page).parent().addClass('menu-open')
        }

      }

    })
  </script>