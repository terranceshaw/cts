<?php

// Set password (and hash)

function hashPassword($password) {
    $hash = hash("sha256", date('moodyHMs'));   // Generate a somewhat random salt...
    $hashedPW = hash("sha256", $hash . $password);  // ...then use that salt to hash the password...
    $dbPass = $hash . $hashedPW;    // ...and then append that to the beginning of the entry we'll store in the database.
    return $dbPass;
}

// Verify password and hash

function login($username, $password) {
    global $connection;
    if ($result = DB::run("SELECT password FROM user_accounts WHERE username=?", [$username])->fetch()) {
        $row = $result['password']; // Grab the entry from the database.
        $hash = substr($row, 0, 64);    // Separate the hash from the password...
        $dbPass = substr($row, 64, 128);    // ...And the password from the hash.
        $check = hash("sha256", $hash . $password); //  Using the hash from the DB, hash the password provided via POST.
        if ($check == $dbPass) {
            // Passed authorization check; get the rest of the information.
            if ($result = DB::run("SELECT * FROM user_accounts WHERE username=?", [$username])->fetch()) {  // We've got a result
                unset($result['password']);    // Clear the password from the returned rows.
                // Now get the roles of this account and append that to the list of returned items.
                $userID = $result['id'];
                $roleID = $result['role_id'];
                if ($result = DB::run("SELECT * FROM user_account_roles WHERE id=?", [$roleID])->fetch()) {
                    unset($result['id']);
                    unset($result['command_id']);
                    unset($result['name']);
                    $row['permissions'] = $result;
                    $row['permissions']['username'] = $row['username'];
                    $row['permissions']['user_id'] = $row['id'];
                    $row['permissions']['group_id'] = $row['group_id'];
                    $row['permissions']['command_id'] = $row['command_id'];
                    $row['permissions']['department_id'] = $row['department_id'];
                    // Check whether account is disabled, locked, or whether or not it has CTS access.

                    if ($row['is_locked'] == 1) {
                        // User's account locked; can't let them log in.
                        return "Your account has been locked. Contact an administrator.";
                    }

                    if ($row['permissions']['has_cts_access'] == 0) {
                        // User isn't authorized for CTS access
                        return "This account is not authorized for CTS access.";
                    }

                    DB::run("UPDATE user_accounts SET last_login=GETDATE() WHERE username=?", [$username]);
                    return $row;
                }
            }
        } else {
            return "Invalid username or password.";
        }
    } else {
        return "Error: " . mysqli_error($connection);
    }
}

?>