<?php
    $user = curl(USER_DETAILS, array("user_id"=>$_GET['user-id']))['message'];
?><div class="col padded">
    <?php
        if (empty($_POST)) {
            // No post variables set; display the form.
            include("pages/forms/edit-user-account-form.php");
        } else { 
            // Update the $_POST data to use valid keys.
            $results = curl(USER_EDIT, $_POST);
            $header = $results['status'] == 200 ? "Account Update Successful" : "Error Updating Account";
            $message = $results['message'];
            echo "<h1 class=\"page-title\">$header</h1>\n";
            echo "<span>$message</span>";
            echo "<a href=\"?view=admin&page=user-accounts\" class=\"link-btn floaty-btn\" style=\"margin-top: 20px\"><i class=\"fas fa-chevron-left btn-glyph\"></i> Go Back</a>\n";
        }
    ?>
</div>