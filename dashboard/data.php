<?php

include '../connect/conectdb.php';

$count1 = 0;
$count2 = 0;

// Create connection
$queryUpdate1 = "UPDATE user_move SET number = 1 WHERE number = 0";
$resultUpdate = mysqli_query($conn, $queryUpdate1);

// Select rows where number is 0 after the update
$querySelect1 = "SELECT * FROM user_move WHERE number = 0";
$resultSelect1 = mysqli_query($conn, $querySelect1);
$count1 = mysqli_num_rows($resultSelect1);

$queryUpdate = "UPDATE user_new SET number = 1 WHERE number = 0";
$resultUpdate = mysqli_query($conn, $queryUpdate);

// Select rows where number is 0 after the update
$querySelect = "SELECT * FROM user_new WHERE number = 0";
$resultSelect = mysqli_query($conn, $querySelect);
$count2 = mysqli_num_rows($resultSelect);

// Close the connection
$conn->close();

?>