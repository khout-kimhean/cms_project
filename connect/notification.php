<?php

// include '../templates/check_access.php';
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

// Create a new database connection for displaying users
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$error = array();

if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $delete_sql = "DELETE FROM login_register WHERE id = $user_id";
    if ($conn->query($delete_sql) === TRUE) {
        header('Location: showuser.php'); // Redirect to refresh the user list
        exit();
    } else {
        $error[] = 'Error deleting user: ' . $conn->error;
    }
}
// Retrieve user data from the database
$sql = "SELECT * FROM login_register";
$res = $conn->query($sql);
$count = 0;
$cont1 = 0;

$query_1 = "UPDATE user_move SET number =1 WHERE number =0";
$result = mysqli_query($conn, $sql);

$query_1 = "SELECT * FROM user_move WHERE number =0";
$result = mysqli_query($conn, $query_1);
// $count = mysqli_num_rows($result);

$currentDate = date('Y-m-d');

$sql = "SELECT display_name, branch, position, request_date, end_date FROM user_move WHERE end_date IS NOT NULL AND end_date <= ? ORDER BY id DESC ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

$reminders = [];

while ($row = $result->fetch_assoc()) {
    array_push($reminders, $row);
}

$stmt->close();
?>