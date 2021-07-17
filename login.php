<?php require_once "api/core.php";
@session_start();

if (!empty($_POST)) {
    sanitize($_POST);
    if ($result = login($_POST['username'], $_POST['password'])) {
        if (is_array($result)) {
            foreach ($result as $sessKey => $sessVar) {
                $_SESSION['nemesys'][$sessKey] = $sessVar;
            }
        } else {
            $status = "<span style=\"text-align: center; color: crimson;\">$result</span>";
        }
    }
}

if (isset($_SESSION['nemesys']['username'])) {
    header("Location: cts.php");
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./js/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="../../css/fontawesome/css/all.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <title>CVN 77 Correspondence Tracking System - Login</title>
</head>
<body style="align-content: center; justify-content: center">

    <form action="" method="post" id="login-form" class="col" style="max-width: 250px; margin: 0 auto">
        <div id="logo-img"></div>
        <h2 style="text-align: center; opacity: .6; margin-top: -10px; margin-bottom: 10px">Login</h3>
        <label for="username">Username</label>
        <input type="text" name="username" id="username">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <?php
            /* echo $status;
            if (!$connection) {
                // If the database is offline, let the user know.
                echo "<span style=\"text-align: center; color: crimson;\">Database offline; please call ADP at 6256.</span>";
            } */
        ?>
        <div class="row" style="margin-top: 20px">
            <input type="submit" value="Login" id="login-btn" class="confirm-btn">
            <input type="reset" value="Reset" id="reset-btn" class="cancel-btn">
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#username").focus()  // Once the page is loaded, immediately give the username textfield focus. It's the li'l bits of polish users appreciate without really noticing it.
        });
    </script>

</body>
</html>