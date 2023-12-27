document.addEventListener("DOMContentLoaded", function () {
    var userRole = "<?php echo $_SESSION['user_role']; ?>";

    // Define the roles that are allowed to access each option
    var allowedRoles = {
        'dashboard.php': ['admin', 'card payment team', 'digital branch team', 'atm team',
            'terminal team'
        ],
        'user_management.php': ['admin'],
        'assessment.php': ['admin', 'card payment team'],

        'read_file.php': ['admin', 'card payment team'],
        'chat.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',

        ],
        'data_mgt.php': ['admin', 'card payment team', 'digital branch team', 'atm team',
            'terminal team'
        ],
        'datachat.php': ['admin', 'user'],
        'showuser.php': ['admin'],
        'user.php': ['admin'],
        'createuser.php': ['admin'],
        'search_user.php': ['admin'],
        'assign_function.php': ['admin'],
        'data_store.php': ['admin'],
        'view_1.php': ['admin'],
        'view_data.php': ['admin'],
        'edit_data.php': ['admin'],
        'upload_user.php': ['admin'],
        'search_resign.php': ['admin'],
        'search_move.php': ['admin'],
        'user_resign.php': ['admin'],
        'move_user.php': ['admin'],
        'newuser_assessment.php': ['admin'],
        'read_error_inlog.php': ['admin'],
        'read_by_keyword.php': ['admin'],
        'edit_user.php': ['admin'],
        'summary.php': ['admin'],
        'assessment_list.php': ['admin'],
        'file_mgt.php': ['admin'],
        'upload_file.php': ['admin'],
        'report.php': ['admin'],
        'view_file.php': ['admin'],
        'upload_new.php': ['admin'],
        'upload_move.php': ['admin'],
        'upload_resign.php': ['admin'],
        'assessment_user.php': ['admin'],
        'asscess_new_user.php': ['admin'],
        'notification.php': ['admin'],
        'chatgpt.php': ['user'],
        'assign_function.php': ['admin']
        // Add other options and roles as needed
    };

    // Function to disable elements for restricted roles
    function disableForRestricted(role, option) {
        var allowed = allowedRoles[option] && allowedRoles[option].includes(role);
        if (!allowed) {
            var elements = document.getElementsByClassName("disable-for-restricted");
            for (var i = 0; i < elements.length; i++) {
                elements[i].addEventListener("click", function (event) {
                    event.preventDefault();
                    // alert("You do not have permission to access this option.");
                });
                elements[i].style.pointerEvents = "none";
                elements[i].style.opacity = "0.6";
            }
        }
    }

    // Call the function for each option
    disableForRestricted(userRole, 'dashboard.php');
    disableForRestricted(userRole, 'user_management.php');
    disableForRestricted(userRole, 'assessment.php');
    disableForRestricted(userRole, 'todo_management.php');
    disableForRestricted(userRole, 'read_file.php');
    disableForRestricted(userRole, 'chat.php');
    disableForRestricted(userRole, 'data_mgt.php');
    disableForRestricted(userRole, 'datachat.php');
    disableForRestricted(userRole, 'showuser.php');
    disableForRestricted(userRole, 'user.php');
    disableForRestricted(userRole, 'createuser.php');
    disableForRestricted(userRole, 'search_user.php');
    disableForRestricted(userRole, 'assign_function.php');
    disableForRestricted(userRole, 'data_store.php');
    disableForRestricted(userRole, 'view_1.php');
    disableForRestricted(userRole, 'view_data.php');
    disableForRestricted(userRole, 'edit_data.php');
    disableForRestricted(userRole, 'upload_user.php');
    disableForRestricted(userRole, 'search_resign.php');
    disableForRestricted(userRole, 'search_move.php');
    disableForRestricted(userRole, 'user_resign.php');
    disableForRestricted(userRole, 'move_user.php');
    disableForRestricted(userRole, 'newuser_assessment.php');
    disableForRestricted(userRole, 'read_error_inlog.php');
    disableForRestricted(userRole, 'read_by_keyword.php');
    disableForRestricted(userRole, 'edit_user.php');
    disableForRestricted(userRole, 'summary.php');
    disableForRestricted(userRole, 'assessment_list.php');
    disableForRestricted(userRole, 'file_mgt.php');
    disableForRestricted(userRole, 'upload_file.php');
    disableForRestricted(userRole, 'report.php');
    disableForRestricted(userRole, 'view_file.php');
    disableForRestricted(userRole, 'upload_new.php');
    disableForRestricted(userRole, 'upload_move.php');
    disableForRestricted(userRole, 'upload_resign.php');
    disableForRestricted(userRole, 'assessment_user.php');
    disableForRestricted(userRole, 'asscess_new_user.php');
    disableForRestricted(userRole, 'notification.php');
    disableForRestricted(userRole, 'chatgpt.php');
    disableForRestricted(userRole, 'assign_function.php');

    // Add other options as needed
});