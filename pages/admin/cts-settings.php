<div class="col padded">
    <h1 class="page-title">CTS Settings</h1>
    <?php
    // Query to get the current auto_increment of the cts_correspondence table.
    $ctsID = null;
    $query = "SELECT AUTO_INCREMENT
              FROM information_schema.TABLES
              WHERE TABLE_SCHEMA = 'nemesys'
              AND TABLE_NAME = 'cts_correspondence'";
    if ($result = mysqli_query($connection, $query)) {
        if ($row = mysqli_fetch_assoc($result)) {
            $ctsID = $row['AUTO_INCREMENT'];
        }
    }

    if (!empty($_POST)) {
        if (!empty($_POST['cts-id-start-value'])) {
            $newStart = sanitize($_POST['cts-id-start-value']);
            if ($newStart > $ctsID) {
                $ctsID = $newStart;
                // If we've provided a value that's greater than the current CTS ID, increase it. Otherwise, do nada.
                $query = "ALTER TABLE cts_correspondence AUTO_INCREMENT = $newStart";
                if (!$result = mysqli_query($connection, $query)) {
                    // Failed to update the ID, explain why.
                    $error = mysqli_error($connection);
                    echo "<span>Error updating CTS ID:<strong> $error</span>";
                }
            }
        }

        if (!empty($_POST['reset-cts'])) {
            // User has opted to reset CTS correspondence.
            mysqli_query($connection, "SET FOREIGN_KEY_CHECKS=0;"); // Gotta temporarily disable key checks to allow truncate of history table.
            if (mysqli_query($connection, "TRUNCATE cts_correspondence;")) {
                if (mysqli_query($connection, "TRUNCATE cts_correspondence_history;")) {
                    if (mysqli_query($connection, "ALTER TABLE cts_correspondence AUTO_INCREMENT = 1")) {
                        mysqli_query($connection, "ALTER TABLE cts_correspondence_history AUTO_INCREMENT = 1");
                        mysqli_query($connection, "SET FOREIGN_KEY_CHECKS=1;"); // Re-enable foreign key checks
                        $ctsID = 1;
                    } else {
                        $error = mysqli_error($connection);
                        echo "<span>Error resetting CTS data:<strong> $error</span>";
                    }
                } else {
                    $error = mysqli_error($connection);
                    echo "<span>Error resetting CTS data:<strong> $error</span>";                    
                }
            } else {
                $error = mysqli_error($connection);
                echo "<span>Error resetting CTS data:<strong> $error</span>";
            }
        }
    }

    ?>
    <form action="" method="post" id="new-cts-id-form" class="row gap">
        <div class="col" style="width: 100%">
            <label for="cts-id-start-value">CTS ID Start Value <i data-tooltip="The starting value for CTS IDs. Be advised: Once set, you cannot set the value to a lower one." class="fas fa-question-circle tooltip"></i></label>
            <input type="text" name="cts-id-start-value" id="cts-id-start-value" value="<?php echo $ctsID ?>">
        </div>
        <div class="col" style="flex: 1 0 auto">
            <input type="submit" value="Save" class="confirm-btn">
            <input type="reset" value="Cancel" class="cancel-btn">
        </div>
    </form>

    <?php if ($ctsID > 1) {?>
        <form action="" method="post" id="reset-cts" class="row gap highlight">
            <div class="col">
                <input type="hidden" name="reset-cts" value="true">
                <span>Reset CTS Data</span>
                <span>This will remove all correspondence from the system and reset CTS IDs to 1. <strong>This action is irreversible.</strong></span>
            </div>
            <input type="submit" value="Reset" class="end" style="background: crimson; border: 0; border-radius: 5px; width: 150px">
        </form>
    <?php } ?>
</div>

<script>
    $(document).ready(function () {
        var acceptTheConsequences = false;
        $("#reset-cts").submit(function(e) {
            if (!acceptTheConsequences) {
                e.preventDefault();
                if (confirm("Performing this action will delete all correspondence currently being tracked.")) {
                    if (confirm("Be advised: you cannot recover any data that is deleted.")) {
                        if (confirm("Final warning: deletion follows.")) {
                            // They made their choice; continue.
                            acceptTheConsequences = true;
                            $("#reset-cts").submit();
                        }
                    }
                }
            }
        });
    });
</script>