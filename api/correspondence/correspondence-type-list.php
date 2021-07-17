<?php
require_once("../core.php");

global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    if (!empty($_POST['command_id'])) {
        // And even then, only return content if a valid command_id was provided.
        $commandID = $_POST['command_id'];
        $query = "SELECT * FROM cts_correspondence_types WHERE command_id=$commandID AND routing_chain <> '' ORDER BY name ASC";
        if ($result = mysqli_query($connection, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            if (@$rows) {
                success($rows);
            } else {
                error("No cts_correspondence_types have been created for the specified command_id.");
            }
        }
    } else {
        error("Error: No command_id specified.");
    }
} else {
    echo "<option>" . mysqli_error($connection) . "</option>";
}

?>