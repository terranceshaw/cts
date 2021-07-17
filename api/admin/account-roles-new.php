<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // CREATE A NEW ROLE.
*/
require_once("../core.php");

global $connection;
if ($connection) {
    // If we're connected, go for it.
    $commandID = COMMAND_ID !== NULL ? COMMAND_ID : sanitize($_POST['command_id']);
    $roleName = sanitize($_POST['role_name']);
    // First, make sure a role doesn't already exist with that name.
    $query = "SELECT id FROM user_account_roles WHERE name='$roleName'";
    if ($result = mysqli_query($connection, $query)) {
        if (count(mysqli_fetch_assoc($result)) == 1) {
            error("Role '$roleName' already exists.");
        } else {
            // Role doesn't exist, create the new one!
            $query = "INSERT INTO user_account_roles (command_id, name) VALUES ($commandID, '$roleName')";
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
    error("Not connected to the database.");
}

?>