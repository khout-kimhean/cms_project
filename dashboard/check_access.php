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
    // 'user_management.php' => ['admin'],
    'assessment.php' => ['admin', 'card payment team'],
    'todo_management.php' => ['admin', 'card payment team'],
    'read_file.php' => ['admin', 'card payment team'],
    'chat.html' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
    'data_mgt.pgp' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
    'datachat.php' => ['admin'],
    'showuser.php' => ['admin'],
    'user.php' => ['admin'],
    'createuser.php' => ['admin'],
    'search_user.php' => ['admin'],
    'assign_function.php' => ['admin'],
    'data_mgt.php' => ['admin'],
    'data_store.php' => ['admin'],
    'view_1.php' => ['admin'],
    'view_data.php' => ['admin'],
    'edit_data.php' => ['admin'],
    'upload_user.php' => ['admin'],
    'search_resign.php' => ['admin'],
    'search_move.php' => ['admin'],
    'user_resign.php' => ['admin'],
    'move_user.php' => ['admin'],
    'newuser_assessment.php' => ['admin'],
    'chat.php' => ['admin'],
    'read_error_inlog.php' => ['admin'],
    'read_by_keyword.php' => ['admin'],
    'edit_user.php' => ['admin'],
    'summary.php' => ['admin'],
    'assessment_list.php' => ['admin'],
    'file_mgt.php' => ['admin'],
    'upload_file.php' => ['admin'],
    'report.php' => ['admin'],
    'view_file.php' => ['admin'],
];

// if (isset($allowed_roles[$current_page]) && !in_array($user_role, $allowed_roles[$current_page])) {
//     redirect('error.php');
// }

if (!in_array($user_role, $allowed_roles[$current_page])) {
    // Generate JavaScript to disable clicks on blocked pages
    echo <<<HTML
        <script src="path/to/your/script.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var overlay = document.createElement("Content");
                overlay.style = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; pointer-events: auto; display: flex; align-items: center; justify-content: center;";
                var message = document.createElement("p");
                message.style = "color: #fff; font-size: 18px;";
                message.textContent = "You do not have permission to access this page.";
                overlay.appendChild(message);
                document.body.appendChild(overlay);

                // Disable clicks on the entire body
                document.body.style.pointerEvents = 'none';
            });
        </script>
HTML;
}
?>