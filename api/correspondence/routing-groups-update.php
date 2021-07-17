<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    https://terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // UPDATE SELECTED ROUTING GROUP
    Required variables: command_id, group_id, group_name
*/
require_once("../core.php");

if ($connection) {
    // If we're connected, proceed.
    $commandID = !empty(COMMAND_ID) ? COMMAND_ID : $_POST['command_id'];
    $groupID = sanitize($_POST['group_id']);
    $groupName = sanitize($_POST['group_name']);
    if ($groupID && $groupName) {
        // Make sure the new name is unique.
        $query = "SELECT name FROM cts_routing_groups WHERE (name='$groupName' AND command_id=$commandID)";
        $result = mysqli_query($connection, $query);
        if (count(mysqli_fetch_assoc($result)) > 0) {
            // Not unique, let them know they're not creative.
            error("A routing group already exists with this name.");
        } else {
            $query = "UPDATE cts_routing_groups
                      SET name='$groupName'
                      WHERE id=$groupID";
            if ($result = mysqli_query($connection, $query)) {
                // Update was good, fetch the updated list.
                $query = "SELECT * FROM cts_routing_groups WHERE command_id=$commandID ORDER BY name ASC";
                if ($result = mysqli_query($connection, $query)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $rows[] = $row;
                    }
                    success($rows);
                } else {
                    error("There was a problem renaming this group: " . mysqli_error($connection));
                }
            } else {
                error("There was a problem renaming this group: " . mysqli_error($connection));
            }
        }
    } else {
        error("Missing either a group_id or group_name.");
    }
} else {
    // Otherwise let the user know.
    error("Database offline.");
}

?>