<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'msg' is set in the POST data
$userMessage = isset($_POST['msg']) ? $conn->real_escape_string($_POST['msg']) : '';

$sql = "SELECT description FROM chat_data WHERE title = ? OR short_description = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userMessage, $userMessage);
$stmt->execute();

if ($stmt->error) {
    die("Error in SQL query: " . $stmt->error);
}
$result = $stmt->get_result();

$messages = array();

while ($row = $result->fetch_assoc()) {
    $messages[] = $row['description'];
    $messages[] = "Please click here <a href='https://join.skype.com/tnP3accFexk3' target='_blank'>Contact Us</a>  if you need to take to team support for solve that issue";

}

if (empty($messages)) {
    $messages[] = "Thank you for using our service, but we don't have a solution for that issue.Please contact to team support for take about that issue click <a href='https://join.skype.com/tnP3accFexk3' target='_blank'>Contact Us</a>";
}

$stmt->close();
$conn->close();

error_log("User Message: " . $userMessage);
error_log("Response: " . implode("<br>", $messages));

echo implode("<br>", $messages);
?>