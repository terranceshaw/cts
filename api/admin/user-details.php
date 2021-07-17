<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // GET DETAILS FOR A USER ACCOUNT
*/
require_once("../core.php");

global $connection;
if ($connection) {
    // If we're connected, go for it.
    $userID = sanitize($_POST['user_id']);
    $query = "SELECT * FROM user_accounts WHERE id=$userID";
    if ($result = mysqli_query($connection, $query)) {
        if ($row = mysqli_fetch_assoc($result)) {
            unset($row['password']);
            success($row);
        } else {
            error("Error: " . mysqli_error($connection));
        }
    } else {
        error("Error: " . mysqli_error($connection));
    }
} else {
    error("Not connected to the database.");
}

?>