<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // CREATE A NEW ROUTING GROUP
*/
require_once("../core.php");

if ($connection) {
    // If we're connected, proceed.
    $groupName = $_POST['group_name'];
    $commandID = !empty(COMMAND_ID) ? COMMAND_ID : $_POST['command_id'];
    // Check to make sure there aren't other groups with the same name.
    $query = "SELECT name FROM cts_routing_groups WHERE (name='$groupName' AND command_id=$commandID)";
    if ($result = mysqli_query($connection, $query)) {
        if (count(mysqli_fetch_assoc($result)) == 1) {
            // Already a group with this name for this command.
            error("Routing group '$groupName' already exists.");
        } else {
            $query = "INSERT INTO cts_routing_groups (command_id, name) VALUES ($commandID, '$groupName')";
            if (mysqli_query($connection, $query)) {
                // Success; get the new list of routing groups.
                $query = "SELECT * FROM cts_routing_groups WHERE command_id=$commandID ORDER BY name ASC";
                if ($result = mysqli_query($connection, $query)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $rows[] = $row;
                    }
                    success($rows);
                } else {
                    error("Error: " . mysqli_error($connection));
                }
            } else {
                // Error; report it.
                error("Error: " . mysqli_error($connection));
            }
        }
    } else {
        error("Error: " . mysqli_error($connection));
    }
} else {
    // Otherwise let the user know.
    error("Database offline.");
}

?>