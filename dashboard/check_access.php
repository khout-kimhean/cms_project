<?php
session_start();

function hasPermission($userRole, $allowedRoles)
{
    return is_array($allowedRoles) && in_array($userRole, $allowedRoles);
}

if (!isset($_SESSION['user_role'])) {
    header("Location: ../user_mgt/login.php");
    exit;
}

$userRole = $_SESSION['user_role'];
$currentPage = basename($_SERVER['PHP_SELF']);

$allowed_roles = [
   // <!-- dashboard -->
   'dashboard.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
   'file_mgt.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team'],
   'chat.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
   'datachat.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team'],
   'read_file.php' => ['admin', 'card payment team', 'digital branch team', 'user'],
   'notification.php' => ['admin', 'card payment team'],
   'assessment.php' => ['admin', 'card payment team'],
   'showuser.php' => ['admin'],
   'chatgpt.php' => ['admin'],
   'testchat.php' => ['admin'],
   'user_management.php' => ['admin'],


   'permission.php' => ['admin'],
   'assign_function.php' => ['admin'],
   // <!-- store file -->
   // 'upload_file.php' => ['admin'],
   'upload_file.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
   'view_file.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
   'view.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
   'view_data.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
   'edit_data.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
   'report.php' => ['admin'],

   // <!-- assessment -->
   'upload_new.php' => ['admin', 'card payment team'],
   'upload_move.php' => ['admin', 'card payment team'],
   'upload_resign.php' => ['admin', 'card payment team'],
   'assessment_user.php' => ['admin', 'card payment team'],


   'search_resign.php' => ['admin', 'card payment team'],
   'search_move.php' => ['admin', 'card payment team'],
   'user_resign.php' => ['admin', 'card payment team'],
   'move_user.php' => ['admin', 'card payment team'],
   'newuser_assessment.php' => ['admin', 'card payment team'],
   'assessment_list.php' => ['admin', 'card payment team'],

   // <!-- user management -->
   'user.php' => ['admin'],
   'createuser.php' => ['admin'],
   'edit_user.php' => ['admin'],


   // <!-- find error -->
   'read_error_inlog.php' => ['admin', 'card payment team', 'digital branch team', 'user'],
   'read_by_keyword.php' => ['admin', 'card payment team', 'digital branch team', 'user'],

];

if (!hasPermission($userRole, $allowed_roles[$currentPage])) {
    header("Location: ../user_mgt/auth.php");
    exit;
}

?>