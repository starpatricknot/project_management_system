<?php
session_start();
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '" . $email . "' and password = '" . md5($password) . "'  ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			return 2;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM students where student_code = '" . $student_code . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['rs_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function save_user()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'password')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (!empty($password)) {
			$data .= ", password=md5('$password') ";
		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");
		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function signup()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass')) && !is_numeric($k)) {
				if ($k == 'password') {
					if (empty($v))
						continue;
					$v = md5($v);
				}
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");
		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			if (empty($id))
				$id = $this->db->insert_id;
			foreach ($_POST as $key => $value) {
				if (!in_array($key, array('id', 'cpass', 'password')) && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$_SESSION['login_id'] = $id;
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}

	function update_user()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'table', 'password')) && !is_numeric($k)) {

				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM users where email ='$email' " . (!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2;
			exit;
		}
		if (isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		if (!empty($password))
			$data .= " ,password=md5('$password') ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set $data");
		} else {
			$save = $this->db->query("UPDATE users set $data where id = $id");
		}

		if ($save) {
			foreach ($_POST as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if (isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
				$_SESSION['login_avatar'] = $fname;
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function save_system_settings()
	{
		extract($_POST);
		$data = '';
		foreach ($_POST as $k => $v) {
			if (!is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if ($_FILES['cover']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['cover']['name'];
			$move = move_uploaded_file($_FILES['cover']['tmp_name'], '../assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set $data where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set $data");
		}
		if ($save) {
			foreach ($_POST as $k => $v) {
				if (!is_numeric($k)) {
					$_SESSION['system'][$k] = $v;
				}
			}
			if ($_FILES['cover']['tmp_name'] != '') {
				$_SESSION['system']['cover_img'] = $fname;
			}
			return 1;
		}
	}
	function save_image()
	{
		extract($_FILES['file']);
		if (!empty($tmp_name)) {
			$fname = strtotime(date("Y-m-d H:i")) . "_" . (str_replace(" ", "-", $name));
			$move = move_uploaded_file($tmp_name, 'assets/uploads/' . $fname);
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
			$hostName = $_SERVER['HTTP_HOST'];
			$path = explode('/', $_SERVER['PHP_SELF']);
			$currentPath = '/' . $path[1];
			if ($move) {
				return $protocol . '://' . $hostName . $currentPath . '/assets/uploads/' . $fname;
			}
		}
	}
	// function save_project()
	// {
	// 	extract($_POST);
	// 	$data = "";
	// 	foreach ($_POST as $k => $v) {
	// 		if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
	// 			if ($k == 'description')
	// 				$v = htmlentities(str_replace("'", "&#x2019;", $v));
	// 			if (empty($data)) {
	// 				$data .= " $k='$v' ";
	// 			} else {
	// 				$data .= ", $k='$v' ";
	// 			}
	// 		}
	// 	}
	// 	if (isset($user_ids)) {
	// 		$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
	// 	}
	// 	// echo $data;exit;
	// 	if (empty($id)) {
	// 		$save = $this->db->query("INSERT INTO project_list set $data");
	// 		if ($save) {
	// 			$project_id = $this->db->insert_id;
	// 			foreach ($user_ids as $user_id) {
	// 				$this->db->query("INSERT INTO notifications (user_id, project_id, message, created_at) VALUES ('$user_id', '$project_id', 'You have been added into a new project named $name.', NOW())");
	// 			}
	// 			return 1;
	// 		}
	// 	} else {
	// 		$save = $this->db->query("UPDATE project_list set $data where id = $id");
	// 		if ($save) {
	// 			foreach ($user_ids as $user_id) {
	// 				$this->db->query("INSERT INTO notifications (user_id, project_id, message, created_at) VALUES ('$user_id', '$id', 'The $name project details have been updated.', NOW())");
	// 			}
	// 			return 1;
	// 		}
	// 	}
	// }



	function save_project()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if ($k == 'description')
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (isset($user_ids)) {
			$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
		}
		// echo $data;exit;
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO project_list set $data");
			if ($save) {
				$project_id = $this->db->insert_id;
				foreach ($user_ids as $user_id) {
					$this->db->query("INSERT INTO notifications (user_id, project_id, message, created_at) VALUES ('$user_id', '$project_id', 'You have been added into a new project named $name.', NOW())");
				}
				$this->send_email_notification($user_ids, "You have been added into a new project named $name. Collaborate with your teammates to ensure the project's success and timely completion. Let's achieve great results together!");
				return 1;
			}
		} else {
			$save = $this->db->query("UPDATE project_list set $data where id = $id");
			if ($save) {
				foreach ($user_ids as $user_id) {
					$this->db->query("INSERT INTO notifications (user_id, project_id, message, created_at) VALUES ('$user_id', '$id', 'The $name project details have been updated.', NOW())");
				}
				$this->send_email_notification($user_ids, "The $name project details have been updated. Please review the changes and continue working towards the project goals. Thank you for your dedication and hard work!");
				return 1;
			}
		}
	}

	function send_email_notification($user_ids, $message)
	{

		$users_query = implode(',', $user_ids);
		$users_result = $this->db->query("SELECT email FROM users WHERE id IN ($users_query)");

		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host       = 'smtp-relay.brevo.com';
			$mail->SMTPAuth   = true;
			$mail->Username   = '753189001@smtp-brevo.com';
			$mail->Password   = 'xsmtpsib-0c121cb66737e425373d800846d325058d05dfaf11144698d974d57985df55c8-3L9rCXMUzPT47jvS';
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port       = 587;

			//Recipients
			$mail->setFrom('753189001@smtp-brevo.com', 'Admin');


			while ($user = $users_result->fetch_assoc()) {
				$mail->addAddress($user['email']);
			}


			$mail->isHTML(true);
			$mail->Subject = 'Project Notification';
			$mail->Body = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Project Notification</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background: #fff;
                        padding: 20px;
                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    h2 {
                        color: #333;
                        font-size: 24px;
                        margin-bottom: 20px;
                    }
                    p {
                        font-size: 16px;
                        color: #666;
                        margin-bottom: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Project Notification</h2>
                    <p>$message</p>
                    <hr>
                    <p>This is an automated email. Please do not reply.</p>
                </div>
            </body>
            </html>
        ";
			$mail->AltBody = strip_tags($message);

			$mail->send();
		} catch (Exception $e) {
			error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
		}
	}








	function delete_project()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM project_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_task()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if ($k == 'description')
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				if ($k == 'remarks') // Handle the remarks field separately
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
			if (isset($user_ids)) {
				$data .= ", user_ids='" . implode(',', $user_ids) . "' ";
			}
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO task_list SET $data");
		} else {
			$save = $this->db->query("UPDATE task_list SET $data WHERE id = $id");
		}
		if ($save) {
			return 1;
		}
	}

	function update_task_status()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if ($k == 'description')
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}

		$query_up = $this->db->query("SELECT * FROM user_productivity WHERE id = $productivity_id");

		if ($query_up->num_rows > 0) {
			// If user has existing productivity, update it
			$row_up = $query_up->fetch_assoc();
			$start_time = $row_up['start_time'];
			if (!empty($end_time)) {
				$start_datetime = strtotime("2020-01-01 " . $start_time);
				$end_datetime = strtotime("2020-01-01 " . $end_time);
				$duration_seconds = abs($end_datetime - $start_datetime);
				$duration_hours = $duration_seconds / 3600;
			}
		}

		if (!empty($end_time)) {
			$update_task_status = $this->db->query("UPDATE task_list SET status = $status WHERE id = $id");
			$update_progress = $this->db->query("UPDATE user_productivity SET end_time = '$end_time', time_rendered = $duration_hours WHERE id = $productivity_id");
		}

		if ($update_task_status && $update_progress) {
			return 1;
		}
	}
	function delete_task()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM task_list where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function save_progress()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_numeric($k)) {
				if ($k == 'comment')
					$v = htmlentities(str_replace("'", "&#x2019;", $v));
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		// $dur = abs(strtotime("2020-01-01 " . $end_time)) - abs(strtotime("2020-01-01 " . $start_time));
		// $dur = $dur / (60 * 60);
		// $data .= ", time_rendered='$dur' ";
		// echo "INSERT INTO user_productivity set $data"; exit;
		if (empty($id)) {
			$data .= ", user_id={$_SESSION['login_id']} ";

			$save = $this->db->query("INSERT INTO user_productivity set $data");
		} else {
			$save = $this->db->query("UPDATE user_productivity set $data where id = $id");
		}
		if ($save) {
			return 1;
		}
	}
	function delete_progress()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM user_productivity where id = $id");
		if ($delete) {
			return 1;
		}
	}
	function get_report()
	{
		extract($_POST);
		$data = array();
		$get = $this->db->query("SELECT t.*,p.name as ticket_for FROM ticket_list t inner join pricing p on p.id = t.pricing_id where date(t.date_created) between '$date_from' and '$date_to' order by unix_timestamp(t.date_created) desc ");
		while ($row = $get->fetch_assoc()) {
			$row['date_created'] = date("M d, Y", strtotime($row['date_created']));
			$row['name'] = ucwords($row['name']);
			$row['adult_price'] = number_format($row['adult_price'], 2);
			$row['child_price'] = number_format($row['child_price'], 2);
			$row['amount'] = number_format($row['amount'], 2);
			$data[] = $row;
		}
		return json_encode($data);
	}

	function get_employee_name()
	{
		extract($_POST);

		if (isset($_POST['employee_id'])) {
			$employee_id = $_POST['employee_id'];
			$query = "SELECT CONCAT(u.firstname, ' ', u.lastname) AS name 
					  FROM employee_schedule es 
					  INNER JOIN users u ON es.employee_id = u.id 
					  WHERE es.employee_id = $employee_id";
			$result = $this->db->query($query);
			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				echo $row['name'];
			} else {
				echo "Employee not found";
			}
		}
	}
}
