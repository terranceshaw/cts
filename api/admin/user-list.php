<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // GET ALL THEM EXISTING USERS.
*/
require_once("../core.php");

if ($commandID = $_POST['command_id']) {
    // Get a list of all existing user accounts.
    if (!empty($_POST['filter'])) {
        // Amend the filter for sorting.
    }
    $query = "SELECT users.id AS user_id, users.username, users.is_enabled, users.is_locked, roles.name AS role_name, groups.name AS group_name, departments.name AS dept_name
                FROM user_accounts users
                LEFT JOIN departments ON
                departments.id = users.department_id
                LEFT JOIN user_account_roles roles ON
                roles.id = users.role_id
                LEFT JOIN cts_routing_groups groups ON
                groups.id = users.group_id
                WHERE users.command_id=$commandID
                    AND roles.has_cts_access=1
                ORDER BY users.id ASC";
    if ($result = mysqli_query($connection, $query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['group_name'] = $row['group_name'] == "" ? "Read Only" : $row['group_name'];
            $userList[] = $row;
        }
        if (@$userList) {
            success($userList);
        } else {
            error("No users configured.");
        }
    } else {
        error("Error: " . mysqli_error($connection));
    }
}

?>