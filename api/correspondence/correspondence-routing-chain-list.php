<?php
require_once("../core.php");

// Get a listing of all existing routing chains.
global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    if (!empty($_POST['type-id'])) {
        // ...And only if a valid type_id was provided.
        sanitize($_POST);
        $commandID = PERMISSIONS['command_id'];
        $typeID = $_POST['type-id'];
        $query = "SELECT routing_chain FROM cts_correspondence_types WHERE id=$typeID";
        if ($result = mysqli_query($connection, $query)) {
            if ($row = mysqli_fetch_row($result)) {
                $row = $group = array_reverse(unserialize($row[0]));
                $row = count($row) > 0 ? implode(",", $row) : null;
                foreach ($group as $type) {
                    // Using the 'id IN ()' approach gets the rows we need, but in the wrong order. This bypasses it, but I feel like it's not the most efficient approach since you query the database once for each type.
                    // That being said, I'm stymied with how else to proceed, so... hurr we go.
                    $query = "SELECT * FROM cts_routing_groups WHERE id=$type";
                    if ($result = mysqli_query($connection, $query)) {
                        $rows['selected-groups'][] = mysqli_fetch_assoc($result);
                    }
                }
                $filter = $row !== null ? "WHERE id NOT IN ($row)" : null;
                $query = "SELECT * FROM cts_routing_groups $filter ORDER BY name ASC";
                if ($newResult = mysqli_query($connection, $query)) {
                    while ($newRow = mysqli_fetch_assoc($newResult)) {
                        $rows['available-groups'][] = $newRow;
                    }
                }
                echo json_encode($rows);
            } else {
                error(mysqli_error($connection));
            }
        } else {
            error(mysqli_error($connection));
        }
    } else {
        error("No type_id was provided.");
    }
} else {
    error(mysqli_error($connection));
}

?>