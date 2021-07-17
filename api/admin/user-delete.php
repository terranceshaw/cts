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
    if ($userID == 1) {
        error("ITC Shaw is the creator. He cannot be deleted.");
        exit();
    }

    // First, make sure the username is unique in case they're trying to change it.
    $query = "DELETE FROM user_accounts WHERE id=$userID";
    if ($result = mysqli_query($connection, $query)) {
        // Delete successful.
        success("User deleted.");
    } else {
        error("Error deleting user: " . mysqli_error($connection));
    }
} else {
    error("No data received.");
}

?>