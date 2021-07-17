<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // UPDATE AN EXISTING ROLE
*/
require_once("../core.php");

global $connection;
if ($connection) {
    // If we're connected, go for it.
    $commandID = COMMAND_ID !== NULL ? COMMAND_ID : sanitize($_POST['command_id']);
    if ($roleID = sanitize($_POST['role_id'])) {
        $isAdmin = !empty($_POST['is_admin']) ? $_POST['is_admin'] : 0;
        $commandAccess = !empty($_POST['has_command_access']) ? $_POST['has_command_access'] : 0;
        $isDeveloper = !empty($_POST['is_developer']) ? $_POST['is_developer'] : 0;
        $query = "UPDATE user_account_roles
                    SET is_admin=$isAdmin, has_command_access=$commandAccess, is_developer=$isDeveloper
                  WHERE id=$roleID";
        if ($result = mysqli_query($connection, $query)) {
            success("Role updated successfully.");
        } else {
            error("Error updating role: " . mysqli_error($connection));
        }
    } else {
        error("No valid role_id provided.");
    }
} else {
    error("Not connected to the database.");
}

?>