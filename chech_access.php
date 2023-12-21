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
    'chat.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
    'data_mgt.php' => ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
    'datachat.php' => ['admin'],
    'showuser.php' => ['admin'],
    'user.php' => ['admin'],
    'createuser.php' => ['admin'],
    'search_user.php' => ['admin'],
    'assign_function.php' => ['admin'],
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

// if (!in_array($user_role, $allowed_roles[$current_page])) {
//     // Generate JavaScript to disable clicks on blocked pages
//     echo <<<HTML
//         <script>
//             document.addEventListener("DOMContentLoaded", function() {
//                 // Create overlay
//                 var overlay = document.createElement("div");
//                 overlay.style.cssText = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: flex; align-items: center; justify-content: center; pointer-events: none;";

//                 // Create message element
//                 var message = document.createElement("p");
//                 message.style.cssText = "color: rgb(192, 12, 16); font-size: 18px; pointer-events: auto;";
//                 // message.textContent = "You do not have permission to access this page.";

//                 // Append message to overlay
//                 overlay.appendChild(message);

//                 // Append overlay to body
//                 document.body.appendChild(overlay);
//             });
//         </script>
// HTML;
// }



if (!in_array($user_role, $allowed_roles[$current_page])) {
    // Generate JavaScript to disable clicks on blocked pages
    echo <<<HTML
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Check if the user role is not admin
                if ("$user_role" !== "admin") {
                    // Create overlay
                    var overlay = document.createElement("div");
                    overlay.style.cssText = "position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;";

                    // Create message element
                    var message = document.createElement("p");
                    message.style.cssText = "color: rgb(192, 12, 16); font-size: 18px;";

                    // Append message to overlay
                    overlay.appendChild(message);

                    // Append overlay to body
                    document.body.appendChild(overlay);

                    // Disable clicks on the message element
                    message.addEventListener("click", function(event) {
                        event.stopPropagation();
                    });
                }
            });
        </script>
HTML;
}
?>