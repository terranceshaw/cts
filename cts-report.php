<?php require_once("api/core.php") ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./js/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="../css/fontawesome/css/all.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <title>CTS Report - CTS ID <?php echo $_GET['cts-id'] ?></title>
</head>
<body style="background: white; color: black">

    <div class="padded">
        <?php
            $data = curl(CORRESPONDENCE_DETAILS, array("cts_id"=>$_GET['cts-id']));
            $cData = $data['message']['correspondence'];
            $hData = $data['message']['history'];
            echo "<h1 class=\"page-title\" style=\"font-weight: bolder\">CTS Report (CTS ID: " . $cData['id'] . ")</h1>\n";
            echo "<h3 style=\"margin-top: -10px\">" . $cData['subject'] . "</h3>";

            pre_array($data);
        ?>
    </div>

</body>
</html>