<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // DELETE AN EXISTING ROLE
*/
require_once("../core.php");

global $connection;
if ($connection) {
    // If we're connected, go for it.
    $commandID = COMMAND_ID !== NULL ? COMMAND_ID : sanitize($_POST['command_id']);
    if ($roleID = sanitize($_POST['role_id'])) {
        // First, make sure that it's not a default role (those cannot be deleted)
        $query = "SELECT is_default FROM user_account_roles WHERE id=$roleID";
        if ($result = mysqli_query($connection, $query)) {
            if (mysqli_fetch_assoc($result)['is_default'] == 1) {
                // Is default; alert the user.
                error("Cannot delete default roles.");
            } else {
                // Is not default, proceed with massive damage.
                $query = "DELETE roles, users
                          FROM user_account_roles roles
                            LEFT JOIN user_accounts users ON users.role_id = roles.id
                          WHERE roles.id=$roleID";
                if ($result = mysqli_query($connection, $query)) {
                    // Action was good, query the database for the new roles list.
                    $query = "SELECT * FROM user_account_roles WHERE command_id=$commandID ORDER BY name ASC";
                    if ($result = mysqli_query($connection, $query)) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $rows[] = $row;
                        }
                        success($rows);
                    } else {
                        error("Error creating a new role: " . mysqli_error($connection));
                    }
                } else {
                    error("Error creating a new role: " . mysqli_error($connection));
                }
            }
        }
    } else {
        error("No role_id provided for deletion.");
    }

} else {
    error("Not connected to the database.");
}

?>