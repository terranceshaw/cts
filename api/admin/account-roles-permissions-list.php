<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    GET PERMISSION LISTING FOR SPECIFIED ACCOUNT ROLE
    Required variables: role_id
*/
require_once("../core.php");

// Get a listing of all permissions for the specified role_id.

global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    $roleID = !empty($_POST['role_id']) ? $_POST['role_id'] : null;
    if (!empty($roleID)) {
        // ...And only if a valid command_id has been provided.
        $query = "SELECT * FROM user_account_roles WHERE id=$roleID";
        if ($result = mysqli_query($connection, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                unset($row['id']);
                unset($row['name']);
                unset($row['command_id']);
                unset($row['visible']);
                $rows[] = $row;
            }
            success($rows);
        }
    } else {
        error("Error: No type_id provided.");
    }
} else {
    error("Error: " . mysqli_error($connection));
}

?>