<div class="col padded">
    <?php
        if ($ctsID = $_GET['cts-id']) {
            // A CTS ID was provided, check that.
            include("pages/forms/route-correspondence-form.php");
        } else {
            include("pages/tables/view-correspondence-table.php");
        }
    ?>
</div>

<script>
    $(document).ready(function () {
        $(".cts-item").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>