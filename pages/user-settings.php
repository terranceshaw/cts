<div class="padded">
    <h1 class="page-title">User Settings</h1>
    <?php
        global $status;
        global $connection;
        if ($status !== null) echo "<span class=\"message\">$status</span><br>\n";
        
    ?>
    <form action="" method="post" class="row gap" id="user-settings-form">
        <div class="row gap" style="width: 100%">
            <div class="col" style="width: 100%">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo LOGGED_IN_USER ?>" title="Your username cannot be changed." readonly="readonly">
                <label for="display-name">Display Name</label>
                <input type="text" name="display-name" id="display-name" value="<?php echo LOGGED_IN_DISPLAY_NAME ?>">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to keep current password.">
                <label for="email-address">E-mail Address</label>
                <input type="text" name="email-address" id="email-address" value="<?php echo $session['email_address'] ?>">
                <label for="notifications" style="line-height: 50px; font-size: 16px" ><input type="checkbox" name="notifications" id="notifications" <?php echo $session['get_notifications'] == 1 ? "checked" : null ?>> Receive correspondence notifications <i data-tooltip="You'll be notified via e-mail when new correspondence is pending for you." class="fas fa-question-circle tooltip"></i></label>
            </div>
            <div class="col" style="width: 100%">

            </div>
        </div>
        <div class="col">
            <input type="submit" value="Save Changes" class="confirm-btn" id="save-btn">
            <input type="reset" value="Reset" class="cancel-btn">
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#display-name").focus(); // Put the focus on the display-name textfield for maximum UX goodness.

            $("#user-settings-form").submit(function(e) {
                e.preventDefault();
                var formData = $("#user-settings-form").serialize();
                $.post("<?php echo USER_UPDATE ?>", formData, function (data) {
                        var json = JSON.parse(data);
                        if (json.status == 200) {
                            // Update was successful
                            $("#username").html("<i class=\"fas fa-user-circle nav-glyph\"></i> " + json.message)
                            $("#save-btn").css("background","#41a34b").val("Changes saved.");
                        }
                    }
                );
            });
        });
    </script>
</div>