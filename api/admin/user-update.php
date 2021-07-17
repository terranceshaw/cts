<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // UPDATE USER SETTINGS (SELF-SERVICE)
*/
require_once("../core.php");

// If we have POST vars to work with...
if (!empty($_POST)) {
    $_POST = sanitize($_POST);
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? hashPassword($_POST['password']) : null;
    $displayName = !empty($_POST['display-name']) ? $_POST['display-name'] : $username; // If they delete their display name, replace it with username.
    $emailAddress = $_POST['email-address'];
    $notifications = isset($_POST['notifications']) ? 1 : 0;
    $updateDate = mySQLDate(date('m/d/y h:m:s'));

    if ($connection) {
        $updatePW = ($password != null) ? ", password='$password'": null;
        $query = "UPDATE user_accounts
                    SET display_name='$displayName', 
                        email_address='$emailAddress',
                        get_notifications=$notifications,
                        date_modified='$updateDate'$updatePW
                    WHERE username='$username'";
        if ($result = mysqli_query($connection, $query)) {
            // Changes were saved; retrieve new values from database and update session variables.
            $query = "SELECT * FROM user_accounts WHERE username='$username'";
            if ($result = mysqli_query($connection, $query)) {  // We've got a result
                if ($row = mysqli_fetch_assoc($result)) {
                    unset($row['password']);    // Clear the password from the returned rows.
                    // Now get the roles of this account and append that to the list of returned items.
                    $roleID = $row['role_id'];
                    $query = "SELECT * FROM user_account_roles WHERE id=$roleID";
                    if ($result = mysqli_query($connection, $query)) {
                        if ($roleRow = mysqli_fetch_assoc($result)) {
                            unset($roleRow['id']);
                            unset($roleRow['command_id']);
                            unset($roleRow['name']);
                            $row['permissions'] = $roleRow;
                            $row['permissions']['username'] = $row['username'];
                            $row['permissions']['command_id'] = $row['command_id'];
                            $row['permissions']['department_id'] = $row['department_id'];
                            session_start();
                            foreach ($row as $sessKey => $sessVar) {
                                $_SESSION['nemesys'][$sessKey] = $sessVar;
                            }
                            success($row['display_name']);
                        }
                    }
                } else {
                    error("Error: " . mysqli_error($connection));
                }
            } else {
                error("Error: " . mysqli_error($connection));
            }
        } else {
            error("Error updating user account: " . mysqli_error($connection));
        }
    } else {
        error("Error connecting to database: " . mysqli_error($connection));
    }
} else {
    error("No data received.");
}

?>