<?php
require_once("../core.php");

// Get a listing of all existing organizations
global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    if (!empty($_POST['command_id'])) {
        // And even then, only return content if a valid command_id was provided.
        $commandID = !empty(COMMAND_ID) ? COMMAND_ID : $_POST['command_id'];
        $deptID = $_POST['department_id'];
        $deptFilter = $_POST['has_command_access'] == 0 ? " AND id=$deptID " : null;    // If they don't have command-wide access, they shouldn't be able to originate anyone else's stuff.
        $query = "SELECT * FROM departments WHERE command_id=$commandID $deptFilter ORDER BY name ASC";
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
        error("Error: No command_id specified.");
    }
} else {
    echo "<option>" . mysqli_error($connection) . "</option>";
}

?>