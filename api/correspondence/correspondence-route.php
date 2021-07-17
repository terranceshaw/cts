<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    *** CORRESPONDENCE ROUTE FUNCTIONALITY ***
    Specifically: this is used to take action on correspondence given inputs from the provided form.
    Last update: 25DEC20 (THS)
*/
require_once("../core.php");

// Fetch a list of all correspondence, provided we have a valid command_id.
global $connection;
$_POST = sanitize($_POST);  // Sanitize the post variables.
$ctsID = $_POST['cts_id'];
$userID = $_POST['user_id'];
$groupID = $_POST['group_id'];
$action = $_POST['action'];
$remarks = $_POST['remarks'];
if ($connection) {
    // If we're connected to the database, proceed.
    $query = "INSERT INTO cts_correspondence_history (correspondence_id, user_id, group_id, action_taken, remarks)
                VALUES ($ctsID, $userID, $groupID, '$action', '$remarks')";
    if (mysqli_query($connection, $query)) {
        // We've successfully added the historical item, now update the correspondence object itself.
        $query = "SELECT cts.completed_chops, types.routing_chain
                  FROM cts_correspondence cts
                  LEFT JOIN cts_correspondence_types types ON
                    cts.type_id = types.id
                  WHERE cts.id=$ctsID";
        if ($result = mysqli_query($connection, $query)) {
            $row = mysqli_fetch_assoc($result);
            $row['completed_chops'] = unserialize($row['completed_chops']); // Unserialize the array...
            $row['completed_chops'][] = $groupID;   // And then append the newest reviewer to the stack...
            $completedChops = serialize($row['completed_chops']);   // ...Then reserialize it...
            $routingChain = unserialize($row['routing_chain']);
            $isApproved = $action == "Approved" ? ", date_closed=now(), status=\"Closed\"" : null;
            $query = "UPDATE cts_correspondence
                        SET completed_chops='$completedChops'
                        $isApproved
                      WHERE id=$ctsID";
            if (mysqli_query($connection, $query)) {
                success("Successfully routed correspondence.");
            } else {
                error("Error routing correspondence: " . mysqli_error($connection));
            }
        } else {
            error("Error routing correspondence: " . mysqli_error($connection));
        }
    } else {
        error("Error routing correspondence: " . mysqli_error($connection));
    }
}

?>