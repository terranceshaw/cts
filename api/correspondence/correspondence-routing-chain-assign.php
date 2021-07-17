<?php
require_once("../core.php");

// Get a listing of all existing organizations
global $connection;
if ($connection) {
    // Only if we're connected to the database should we attempt anything
    $correspondenceID = $_POST['correspondence-id'];
    $routingChain = $_POST['routing-chain'];
    if (!empty($correspondenceID) && !empty($routingChain)) {
        // And only if we've got the required fields.
        $query = "UPDATE cts_correspondence_types
                  SET routing_chain='$routingChain'
                  WHERE id=$correspondenceID";
        if ($result = mysqli_query($connection, $query)) {
            success("Routing chain successfully updated.");
        }
    }
} else {
    error(mysqli_error($connection));
}

?>