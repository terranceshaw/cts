<?php
/*
    CTS 2.0
    Better than whatever the original was.
    Developed for CVN 77 by ITC Terrance H Shaw
    terrance.shaw@gmail.com / thedude@terranceshaw.com
    NOTE: You can remove this header if you like, but if you do... support is your problem.

    // CREATE A NEW USER ACCOUNT BROH.
*/
require_once("../core.php");

// If we have POST vars to work with...
$_POST = sanitize($_POST);
$commandID = $_POST['command_id'];
$departmentID = $_POST['department-id'];
$roleID = $_POST['role-id'];
$groupID = $_POST['group-id'];
$displayName = $_POST['display-name'];
$username = $_POST['username'];
$password = empty($_POST['password']) ? hashPassword("P@ssw0rd!@#$%^&") : hashPassword($_POST['password']); // Default password if one wasn't provided.
$emailAddress = $_POST['email-address'];

if (!empty($username) && !empty($password)) {
    // Just need the username and password, since all other required fields are submitted automagically.
    $displayName = !empty($displayName) ? $displayName : $username; // Make sure we gots a display name one way or another.
    // Before we create the account, verify the username is unique.
    $query = "SELECT username FROM user_accounts WHERE username='$username'";
    if ($result = mysqli_query($connection, $query)) {
        if (count(mysqli_fetch_row($result)) > 0) {
            // User exists, error out.
            unset($_POST['password']);
            error("An account already exists for $username.");
        } else {
            // Unique username; continue...
            $query = "INSERT INTO user_accounts (command_id, department_id, role_id, group_id, display_name, username, password, email_address) VALUES ($commandID, $departmentID, $roleID, $groupID, '$displayName', '$username', '$password', '$emailAddress')";
            if ($result = mysqli_query($connection, $query)) {
                // Account created.
                success("Account created successfully for $username.");
            } else {
                // Ruh-roh, Raggy.
                error("Error creating account ($query): " . mysqli_error($connection));
            }
        }
    }
} else {
    error("Username and password are both required fields.");
}

?>