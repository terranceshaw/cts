<div class="col padded">
    <?php
        if (empty($_POST)) {
            include("pages/forms/new-trouble-ticket-form.php");
        } else {
            sanitize($_POST);
            $userID = $session['id'];
            $subject = sanitize($_POST['subject']);
            $text = sanitize($_POST['problem']);
            $query = "INSERT INTO cts_trouble_tickets (user_id, subject, text)
                        VALUES ($userID, '$subject', '$text')";
            if (mysqli_query($connection, $query)) {
            ?>
            <h1 class="page-title">Ticket submitted!</h1>
            <p>Your trouble ticket has been submitted and will be reviewed by the administrators as soon as possible.</p>
            <p>If you need additional assistance in the interim, <a href="?page=trouble-ticket" style="border-bottom: 1px dotted">submit another ticket</a> or <a href="mailto:webmaster@cvn77.navy.mil" style="border-bottom: 1px dotted">contact the webmaster</a>.</p>
            <a href="cts.php" class="link-btn floaty-btn">Return Home</a>
            <?php
            } else {
                ?>
            <h1 class="page-title">Error</h1>
            <p>There was an error submitting your trouble ticket: <?php echo mysqli_error($connection) ?>. Please try again later.</p>
            <a href="index.php" class="link-btn floaty-btn">Return Home</a>
                <?php
            }
        }
    ?>
</div>