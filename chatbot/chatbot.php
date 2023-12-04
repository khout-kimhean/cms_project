<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userMessage = $conn->real_escape_string($_POST['msg']);

$sql = "SELECT answer_bot FROM tb_chat WHERE question_bot = ? OR error = ?"; // You can use OR condition here
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userMessage, $userMessage);
$stmt->execute();

if ($stmt->error) {
    die("Error in SQL query: " . $stmt->error);
}
$result = $stmt->get_result();

$messages = array();

while ($row = $result->fetch_assoc()) {
    $messages[] = $row['answer_bot'];
}

if (empty($messages)) {
    $messages[] = "We don't have an answer for that question.You can go to check in search or view data.";
}

$stmt->close();
$conn->close();

error_log("User Message: " . $userMessage);
error_log("Response: " . implode("<br>", $messages)); // Log messages with line breaks

echo implode("<br>", $messages); // Output messages with line breaks
?>
