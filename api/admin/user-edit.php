<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // UPDATE A USER ACCOUNT (ADMIN)
*/
require_once("../core.php");

// If we have POST vars to work with...
if (!empty($_POST)) {
    // Get our sanitized variables...
    $userID = sanitize($_POST['user_id']);
    $username = sanitize($_POST['username']);
    $displayName = !empty($_POST['display_name']) ? sanitize($_POST['display_name']) : $username;
    $emailAddress = sanitize($_POST['email_address']);
    $departmentID = sanitize($_POST['department_id']);
    $roleID = sanitize($_POST['role_id']);
    $groupID = sanitize($_POST['group_id']);

    // First, make sure the username is unique in case they're trying to change it.
    $query = "SELECT id FROM user_accounts WHERE (username='$username' AND id != $userID)";
    $result = mysqli_query($connection, $query);
    if (count(mysqli_fetch_assoc($result)) > 0) {
        // Not unique; fail out.
        error("An account already exists with username '$username'.");
    } else {
        // Unique username; proceed with update.
        $query = "UPDATE user_accounts
                  SET username='$username', display_name='$displayName', email_address='$emailAddress', department_id=$departmentID, role_id=$roleID, group_id=$groupID, date_modified=NOW()
                  WHERE id=$userID";
        if ($result = mysqli_query($connection, $query)) {
            success("The user account '$username' was successfully updated.");
        } else {
            error("Error updating account: " . mysqli_error($connection));
        }
    }
} else {
    error("No data received.");
}

?>