<div class="padded">
    <h1 class="page-title">Account Roles</h1>
    <div class="row gap">
        <div class="col" style="width: 100%">
            <label for="selected-role">Selected Role</label>
            <select name="selected-role" id="selected-role">
                <?php listGenerator(ACCOUNT_ROLES_LIST, array("command_id"=>COMMAND_ID)) ?>
            </select>
            <div class="row gap" style="justify-content: space-around; margin-top: 10px">
                <input type="button" value="New Role" id="new-role-btn" style="width: 50%">
                <input type="button" value="Delete Role" id="delete-role-btn" style="width: 50%">
            </div>
        </div>
        <div class="col" style="width: 100%">
            <label for="">Permissions</label>
            <label for="is-admin" class="checkbox-label"><input type="checkbox" name="is-admin" id="is-admin"> Has administrative rights to the system. <i data-tooltip="Gives the user administrative access to CTS to configure the system and its behaviors." class="fas fa-question-circle tooltip"></i></label>
            <label for="has-command-access" class="checkbox-label"><input type="checkbox" name="has-command-access" id="has-command-access"> Has command-wide access to correspondence. <i data-tooltip="Allows the user to modify and route correspondence for the entire command." class="fas fa-question-circle tooltip"></i></label>
            <?php if (IS_DEVELOPER) { ?>
            <label for="is-developer" class="checkbox-label"><input type="checkbox" name="is-developer" id="is-developer"> Has developer access to the system.</label>
            <?php } ?>
        </div>
        <div class="col">
            <input type="submit" value="Save" class="confirm-btn" id="save-btn">
            <input type="reset" value="Cancel" class="cancel-btn">
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var roleID = $("#selected-role :selected").val();   // Get the ID of the first role in the list...
        getPermissions(roleID); // And then get the permissions for that list.

        $("#selected-role").change(function() {
            var roleID = $(this).val();
            getPermissions(roleID);
        });

        $("#new-role-btn").click(function() {
            var roleName = null;
            if (roleName = prompt("Please specify a name for the new role.")) {
                $.post("<?php echo ACCOUNT_ROLES_NEW ?>", {
                    "role_name":roleName
                }, function(data) {
                    if (refreshList(data, roleName)) {
                        // New list obtained, clear all the checkboxes.
                        $("#is-admin").prop("checked", false);
                        $("#has-command-access").prop("checked", false);
                        <?php if (IS_ADMIN) echo "$(\"#is-developer\").prop(\"checked\", false);" ?>
                    }
                });
            }
        });

        $("#delete-role-btn").click(function() {
            if (confirm("Deleting this role will also delete all accounts with this role. Do you wish to proceed?")) {
                // Make sure they understand this is a pretty big deal.
                roleID = $("#selected-role").val()
                $.post("<?php echo ACCOUNT_ROLES_DELETE ?>", {
                    "role_id":roleID
                }, function(data) {
                    refreshList(data);
                });
            }
        });

        $("#save-btn").click(function() {
            // Save the changes!
            var roleID = $("#selected-role").val();
            var isAdmin = $("#is-admin").is(":checked") ? 1 : 0;
            var hasCommandAccess = $("#has-command-access").is(":checked") ? 1 : 0;
            <?php echo IS_DEVELOPER ? "var isDeveloper = $(\"#is-developer\").is(\":checked\") ? 1 : 0;" : null ?>
            $.post("<?php echo ACCOUNT_ROLES_UPDATE ?>", {
                "role_id":roleID,
                "is_admin":isAdmin,
                "has_command_access":hasCommandAccess
                <?php echo IS_DEVELOPER ? ", \"is_developer\": isDeveloper\n" : null ?>
            }, function(data) {
                console.log(data);
            });
        });

        function refreshList(data, selectedRoleName=null) {
            if (JSON.parse(data)) {
                // Verify it's good JSON.
                var json = JSON.parse(data);
                if (json.status == 200) {
                    // Fetch successful; proceed
                    $("#selected-role").empty();
                    var roles = json.message;
                    $.each(roles, function (k, v) { 
                        if (v.name == selectedRoleName) {
                            // If this role is to be selected, update the ID and set the option as such.
                            $("#selected-role").append("<option value=\"" + v.id + "\" selected>" + v.name + "</option>");
                        } else {
                            $("#selected-role").append("<option value=\"" + v.id + "\">" + v.name + "</option>");
                        }
                    });
                    roleID = $("#selected-role").val();
                    return true;
                } else {
                    alert(json.message);
                    return false;
                }
            } else {
                console.log(data);
            }
        }

        function getPermissions(roleID) {
            $.post("<?php echo ACCOUNT_ROLES_PERMISSIONS_LIST ?>", {
                "role_id":roleID
            }, function(data) {
                var role = JSON.parse(data)['message'][0];
                $("#is-admin").prop("checked", role.is_admin == 1 ? true : false);
                $("#has-command-access").prop("checked", role.has_command_access == 1 ? true : false);
                <?php echo IS_DEVELOPER ? "$(\"#is-developer\").prop(\"checked\", role.is_developer == 1 ? true : false)" : null ?>
            });
        }
    });
</script>