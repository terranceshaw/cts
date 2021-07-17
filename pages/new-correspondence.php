<div class="col padded">
    <h1 class="page-title">New Correspondence</h1>

    <?php
        if (empty($_POST)) {
            // If we've got no POST vars, show the form.
            include("pages/forms/new-correspondence-form.php");
        } else {
            // Otherwise, submit them to create new correspondence.
            $results = curl(CORRESPONDENCE_NEW, array("user_data"=>PERMISSIONS, "correspondence_data"=>$_POST));
            if ($results['status'] == 200) {
                $ctsID = $results['message'];
                echo "<h1>Correspondence Submitted</h1><br>\n";
                echo "<span><strong>CTS ID:</strong> $ctsID</span>\n";
            } else {
                echo "<h1>Error</h1><br>\n";
                echo "<span>" . $results['message'] . "</span>\n";
            }
            echo "<a href=\"?page=new-correspondence\" class=\"link-btn floaty-btn\" style=\"margin-top: 20px\"><i class=\"fas fa-arrow-circle-left btn-glyph\"></i> Go Back</a>\n";
        }
    ?>
</div>

<script>
    // Prevent page refreshes from re-submitting the form.
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>