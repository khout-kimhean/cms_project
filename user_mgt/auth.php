<?php
session_start();

// Function to check if the user has permission for a specific action
function hasPermission($user_role, $allowed_roles)
{
    return in_array($user_role, $allowed_roles);
}

// Check if the user is already logged in
if (isset($_SESSION['user_role'])) {
    $user_role = $_SESSION['user_role'];

    // Redirect all users to dashboard.php upon login
    $redirect_url = '../dashboard/dashboard.php';

    // Check permissions based on roles
    switch ($user_role) {
        case 'admin':
            // Admin has access to all functions
            $redirect_url = '../dashboard/dashboard.php';
            break;
        case 'card payment team':
            // Card Payment Team has access to User Management
            if (hasPermission($user_role, ['card payment team'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        case 'digital branch team':
            // Digital Branch Team has access to Assessment
            if (hasPermission($user_role, ['digital branch team'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        case 'atm team':
            // ATM Team has access to ToDo Management
            if (hasPermission($user_role, ['atm team'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        case 'terminal team':
            // Terminal Team has access to Find Error
            if (hasPermission($user_role, ['terminal team'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        case 'user':
            // Regular users have access to ChatBot
            if (hasPermission($user_role, ['user'])) {
                $redirect_url = '../dashboard/dashboard.php';
            }
            break;
        default:
            // Handle other roles or redirect to an error page
            $redirect_url = 'error.php';
            break;
    }

    header("Location: $redirect_url");
    exit();
}

// If the user is not logged in, redirect to the login page
header('Location: login.php');
exit();
?>