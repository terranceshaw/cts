<?php require_once("api/core.php") ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./js/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="../../css/fontawesome/css/all.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <title>Correspondence Tracking System - Track Your Correspondence</title>
</head>
<body>

<div class="col" style="height: 100vh; justify-content: space-around">
    <?php
        if (empty($_POST)) {
            // Post is empty, show the form.
            include("pages/forms/cts-tracking-form.php");
        } else {
            // POST is not empty, search for correspondence.
            $ctsID = sanitize($_POST['cts-id']);
            $query = "SELECT * FROM cts_correspondence WHERE id=$ctsID";
            if ($result = mysqli_query($connection, $query)) {
                $row = mysqli_fetch_assoc($result);
                if ($row) {
                    $data = curl(CORRESPONDENCE_DETAILS, array("cts_id"=>$ctsID));
                    if ($data['status'] == 200) {
                        $status = end($data['message']['history'])['action_taken'];
                        $details = $data['message']['correspondence'];
                        $history = $data['message']['history'];
                        ?>
                            <div class="col" style="width: 250px; margin: 0 auto; text-align: center">
                                <div id="logo-img"></div>
                                <h2 style="margin-bottom: 10px;">Status for CTS ID <?php echo $ctsID ?></h2>
                                <ul class="cts-tracker-chain">
                                <?php
                                foreach (array_reverse($details['routing_chain']) as $stop) {
                                    $name = $stop['name'];
                                    $date = $stop['date_processed'] !== null ? date('m/d/y', strtotime($stop['date_processed'])) : null;
                                    if (in_array($stop['id'], $details['completed_chops'])) {
                                        echo "<li class=\"cts-tracker-routing-stop cts-tracker-routing-complete\"><i class=\"fas fa-check-circle\"></i> $name <span class=\"cts-tracker-date\">$date</span></li>";
                                    } else {
                                        echo "<li class=\"cts-tracker-routing-stop\"><i class=\"fas fa-circle\"></i> $name</li>";
                                    }
                                }
                                ?>
                                </ul>
                                <span>This correspondence is <strong><?php echo $status ?></strong>.</span>
                                <a href="cts-tracker.php" class="link-btn floaty-btn" style="margin: 10px auto">Go back</a>
                            </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="col" style="width: 250px; margin: 0 auto; text-align: center">
                        <div id="logo-img"></div>
                        <h2>No results found.</h2>
                        <span>There were no matching results for the provided CTS ID.</span>
                        <a href="cts-tracker.php" class="link-btn floaty-btn" style="margin: 10px auto">Go back</a>
                    </div>
                    <?php
                }
            } else {
                echo "Error: " . mysqli_error($connection);
            }
        }
    ?>

    <a href="index.php" class="link-btn floaty-btn" style="position: absolute; top: 10px; left: 20px"><i class="fas fa-chevron-left link-btn-glyph"></i> CTS Home</a>
</div>

</body>
</html>