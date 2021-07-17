<?php
require_once("../core.php");

// Get a listing of all existing organizations
global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    $commandID = COMMAND_ID !== null ? COMMAND_ID : $_POST['command_id'];
    if (!empty($commandID) && !empty($_POST['type_name'])) {
        // And even then, only return content if a valid command_id and type_id was provided.
        sanitize($_POST);
        $typeName = mysqli_real_escape_string($connection, $_POST['type_name']);
        $query = "INSERT INTO cts_correspondence_types (command_id, name) VALUES ($commandID, '$typeName')";
        if ($result = mysqli_query($connection, $query)) {
            // Type was created successfully, fetch the new full list.
            $query = "SELECT * FROM cts_correspondence_types WHERE command_id=$commandID ORDER BY name ASC";
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
            error("Error creating new type: " . mysqli_error($connection));
        }
    } else {
        error("Error: No command_id specified ($commandID, $typeName)");
    }
} else {
    echo "<option>" . mysqli_error($connection) . "</option>";
}

?>