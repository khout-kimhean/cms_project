<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatbotv2";


// Create connection
$queryUpdate = "UPDATE user_move SET number = 1 WHERE number = 0";
$resultUpdate = mysqli_query($conn, $queryUpdate);

// Select rows where number is 0 after the update
$querySelect = "SELECT * FROM user_move WHERE number = 0";
$resultSelect = mysqli_query($conn, $querySelect);
$count = mysqli_num_rows($resultSelect);

$queryUpdate = "UPDATE user_new SET number = 1 WHERE number = 0";
$resultUpdate = mysqli_query($conn, $queryUpdate);

// Select rows where number is 0 after the update
$querySelect = "SELECT * FROM user_new WHERE number = 0";
$resultSelect = mysqli_query($conn, $querySelect);
$count = mysqli_num_rows($resultSelect);

// Close the connection
$conn->close();

?>