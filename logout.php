<?php session_start(); session_unset(); session_destroy() ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./js/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="./css/fontawesome/css/all.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <title>CVN 77 Correspondence Tracking System - Logged Out</title>
</head>
<body>

<div class="col" style="height: calc(100vh - 200px); text-align: center; justify-content: center">
    <i class="fas fa-thumbs-up" style="font-size: 6rem; opacity: .8; margin-bottom: 10px"></i>
    <h1>Logged you out.</h1>
    <a href="login.php" class="link-btn floaty-btn" style="width: 200px; margin: 0 auto; margin-top: 20px;"><i class="fas fa-arrow-circle-left btn-glyph"></i> Return to login.</a>
    <a href="http://web" class="link-btn floaty-btn" style="width: 200px; margin: 0 auto; margin-top: 20px;"><i class="fas fa-arrow-circle-left btn-glyph"></i> CVN 77 Intranet</a>
</div>

</body>
</html>