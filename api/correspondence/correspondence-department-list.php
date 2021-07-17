<?php
require_once("../core.php");

// Get a listing of all existing departments.
global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    if (!empty($_POST['command_id'])) {
        // ...And only if a valid command_id was provided.
        $commandID = $_POST['command_id'];
        $query = "SELECT * FROM cts_responsible_departments WHERE command_id=$commandID ORDER BY name ASC";
        if ($result = mysqli_query($connection, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            if (@$rows) {
                success($rows);
            } else {
                error("No departments have been created for the specified command_id.");
            }
        }
    } else {
        error("No command_id was provided.");
    }
} else {
    echo "<option>" . mysqli_error($connection) . "</option>";
}

?>