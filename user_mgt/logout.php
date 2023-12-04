<?php

$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'demo';

session_start();
session_unset();
session_destroy();

header('location:login.php');

?>