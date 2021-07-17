<?php
require_once("../core.php");

// Get a listing of all existing organizations
global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    $query = "SELECT * FROM commands ORDER BY name ASC";
    if ($result = mysqli_query($connection, $query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        if (@$rows) {
            success($rows);
        } else {
            error("No commands have been created.");
        }
    }
} else {
    echo "<option>" . mysqli_error($connection) . "</option>";
}

?>