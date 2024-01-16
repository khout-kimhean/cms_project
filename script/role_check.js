document.addEventListener("DOMContentLoaded", function () {
    var userRole = "<?php echo $_SESSION['user_role']; ?>";

    // Define the roles that are allowed to access each option
    var allowedRoles = {
        'dashboard.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
        'file_mgt.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team'],
        'chat.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
        'chatgpt.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team', 'user'],
        'datachat.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team'],
        'read_file.php': ['admin', 'card payment team', 'digital branch team', 'user'],
        'notification.php': ['admin', 'card payment team'],
        'assessment.php': ['admin', 'card payment team'],
        'showuser.php': ['admin'],
        'testchat.php': ['admin'],
        'user_management.php': ['admin'],

        'permission.php': ['admin'],
        'assign_function.php': ['admin'],

        // store file
        'upload_file.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
        'view_file.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
        'view.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
        'view_data.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
        'edit_data.php': ['admin', 'card payment team', 'digital branch team', 'atm team', 'terminal team',],
        'report.php': ['admin'],

        // <!-- assessment -->
        'upload_new.php': ['admin', 'card payment team'],
        'upload_move.php': ['admin', 'card payment team'],
        'upload_resign.php': ['admin', 'card payment team'],
        'assessment_user.php': ['admin', 'card payment team'],


        'search_resign.php': ['admin', 'card payment team'],
        'search_move.php': ['admin', 'card payment team'],
        'user_resign.php': ['admin', 'card payment team'],
        'move_user.php': ['admin', 'card payment team'],
        'newuser_assessment.php': ['admin', 'card payment team'],
        'assessment_list.php': ['admin', 'card payment team'],

        // <!-- user management -->
        'user.php': ['admin'],
        'createuser.php': ['admin'],
        'edit_user.php': ['admin'],


        // <!-- find error -->
        'read_error_inlog.php': ['admin', 'card payment team', 'digital branch team', 'user'],
        'read_by_keyword.php': ['admin', 'card payment team', 'digital branch team', 'user'],
        'read_trxno.php': ['admin', 'card payment team', 'digital branch team', 'user'],
        'list_new_user.php': ['admin', 'card payment team'],
        'edit_user_new.php': ['admin', 'card payment team'],
        'edit_user_move.php': ['admin', 'card payment team'],


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
    // function disableForRestricted(role, option) {
    //     var allowed = allowedRoles[option] && allowedRoles[option].includes(role);
    //     if (!allowed) {
    //         var elements = document.getElementsByClassName("disable-for-restricted");
    //         for (var i = 0; i < elements.length; i++) {
    //             elements[i].addEventListener("click", function (event) {
    //                 event.preventDefault();
    //                 // alert("You do not have permission to access this option.");
    //             });
    //             elements[i].style.pointerEvents = "none";
    //             elements[i].style.opacity = "0.6";
    //             elements[i].style.cursor = "not-allowed";
    //             elements[i].style.transition = "opacity 0.3s, cursor 0.3s";

    //             // Add hover effect to show a block cursor
    //             elements[i].addEventListener("mouseenter", function () {
    //                 this.style.cursor = "not-allowed";
    //             });

    //             elements[i].addEventListener("mouseleave", function () {
    //                 this.style.cursor = "initial";
    //             });
    //         }
    //     }
    // }


    // Call the function for each option
    disableForRestricted(userRole, 'dashboard.php');
    disableForRestricted(userRole, 'user_management.php');
    disableForRestricted(userRole, 'assessment.php');
    disableForRestricted(userRole, 'read_file.php');
    disableForRestricted(userRole, 'chat.php');
    disableForRestricted(userRole, 'datachat.php');
    disableForRestricted(userRole, 'showuser.php');
    disableForRestricted(userRole, 'user.php');
    disableForRestricted(userRole, 'createuser.php');
    disableForRestricted(userRole, 'permission.php');
    disableForRestricted(userRole, 'assign_function.php');
    disableForRestricted(userRole, 'view.php');
    disableForRestricted(userRole, 'view_data.php');
    disableForRestricted(userRole, 'edit_data.php');
    disableForRestricted(userRole, 'search_resign.php');
    disableForRestricted(userRole, 'search_move.php');
    disableForRestricted(userRole, 'user_resign.php');
    disableForRestricted(userRole, 'move_user.php');
    disableForRestricted(userRole, 'newuser_assessment.php');
    disableForRestricted(userRole, 'read_error_inlog.php');
    disableForRestricted(userRole, 'read_by_keyword.php');
    disableForRestricted(userRole, 'edit_user.php');
    disableForRestricted(userRole, 'assessment_list.php');
    disableForRestricted(userRole, 'file_mgt.php');
    disableForRestricted(userRole, 'upload_file.php');
    disableForRestricted(userRole, 'report.php');
    disableForRestricted(userRole, 'view_file.php');
    disableForRestricted(userRole, 'upload_new.php');
    disableForRestricted(userRole, 'upload_move.php');
    disableForRestricted(userRole, 'upload_resign.php');
    disableForRestricted(userRole, 'assessment_user.php');
    disableForRestricted(userRole, 'notification.php');
    disableForRestricted(userRole, 'chatgpt.php');
    disableForRestricted(userRole, 'testchat.php');

    // Add other options as needed
});