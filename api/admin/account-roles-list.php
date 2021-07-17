<?php
require_once("../core.php");

// Get a listing of all existing organizations
global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    $commandID = COMMAND_ID !== null ? COMMAND_ID : $_POST['command_id'];
    if (!empty($commandID)) {
        // ...And only if a valid command_id has been provided.
        $devFilter = IS_DEVELOPER ? "AND visible=1" : null;
        $query = "SELECT * FROM user_account_roles WHERE command_id=$commandID $devFilter ORDER BY name ASC";
        if ($result = mysqli_query($connection, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            if (@$rows) {
                success($rows);
            } else {
                error("No account roles have been created for the specified command_id");
            }
        }
    } else {
        error("Error: No command_id provided.");
    }
} else {
    echo "<option>" . mysqli_error($connection) . "</option>";
}

?>