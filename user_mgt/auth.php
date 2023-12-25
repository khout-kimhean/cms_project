<?php
session_start();
function hasPermission($user_role, $allowed_roles)
{
    return in_array($user_role, $allowed_roles);
}

if (isset($_SESSION['user_role'])) {
    $user_role = $_SESSION['user_role'];

    $redirect_url = '../dashboard/dashboard.php';

    switch ($user_role) {
        case 'admin':
            $redirect_url = '../dashboard/dashboard.php';
            break;
        case 'card payment team':
            if (hasPermission($user_role, ['card payment team'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        case 'digital branch team':
            if (hasPermission($user_role, ['digital branch team'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        case 'atm team':
            if (hasPermission($user_role, ['atm team'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        case 'terminal team':
            if (hasPermission($user_role, ['terminal team'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        case 'user':
            if (hasPermission($user_role, ['user'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        default:
            $redirect_url = 'error.php';
            break;
    }
    header("Location: $redirect_url");
    exit();
}
header('Location: login.php');
exit();
?>