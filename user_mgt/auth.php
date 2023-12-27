<?php
session_start();
function hasPermission($user_role, $allowed_roles)
{
    return in_array($user_role, $allowed_roles);
}

if (isset($_SESSION['user_role'])) {
    $user_role = $_SESSION['user_role'];

    $allowed_roles = [
        'admin' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
        'card payment team' => ['card payment team'],
        'digital branch team' => ['digital branch team'],
        'atm team' => ['atm team'],
        'terminal team' => ['terminal team'],
        'user' => ['user'],
    ];

    $redirect_url = isset($allowed_roles[$user_role]) ? '../dashboard/dashboard.php' : 'error.php';

    header("Location: $redirect_url");
    exit();
}

header('Location: login.php');
exit();
?>