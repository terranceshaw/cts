<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // GET ALL THEM EXISTING ROUTING GROUPS.
*/
require_once("../core.php");

if ($connection) {
    // If we're connected, proceed.
    $commandID = COMMAND_ID !== NULL ? COMMAND_ID : $_POST['command_id'];
    $query = "SELECT * FROM cts_routing_groups WHERE command_id=$commandID ORDER BY name ASC";
    if ($result = mysqli_query($connection, $query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        if ($rows) {
            success($rows);
        } else {
            error("Error fetching routing group list: " . mysqli_error($connection));
        }
    } else {
        // error("Error fetching groups (" . COMMAND_ID . "): " . mysqli_error($connection));
        error(PERMISSIONS);
    }
} else {
    // Otherwise let the user know.
    error("Database offline.");
}

?>