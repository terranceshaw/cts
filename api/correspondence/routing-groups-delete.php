<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // DELETE SELECTED ROUTING GROUP
*/
require_once("../core.php");

if ($connection) {
    // If we're connected, proceed.
    $commandID = !empty(COMMAND_ID) ? COMMAND_ID : $_POST['command_id'];
    $groupID = $routingGroups = sanitize($_POST['group_id']);
    $routingGroups = (is_array($routingGroups)) ? "id IN (" . implode(",", $routingGroups) . ")" : "id=" . $routingGroups;    // Convert to string if multiple were selected.
    $query = "DELETE FROM cts_routing_groups WHERE $routingGroups";
    if (mysqli_query($connection, $query)) {
        // Successfully deleted; query the new bit.
        $query = "UPDATE user_accounts SET group_id=0
                  WHERE group_id=$groupID";
        mysqli_query($connection, $query);  // Reassign anyone that was in that group to read-only access (0)
        $query = "SELECT * FROM cts_routing_groups WHERE command_id=$commandID ORDER BY name ASC";
        if ($result = mysqli_query($connection, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            if ($rows) {
                success($rows);
            } else {
                error("No rows for some reason.");
            }
        } else {
            error("Unable to delete: " . mysqli_error($connection));
        }
    } else {
        // Error, report it.
        error("Unable to delete: " . mysqli_error($connection));
    }
} else {
    // Otherwise let the user know.
    error("Database offline.");
}

?>