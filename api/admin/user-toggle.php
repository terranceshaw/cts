<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    TOGGLE SIMPLE SETTINGS (LOCKED/ENABLED) FOR AN ACCOUNT
    Required variables: user_id, setting, value
*/
require_once("../core.php");

if ($connection) {
    // We're connected; proceed
    $userID = sanitize($_POST['user_id']);
    $setting = sanitize($_POST['setting']);
    $value = sanitize($_POST['value']);
    $query = "UPDATE user_accounts SET is_$setting=$value WHERE id=$userID";
    if (mysqli_query($connection, $query)) {
        success("Account updated.");
    } else {
        error("Unable to update account: " . mysqli_error($connection));
    }
} else {
    error("Unable to complete: Not connected to the database.");
}

?>