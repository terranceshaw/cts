<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    *** NEW CORRESPONDENCE FUNCTIONALITY ***
    Last update: 22DEC20 (THS)
*/
require_once("../core.php");
session_start();

// Fetch a list of all correspondence, provided we have a valid command_id.
global $connection;

if ($connection) {
    // If we're connected, proceed.
    // Split the received POST vars between user data (uData) and correspondence data (cData);
    $uData = $_POST['user_data'];
    $cData = $_POST['correspondence_data'];
    $commandID = $uData['command_id'];
    $userID = $uData['user_id'];
    $groupID = $uData['group_id'];
    $isPrivate = !empty($cData['private-correspondence']) ? 1 : 0;
    $typeID = $cData['correspondence-type'];
    $departmentID = $cData['originator-department'];
    $returnDate = $cData['return-date'] !== null ? mysqlDate($cData['return-date']) : null;    // And modify the return-date POST var for SQL friendliness.
    $priority = $cData['priority'];
    $subject = sanitize($cData['subject']);
    $originatorName = sanitize($cData['originator-name']);
    $requesteeRank = sanitize($cData['requestee-rank']);
    $requesteeName = sanitize($cData['requestee-name']);
    $requesteeContact = sanitize($cData['requestee-contact']);
    $remarks = sanitize($cData['remarks']);

    // Get the routing chain of the specified type...
    $query = "SELECT routing_chain
              FROM cts_correspondence_types
              WHERE id=$typeID";
    if ($result = mysqli_query($connection, $query)) {
        if ($row = mysqli_fetch_assoc($result)) {
            $routingChain = unserialize($row['routing_chain']);
            $currentReviewer = count($routingChain) > 0 ? $routingChain[0] : null;
            $completedChain = null;
            for ($i = 0; $i < count($routingChain); $i++) {
                if ($routingChain[$i] == $uData['group_id']) {
                    $completedChops[] = $routingChain[$i];
                    unset($routingChain[$i]);   // If the originator is part of the routing chain, no need to include them.
                }
            }
            $remainingChops = !empty($routingChain) ? serialize($routingChain) : null;
            $completedChops = !empty($completedChops) ? serialize($completedChops) : null;

            // // Then insert the data into the database.
            $query = "INSERT INTO cts_correspondence (command_id, type_id, origin_department_id, completed_chops, remaining_chops, priority, subject, originator_name, requestee_rank, requestee_name, requestee_contact, is_private, date_due) 
                      VALUES ($commandID, $typeID, $departmentID, '$completedChops', '$remainingChops', $priority, '$subject', '$originatorName', '$requesteeRank', '$requesteeName', '$requesteeContact', $isPrivate, '$returnDate')";
            if ($result = mysqli_query($connection, $query)) {
                // Insert was successful; create new historical data for the item.
                $ctsID = mysqli_insert_id($connection); // Grab generated CTS ID
                $query = "INSERT INTO cts_correspondence_history (correspondence_id, user_id, group_id, remarks, action_taken, date_processed) 
                          VALUES ($ctsID, $userID, $groupID, '$remarks', 'Originated', NOW())";
                if ($result = mysqli_query($connection, $query)) {
                    success($ctsID);    // If we're good, return the CTS ID.
                } else {
                    error("Error creating new correspondence ($query): " . mysqli_error($connection));
                }
            } else {
                error("Error creating new correspondence: " . mysqli_error($connection));
            }
        } else {
            error("Error creating new correspondence: " . mysqli_error($connection));
        }
    } else {
        error("Error creating new correspondence ($query): " . mysqli_error($connection));
    }
} else {
    error("Unable to create new correspondence: database offline.");
}

?>