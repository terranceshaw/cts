<?php
// Session helpers.
// session_save_path("C:\php\tmp");
@session_start();
date_default_timezone_set("America/New_York");  // Make sure we use the right timezone for accounting purposes.
@$session = $_SESSION['nemesys']; // Slightly quicker means of accessing session vars.
@define("PERMISSIONS", $session['permissions']);
@define("IS_ADMIN", (PERMISSIONS['is_admin'] == 1) || (PERMISSIONS['is_global_admin'] == 1) || (PERMISSIONS['is_developer'] == 1) ? true : false);
@define("IS_DEVELOPER", PERMISSIONS['is_developer'] == 1 ? true : false);
@define("LOGGED_IN_USER", $session['username']);
@define("LOGGED_IN_DISPLAY_NAME", $session['display_name']);
@define("COMMAND_ID", $session['command_id']);
@define("DEPARTMENT_ID", $session['department_id']);

// Define some dealies.
$status; // A message we can use here and there; hither and thither.

// Mando requires.
require_once "db.php"; // Database info and actual connection
require_once "shared-functions.php";   // Function library to keep things organized
require_once("modules/user-functions.php");

// Some URL definitions for ease of use with our cURL requests
// Define the base URL to be used (potential placeholder for better solution in the future).
// Had to move to an if...then because CVN77 reasons.
if ($_SERVER['SERVER_NAME'] == "web.cvn77.navy.mil") {
    define("BASE_URL", "http://web.cvn77.navy.mil/apps/cts/");
} else if ($_SERVER['SERVER_NAME'] == "web") {
    define("BASE_URL", "http://web/apps/cts/");
} else {
    define("BASE_URL", "http://localhost/cvn77/intranet/apps/cts/");
}

// Developer-related URLs
define("API_TEST", BASE_URL . "api/dev/api-test.php");  // Test various dealies and doodads pertaining to the API.

// Command-related URLs
define("COMMAND_LIST", BASE_URL . "api/admin/command-list.php");  // Get a list of existing commands.
define("DEPARTMENT_LIST", BASE_URL . "api/admin/department-list.php");  // Get a list of existing departments for a given command_id.

// User-related URLs
define("USER_LIST", BASE_URL . "api/admin/user-list.php");  // Get a list of existing users.
define("USER_DETAILS", BASE_URL . "api/admin/user-details.php");  // Get user details for a provided user_id.
define("USER_NEW", BASE_URL . "api/admin/user-new.php");  // Create a new user.
define("USER_UPDATE", BASE_URL . "api/admin/user-update.php");  // Update an existing user (self-service).
define("USER_EDIT", BASE_URL . "api/admin/user-edit.php");  // Update an existing user (admin).
define("USER_DELETE", BASE_URL . "api/admin/user-delete.php");  // Delete an existing user (admin).
define("USER_TOGGLE", BASE_URL . "api/admin/user-toggle.php");  // Quick access to simple user toggles.

// Roles-related URLs
define("ACCOUNT_ROLES_LIST", BASE_URL . "api/admin/account-roles-list.php");  // Get a list of existing roles.
define("ACCOUNT_ROLES_NEW", BASE_URL . "api/admin/account-roles-new.php");  // Create a new role with provided role_name.
define("ACCOUNT_ROLES_DELETE", BASE_URL . "api/admin/account-roles-delete.php");  // Create a new role with provided role_id.
define("ACCOUNT_ROLES_UPDATE", BASE_URL . "api/admin/account-roles-update.php");  // Update role with provided role_id.
define("ACCOUNT_ROLES_PERMISSIONS_LIST", BASE_URL . "api/admin/account-roles-permissions-list.php");  // Get a list of permission settings for provided type_id.

// Correspondence-related URLs
define("CORRESPONDENCE_NEW", BASE_URL . "api/correspondence/correspondence-new.php");  // Create a new correspondence object with provided data.
define("CORRESPONDENCE_LIST", BASE_URL . "api/correspondence/correspondence-list.php");  // Get a list of existing correspondence for a given command_id.
define("CORRESPONDENCE_DETAILS", BASE_URL . "api/correspondence/correspondence-details.php");  // Get details for a specific correspondence item, provided a given cts_id.
define("CORRESPONDENCE_TYPE_DELETE", BASE_URL . "api/correspondence/correspondence-type-delete.php");  // Delete the correspondence type of provided type_id.
define("CORRESPONDENCE_TYPE_NEW", BASE_URL . "api/correspondence/correspondence-type-new.php");  // Create a new correspondence type.
define("CORRESPONDENCE_ROUTE", BASE_URL . "api/correspondence/correspondence-route.php");  // Take selected actions on the provided cts_id.
define("CORRESPONDENCE_ROUTE_CHAIN_LIST", BASE_URL . "api/correspondence/correspondence-routing-chain-list.php");  // Get list of correspondence routing chain list, given a provided type_id.
define("CORRESPONDENCE_TYPES_LIST", BASE_URL . "api/correspondence/correspondence-type-list.php");  // Get a list of existing correspondence_types for a given command_id.
define("CORRESPONDENCE_GROUPS_LIST", BASE_URL . "api/correspondence/correspondence-group-list.php");  // Get a list of existing correspondence_types for a given command_id.
define("CORRESPONDENCE_PRIORITY_LIST", BASE_URL . "api/correspondence/correspondence-priority-list.php");  // Get a list of existing correspondence priorities for a given command_id.
define("CORRESPONDENCE_DEPARTMENT_LIST", BASE_URL . "api/correspondence/correspondence-department-list.php");  // Get a list of existing correspondence_departments for a given command_id.
define("ROUTING_GROUPS_LIST", BASE_URL . "api/correspondence/routing-groups-list.php");  // Get a list of all routing groups with provided command_id.
define("ROUTING_GROUPS_NEW", BASE_URL . "api/correspondence/routing-groups-new.php");  // Create a new routing group with provided group_name and command_id.
define("ROUTING_GROUPS_UPDATE", BASE_URL . "api/correspondence/routing-groups-update.php");  // Update the specified routing group.
define("ROUTING_GROUPS_DELETE", BASE_URL . "api/correspondence/routing-groups-delete.php");  // Delete specified routing group.

// Connect to the database and fetch roles.
$roles = null;
if ($rows = DB::run("SELECT * FROM user_account_roles")->fetchAll()) {
    foreach ($rows as $row) {
        $roles[] = $row;
    }
    if (empty($roles)) {
        $roles = "No roles, bruh.";
    }
}
// if ($connection) {
//     $query = "SELECT * FROM account_roles";
//     if ($result = mysqli_query($connection, $query)) {
//         while ($row = mysqli_fetch_assoc($result)) {
//             $roles[] = $row;
//         }
//         if (empty($roles)) {
//             $roles = "No roles, bruh.";
//         }
//     }
// }

?>