<div class="col padded">
    <h1 class="page-title">New User Account</h1>
    <?php
        if (empty($_POST)) {
            // No POST vars set; display the new user form.
            include("pages/forms/new-user-account-form.php");
        } else {
            // POST vars set; try to create the new user.
            $newUserMsg = curl(USER_NEW, $_POST);
            echo $newUserMsg['message'];
            echo "<a href=\"#\" class=\"link-btn floaty-btn\" onclick=\"window.history.back();\">Go Back</a>";
        }
    ?>
</div>