<?php
require_once("../core.php");

global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    $typeID = $_POST['type_id'];
    $commandID = COMMAND_ID !== null ? COMMAND_ID : $_POST['command_id'];
    if (!empty($_POST['type_id'])) {
        // And even then, only return content if a valid type_id was provided.
        sanitize($_POST);
        $query = "DELETE types, correspondence
                  FROM cts_correspondence_types types
                    JOIN cts_correspondence correspondence ON correspondence.type_id = types.id
                  WHERE types.id=$typeID";
        if ($result = mysqli_query($connection, $query)) {
            // Type was deleted successfully, provide the refreshed list.
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
            } else {
                error("Error deleting type: " . mysqli_error($connection));
            }
        } else {
            error("Error deleting type: " . mysqli_error($connection));
        }
    } else {
        error("Error: No type_id specified.");
    }
} else {
    echo "<option>" . mysqli_error($connection) . "</option>";
}

?>