<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    *** CORRESPONDENCE LIST FUNCTIONALITY ***
    Last update: 22DEC20 (THS)
*/
require_once("../core.php");

// Fetch a list of all correspondence, provided we have a valid command_id.
global $connection;
$_POST = sanitize($_POST);  // Sanitize the post variables.
$commandID = !empty(COMMAND_ID) ? COMMAND_ID : $_POST['command_id'];
$deptID = !empty(DEPARTMENT_ID) ? DEPARTMENT_ID : $_POST['department_id'];
if ($commandID) {
    // If they don't have command-wide access, limit visibility to their own correspondence.
    $cwFilter = (PERMISSIONS['has_command_access'] == 0) ? "AND (correspondence.origin_department_id=$deptID)" : null ;

    // Apply any other search filters that may have been passed.
    if (!empty($_POST['search'])) {
        $searchFilter = $_POST['filter'];
        $searchValue = $_POST['value'];
        if (($searchValue !== "") || $searchValue !== 0) {
            if ($searchFilter == "subject") {
                $searchQuery = "AND (correspondence.subject LIKE '%{$searchValue}%')";
            } else if ($searchFilter == "cts-id") {
                $searchQuery = "AND (correspondence.id=$searchValue)";
            } else if ($searchFilter == "origin-dept") {
                $searchQuery = "AND (correspondence.origin_department_id=$deptID)";
            }
        }
    }

    // First we need to figure out if this user is pending in any correspondence for the command.
    $correspondenceQueue = null;
    $query = "SELECT id, completed_chops, remaining_chops FROM cts_correspondence
                WHERE command_id=$commandID AND date_closed IS NULL";
    if ($result = mysqli_query($connection, $query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $remainingChops = unserialize($row['remaining_chops']);
            $completedChops = unserialize($row['completed_chops']);
            if (in_array($_POST['group_id'], $remainingChops) || in_array($_POST['group_id'], $completedChops)) {
                $correspondenceQueue[] = $row['id'];
            }
        }
    } else {
        error("Error: " . mysqli_error($connection));
    }

    // Filters and such.
    $correspondenceQueue = implode(",", $correspondenceQueue);
    $groupFilter = !empty($correspondenceQueue) ? "OR (correspondence.id IN ($correspondenceQueue))" : null;
    $closedFilter = (isset($_POST['show-closed']) && $_POST['show-closed'] == 1) ? null : "AND (correspondence.date_closed IS NULL)";

    $query = "SELECT correspondence.id, correspondence.subject, correspondence.status, correspondence.date_entered, correspondence.date_closed, 
                correspondence.is_private, depts.name AS dept_name, types.name AS type_name
              FROM cts_correspondence correspondence
              INNER JOIN departments depts ON
                depts.id = correspondence.origin_department_id
              INNER JOIN cts_correspondence_types types ON
                types.id = correspondence.type_id
                WHERE (correspondence.command_id=$commandID)
                $closedFilter
                $cwFilter
                $groupFilter
                $searchQuery
              ORDER BY correspondence.id ASC";
    if ($result = mysqli_query($connection, $query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['date_entered'] = date('m/d/y', strtotime($row['date_entered']));
            $rows[] = $row;
        }
        if (@$rows) {
            success($rows);
        } else {
            $searchQuery !== null ? error("No correspondence for search query.") : error("No correspondence.");
        }
    } else {
        error("Error ($query): " . mysqli_error($connection));
    }
} else {
    error("No correspondence.");
}

?>