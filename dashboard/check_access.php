<?php
session_start();

function redirect($location)
{
    header("Location: $location");
    exit();
}

if (!isset($_SESSION['user_role'])) {
    redirect('login.php');
}

$user_role = $_SESSION['user_role'];
$current_page = basename($_SERVER['PHP_SELF']);

$allowed_roles = [
    'dashboard.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
    'user_management.php' => ['admin'],
    'assessment.php' => ['admin', 'card payment team'],
    'todo_management.php' => ['admin', 'card payment team'],
    'read_file.php' => ['admin', 'card payment team'],
    'chat.html' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
    'data_mgt.pgp' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
    'datachat.php' => ['admin'],
    'showuser.php' => ['admin'],
];

if (isset($allowed_roles[$current_page]) && !in_array($user_role, $allowed_roles[$current_page])) {
    redirect('error.php');
}

if (!in_array($user_role, $allowed_roles[$current_page])) {
    // Generate JavaScript to disable clicks on blocked pages
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var overlay = document.createElement("div");
            overlay.style = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; pointer-events: auto; display: flex; align-items: center; justify-content: center;";
            var message = document.createElement("p");
            message.style = "color: #fff; font-size: 18px;";
            message.textContent = "You do not have permission to access this page.";
            overlay.appendChild(message);
            document.body.appendChild(overlay);
        });
    </script>';
}
?>