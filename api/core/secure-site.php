<?php
include_once dirname(__FILE__) . "/db.php";
session_start();

// This script will go through and add users as they visit the page if they don't already have an entry in the database.
$username = explode("\\", $_SERVER['AUTH_USER'])[1];
if (DB::run("SELECT COUNT(*) FROM user_accounts WHERE username LIKE ?", [$username])->fetchColumn() == 0) {
    DB::run("INSERT INTO user_accounts (username, email_address) VALUES (?, ?)", [$username, $username . "@cvn77.navy.mil"]);
    $_SESSION['username'] = $username;
} else {
    $_SESSION['username'] = $username;
    $_SESSION['id'] = DB::run("SELECT id FROM user_accounts WHERE username LIKE ?", [$username])->fetchColumn();
    $_SESSION['is_admin'] = DB::run("SELECT is_admin FROM user_accounts WHERE username LIKE ?", [$username])->fetchColumn();
    $_SESSION['hd_supervisor'] = DB::run("SELECT hd_supervisor FROM user_accounts WHERE username LIKE ?", [$username])->fetchColumn();
    $_SESSION['hd_technician'] = DB::run("SELECT hd_technician FROM user_accounts WHERE username LIKE ?", [$username])->fetchColumn();
    $_SESSION['cantor_mode'] = DB::run("SELECT cantor_mode FROM user_accounts WHERE username LIKE ?", [$username])->fetchColumn();
}

?>