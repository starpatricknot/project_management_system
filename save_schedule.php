<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script> alert('Error: No data to save.'); location.replace('./') </script>";
    $conn->close();
    exit;
}

extract($_POST);
$allday = isset($allday);

// Retrieve employee's first name and last name from users table
$query = "SELECT firstname, lastname FROM users WHERE id = '$employee_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $employee_fname = $row['firstname'];
    $employee_lname = $row['lastname'];

    if (empty($id)) {
        $sql = "INSERT INTO `employee_schedule` (`schedule_name`,`employee_id`,`employee_fname`,`employee_lname`,`start_date`,`end_date`) VALUES ('$schedule_name','$employee_id','$employee_fname','$employee_lname','$start_date','$end_date')";
    } else {
        $sql = "UPDATE `employee_schedule` SET `schedule_name` = '{$schedule_name}', `employee_id` = '{$employee_id}', `employee_fname` = '{$employee_fname}', `employee_lname` = '{$employee_lname}', `start_date` = '{$start_date}', `end_date` = '{$end_date}' WHERE `id` = '{$id}'";
    }

    $save = $conn->query($sql);

    if ($save) {
        echo "<script> alert('Schedule Successfully Saved.'); location.replace('./index.php?page=schedule') </script>";
    } else {
        echo "<pre>";
        echo "An Error occurred.<br>";
        echo "Error: " . $conn->error . "<br>";
        echo "SQL: " . $sql . "<br>";
        echo "</pre>";
    }
} else {
    echo "<script> alert('Employee not found.'); location.replace('./index.php?page=schedule') </script>";
}

$conn->close();
