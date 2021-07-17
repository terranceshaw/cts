<div class="col padded">
    <h1 class="page-title">User Accounts</h1>

    <div class="row" style="margin-bottom: 5px">
        <div class="col">
            <label for="department-filter">Department</label>
            <select name="department-filter" id="department-filter" style="width: 250px">
                <option value="0">All</option>
                <option disabled>-</option>
                <?php listGenerator(DEPARTMENT_LIST, PERMISSIONS)?>
            </select>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <td style="">Actions</td>
                <td>Username</td>
                <td>Role</td>
                <td>Group</td>
                <td>Department</td>
                <td style="width: 100px">Enabled</td>
                <td style="width: 100px">Locked</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $userList = curl(USER_LIST, PERMISSIONS);
                $message = $userList['message'];
                if ($userList['status'] == 500) {
                    // We don't have users, or there was a problem getting them. Either way, show what happened.
                    echo "<td colspan=\"6\">$message</td>";
                } else {
                    if (count($userList) > 0) {
                        // If we've got users, show them.
                        $userList = $message;
                        foreach ($userList as $user) {
                            $username = $user['username'];
                            $role = $user['role_name'];
                            $group = $user['group_name'];
                            $department = $user['dept_name'];
                            $isEnabled = $user['is_enabled'] == 1 ? " checked" : null;
                            $isLocked = $user['is_locked'] == 1 ? " checked" : null;
                            $userID = $user['user_id'];
                            echo "<tr class=\"user\" data-user-id=\"$userID\">\n";
                            echo "<td style=\"display: flex; flex-direction: row; padding-top: 10px\"><a href=\"?view=admin&page=edit-user&user-id=$userID\" title=\"Edit account for $username\"><i class=\"fas fa-edit toolbar-button\"></i></a> <a href=\"?view=admin&page=edit-user&user-id=$userID\" title=\"Delete account for $username\"><i class=\"fas fa-user-slash toolbar-button\" id=\"delete-user\" data-user-id=\"" . $userID . "\" data-username=\"" . $username . "\"></i></a></td>\n";
                            echo "<td>$username</td>\n";
                            echo "<td>$role</td>\n";
                            echo "<td>$group</td>\n";
                            echo "<td>$department</td>\n";
                            echo "<td>\n";
                            echo "\t<label class=\"switch\">\n";
                            echo "\t\t<input type=\"checkbox\" name=\"is-ebabled\" data-action=\"enabled\" data-user-id=\"$userID\" class=\"toggle\"$isEnabled>\n";
                            echo "\t\t<span class=\"slider round\"></span>\n";
                            echo "\t</label>\n";
                            echo "</td>\n";
                            echo "<td>\n";
                            echo "\t<label class=\"switch\">\n";
                            echo "\t\t<input type=\"checkbox\" name=\"is-locked\" data-action=\"locked\" data-user-id=\"$userID\" class=\"toggle\"$isLocked>\n";
                            echo "\t\t<span class=\"slider round\"></span>\n";
                            echo "\t</label>\n";
                            echo "</td>\n";
                            echo "</tr>\n";
                        }
                    } else {
                        // Otherwise, let that be known, too.
                        // Though this should never be the case, otherwise we wouldn't even be here. :|
                        echo "<td colspan=\"7\">No current user accounts.</td>";
                    }
                }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $(".toggle").click(function(e) {
            // e.stopPropagation();
            var userID = $(this).attr("data-user-id");
            var action = $(this).attr("data-action");
            var status = $(this).is(":checked") ? 1 : 0;
            // console.log("Clicked a(n) " + action + " checkbox for user ID " + userID + " (" + status + ")");
            $.post("<?php echo USER_TOGGLE ?>", {
                "user_id":userID,
                "setting":action,
                "value":status
            }, function(data) {
                console.log(data);
            });
        });

        $(".user").click(function(e) {
            // Can't figure out how to stop propagation in the checkbox
            // TODO: Figure it out
            var userID = $(this).attr("data-user-id");
            console.log("Clicked a row for user ID of " + userID);
        });

        $(document).on("click", "#delete-user", function(e) {
            // Set up the listener for delete requests
            e.preventDefault();
            var userID = $(this).attr("data-user-id");
            var username = $(this).attr("data-username");
            if (confirm("Are you sure you want to delete the account for " + username + "?")) {
                // alert("Kidding! Can't do that yet; gotta gimme a minute. V/R, ITC Shaw");
                $.post("<?php echo USER_DELETE ?>", {
                    "user_id":userID
                }, function (data) {
                        console.log(data);
                        var json = JSON.parse(data);
                        if (json.status == 200) {
                            // Update was successful
                            $(this).parent().parent().parent().fadeOut("slow");
                        } else {
                            alert("Unable to delete user: " + json.message);
                        }
                    }
                );
            }
        });
    });
</script>