<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Admin Dashboard/styles/assign_function.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>

    <div class="container">
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="../images/logo/logo.jpg">
                    <h2>FTB <span class="danger">Bank</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        <!-- close -->
                    </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="../dashboard/dashboard.php">
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>

                <a href="../data_store/search.php">
                    <span class="fa fa-search">
                    </span>
                    <h3>Search</h3>
                </a>
                <a href="../contact/contact.php">
                    <span class="fa fa-address-card">
                    </span>
                    <h3>Contact</h3>
                </a>
                <a href="../data_store/upload_file.php">
                    <span class="fa fa-upload">
                    </span>
                    <h3>Data Store</h3>
                </a>

                <a href="../data_store/list_upload.php">
                    <span class="material-icons-sharp">
                        inventory
                    </span>
                    <h3>View File</h3>
                </a>
                <a href="../assessment/assessment.php">
                    <span class="fa fa-address-book">
                        <!-- fab fa-app-store-ios -->
                    </span>
                    <h3>Assessment</h3>
                </a>

                <a href="../user_mgt/user_management.php" class="active">
                    <span class="fa fa-user-circle">
                    </span>
                    <h3>User Mgt</h3>
                </a>
                <a href="../to_do_list/todo_management.php">
                    <span class="fa fa-list-alt">
                    </span>
                    <h3>To-do List</h3>
                </a>
                <a href="../data_store/data_mgt.php">
                    <span class="fa fa-briefcase">
                    </span>
                    <h3>Stock Mgt</h3>
                </a>


                <a href="../user_mgt/logout.php">
                    <span class="material-icons-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <main>
            <div class="container2">
                <div class="back_button">
                    <a href="../templates/user_management.php" class="back-button">
                        <i class="fa fa-chevron-circle-left" style="font-size: 25px">Back</i>
                    </a>
                </div>
                <h2>Add Function to Role/Team User</h2>
                <div class="select_role">
                    <label for="role">Select User Role</label>
                    <select name="user_type">
                        <option value="admin">Admin</option>
                        <option value="card payment team">Card Payment Team</option>
                        <option value="digital branch team">Digital Branch Team</option>
                        <option value="atm team">ATM Team</option>
                        <option value="terminal team">Terminal Team</option>
                        <option value="user">User</option>
                    </select>
                    <label class="label_input" for="role">User Name</label>
                    <input type="text" name="name" required placeholder="enter your name">
                </div>

                <div class="dashboard">
                    <h2>Dashboard</h2>
                    <div class="container3">
                        <input type="checkbox" id="input-1" class="check-input">
                        <label for="input-1" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>User Mgt</span>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-2" class="check-input">
                        <label for="input-2" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>Todo List</span>
                        <div class="list">
                            <div class="container3">
                                <input type="checkbox" id="input-18" class="check-input">
                                <label for="input-18" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Admin</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-19" class="check-input">
                                <label for="input-19" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>User</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-20" class="check-input">
                                <label for="input-20" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Card Payment Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-21" class="check-input">
                                <label for="input-21" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Digital Branch Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-22" class="check-input">
                                <label for="input-22" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>ATM Network Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-23" class="check-input">
                                <label for="input-23" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Terminal Mgt Team</span>
                            </div>
                        </div>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-3" class="check-input">
                        <label for="input-3" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>Stock Mgt</span>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-4" class="check-input">
                        <label for="input-4" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>Chatbot</span>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-5" class="check-input">
                        <label for="input-5" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>Upload Chat</span>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-6" class="check-input">
                        <label for="input-6" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>View</span>
                        <div class="list">
                            <div class="container3">
                                <input type="checkbox" id="input-24" class="check-input">
                                <label for="input-24" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Admin</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-25" class="check-input">
                                <label for="input-25" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>User</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-26" class="check-input">
                                <label for="input-26" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Card Payment Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-27" class="check-input">
                                <label for="input-27" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Digital Branch Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-28" class="check-input">
                                <label for="input-28" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>ATM Network Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-29" class="check-input">
                                <label for="input-29" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Terminal Mgt Team</span>
                            </div>
                        </div>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-7" class="check-input">
                        <label for="input-7" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>User Assessment</span>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-8" class="check-input">
                        <label for="input-8" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>Search</span>
                        <div class="list">
                            <div class="container3">
                                <input type="checkbox" id="input-30" class="check-input">
                                <label for="input-30" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Admin</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-31" class="check-input">
                                <label for="input-31" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>User</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-32" class="check-input">
                                <label for="input-32" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Card Payment Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-33" class="check-input">
                                <label for="input-33" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Digital Branch Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-34" class="check-input">
                                <label for="input-34" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>ATM Network Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-35" class="check-input">
                                <label for="input-35" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Terminal Mgt Team</span>
                            </div>
                        </div>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-9" class="check-input">
                        <label for="input-9" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>Contact</span>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-10" class="check-input">
                        <label for="input-10" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>Upload File</span>
                    </div>
                    <div class="container3">
                        <input type="checkbox" id="input-11" class="check-input">
                        <label for="input-11" class="checkbox">
                            <svg viewBox="0 0 22 16" fill="none">
                                <path d="M1 6.85L8.09677 14L21 1" />
                            </svg>
                        </label>
                        <span>View File</span>
                        <div class="list">
                            <div class="container3">
                                <input type="checkbox" id="input-12" class="check-input">
                                <label for="input-12" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Admin</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-13" class="check-input">
                                <label for="input-13" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>User</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-14" class="check-input">
                                <label for="input-14" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Card Payment Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-15" class="check-input">
                                <label for="input-15" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Digital Branch Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-16" class="check-input">
                                <label for="input-16" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>ATM Network Team</span>
                            </div>
                            <div class="container3">
                                <input type="checkbox" id="input-17" class="check-input">
                                <label for="input-17" class="checkbox">
                                    <svg viewBox="0 0 22 16" fill="none">
                                        <path d="M1 6.85L8.09677 14L21 1" />
                                    </svg>
                                </label>
                                <span>Terminal Mgt Team </span>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="../templates/assign_function.php">
                    <input type="submit" name="submit" value="Assign Function" class="form-btn">
                </form>

            </div>
        </main>
        <div class="right-section">
            <div class="nav">
                <button id="menu-btn">
                    <span class="material-icons-sharp">
                        menu
                    </span>
                </button>
                <div class="dark-mode">
                    <span class="material-icons-sharp active">
                        light_mode
                    </span>
                    <span class="material-icons-sharp">
                        dark_mode
                    </span>
                </div>

                <div class="profile">
                    <div class="info">
                        <p>Welcome</p>
                        <small class="text-muted">Admin</small>
                    </div>
                    <div class="profile-photo">
                        <img src="../images/logo/logo.jpg">
                    </div>
                </div>

            </div>
            <!-- End of Nav -->

            <div class="user-profile">
                <div class="logo">
                    <a href="https://ftb.com.kh/en/">
                        <img src="../images/logo/logo.jpg">
                        <h2>FTB Bank </h2>
                        <p>Welcome to FTB Bank</p>
                    </a>
                </div>
            </div>

            <div class="reminders">
                <div class="header">
                    <h2>Reminders</h2>
                    <span class="material-icons-sharp">
                        notifications_none
                    </span>
                </div>

                <div class="notification">
                    <div class="icon">
                        <span class="material-icons-sharp">
                            volume_up
                        </span>
                    </div>
                    <div class="content">
                        <div class="info">
                            <h3>Support Time</h3>
                            <small class="text_muted">
                                08:00 AM - 5:00 PM
                            </small>
                        </div>
                        <span class="material-icons-sharp">
                            more_vert
                        </span>
                    </div>
                </div>

                <div class="notification deactive">
                    <div class="icon">
                        <span class="material-icons-sharp">
                            edit
                        </span>
                    </div>
                    <div class="content">
                        <div class="info">
                            <h3>Open Time</h3>
                            <small class="text_muted">
                                08:00 AM - 5:00 PM
                            </small>
                        </div>
                        <span class="material-icons-sharp">
                            more_vert
                        </span>
                    </div>
                </div>

                <div class="notification add-reminder">
                    <div>
                        <span class="material-icons-sharp">
                            add
                        </span>
                        <h3>Add Reminder</h3>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- <script src="orders.js"></script> -->
    <script src="../script/index.js"></script>
</body>

</html>