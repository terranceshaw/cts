<?php
require_once("../core.php");

// Get a listing of all existing correspondence routing groups.
global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    $query = "SELECT * FROM cts_routing_groups ORDER BY name ASC";
    if ($result = mysqli_query($connection, $query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        if (@$rows) {
            success($rows);
        } else {
            error("No routing groups have been created.");
        }
    }
} else {
    echo "<option>" . mysqli_error($connection) . "</option>";
}

?>