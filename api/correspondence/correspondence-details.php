<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    *** CORRESPONDENCE DETAIL FUNCTIONALITY ***
    Last update: 25DEC20 (THS)
*/
require_once("../core.php");

// Fetch a list of all correspondence, provided we have a valid command_id.
global $connection;
$_POST = sanitize($_POST);  // Sanitize the post variables.
$ctsID = $_POST['cts_id'];
$query = "SELECT cts.id, cts.date_entered, cts.status, cts.date_due, cts.subject, cts.completed_chops, depts.name AS dept_name, types.routing_chain, types.name AS type_name
        FROM cts_correspondence cts 
        INNER JOIN departments depts
            ON cts.origin_department_id = depts.id
        INNER JOIN cts_correspondence_types types
            ON cts.type_id = types.id
        WHERE cts.id=$ctsID";
if ($result = mysqli_query($connection, $query)) {
    if ($row = mysqli_fetch_assoc($result)) {
        $dateEntered = $row['date_entered'] = date('m/d/y', strtotime($row['date_entered']));
        $dateDue = $row['date_due'] = !empty($row['date_due']) ? date('m/d/y', strtotime($row['date_due'])) : null;
        $isOverdue = $row['is_overdue'] = !empty($row['date_due']) && (strtotime($row['date_due']) < strtotime($row['date_entered'])) ? true : false;
        $routingChain = $row['routing_chain'] = unserialize($row['routing_chain']);
        $row['completed_chops'] = unserialize($row['completed_chops']);
        foreach ($routingChain as $key) {   // Fetch each routing group in the chain...
            $query = "SELECT id, name FROM cts_routing_groups WHERE id=$key";
            if ($result = mysqli_query($connection, $query)) {
                while ($name = mysqli_fetch_assoc($result)) {
                    $names[] = $name;
                }
            }
        }
        foreach ($row['routing_chain'] as &$stop) {
            foreach ($names as $name) {
                if ($name['id'] == $stop) {
                    $current = $stop;
                    $stop = null;
                    $stop['id'] = $name['id'];
                    $stop['name'] = $name['name'];
                }
            }
        }
        $data['correspondence'] = $row;

        // Fetch an updated history of the item in the event the user took action on it.
        $query = "SELECT user.display_name, history.action_taken, history.date_received, history.date_processed, history.remarks FROM cts_correspondence_history history
                INNER JOIN user_accounts user
                    ON user.id = history.user_id
                WHERE history.correspondence_id=$ctsID";
        if ($result = mysqli_query($connection, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row['date_received'] = date('m/d/y', strtotime($row['date_received']));
                $row['date_processed'] = $row['date_processed'] ==! null ? date('m/d/y', strtotime($row['date_processed'])) : null;
                $data['history'][] = $row;
            }
            success($data);
        } else {
            error(mysqli_error($connection));
        }
    }
} else {
    error("Error: " . mysqli_error($connection));
}

?>