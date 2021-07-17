<form action="" id="new-user-form" method="post" class="row gap" enctype="multipart/form-data">
    <div class="col" style="width: 100%">
        <h3>New Account</h3>
        <input type="hidden" name="command_id" value="1">
        <label for="department-id">Department</label>
        <select name="department-id" id="department-id">
            <?php listGenerator(DEPARTMENT_LIST, PERMISSIONS) ?>
        </select>
        <div class="row gap">
            <div class="col fluffy">
                <label for="role-id">Account Role</label>
                <select name="role-id" id="role-id">
                    <?php listGenerator(ACCOUNT_ROLES_LIST, PERMISSIONS) ?>
                </select>
            </div>
            <div class="col fluffy">
                <label for="group-id">Routing Group [<a href="?view=admin&page=routing-groups">Manage Routing Groups</a>]</label>
                <select name="group-id" id="group-id">
                    <option value="0">None (View Only)</option>
                    <option disabled>-</option>
                    <?php listGenerator(CORRESPONDENCE_GROUPS_LIST, PERMISSIONS) ?>
                </select>
            </div>
        </div>
        <div class="row gap">
            <div class="col fluffy">
                <label for="username" required>Username</label>
                <input type="text" name="username" id="username"<?php if (isset($_POST['username'])) echo " value=\"" . $_POST['username'] . "\""?>>
            </div>
            <div class="col fluffy">
                <label for="display-name" required>Display Name <i data-tooltip="Optional field; if left blank, username will be used as the display name." class="fas fa-question-circle tooltip"></i></label>
                <input type="text" name="display-name" id="display-name"<?php if (isset($_POST['display-name'])) echo " value=\"" . $_POST['display-name'] . "\""?>>
            </div>
        </div>
        <div class="row gap">
            <div class="col fluffy">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to use default password.">
            </div>
            <div class="col fluffy">
                <label for="confirm-password" required>Confirm Password</label>
                <input type="password" id="confirm-password">
            </div>
        </div>
        <label for="email-address">E-Mail Address</label>
        <input type="text" name="email-address" id="email-address"<?php if (isset($_POST['email-address'])) echo " value=\"" . $_POST['email-address'] . "\""?>>
    </div>
    <div class="col">
        <input type="submit" value="Submit" class="confirm-btn">
        <input type="reset" value="Reset" class="cancel-btn">
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#username").keydown(function() {
            console.log("Key pressed.");
            if ($(this).val() != "") {
                // If the username isn't blank, update the e-mail address field.
                $("#email-address").val($(this).val() + "@cvn77.navy.mil");
            } else {
                $("#email-address").val("");
            }
        });
    });
</script>