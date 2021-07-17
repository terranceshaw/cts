<h1 class="page-title">Edit User</h1>
<span class="subtitle">View and edit user account settings.</span>
<form action="" method="post" class="row gap">
    <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
    <div class="col fluffy">
        <div class="row gap">
            <div class="col fluffy">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo $user['username'] ?>">
            </div>
            <div class="col fluffy">
                <label for="display-name">Display Name <i data-tooltip="If left blank, the username will be used." class="fas fa-question-circle tooltip"></i></label>
                <input type="text" name="display_name" id="display-name" value="<?php echo $user['display_name'] ?>">
            </div>
        </div>
        <div class="row gap">
            <div class="col fluffy">
                <label for="email-address">E-mail Address <i data-tooltip="Invalid e-mail addresses will cause notification delivery failure." class="fas fa-question-circle tooltip"></i></label>
                <input type="text" name="email_address" id="email-address" value="<?php echo $user['email_address'] ?>">
            </div>
            <div class="col fluffy">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to keep current password.">
            </div>
        </div>
        <label for="department-id">Department</label>
        <select name="department_id" id="department-id">
            <?php listGenerator(DEPARTMENT_LIST, PERMISSIONS, $user['department_id']) ?>
        </select>
        <div class="row gap">
            <div class="col fluffy">
                <label for="role-id">Role [<a href="?view=admin&page=account-roles">Manage Account Roles</a>]</label>
                <select name="role_id" id="role-id">
                    <?php listGenerator(ACCOUNT_ROLES_LIST, PERMISSIONS, $user['role_id'])?>
                </select>
            </div>
            <div class="col fluffy">
                <label for="group-id">Routing Group [<a href="?view=admin&page=routing-groups">Manage Routing Groups</a>]</label>
                <select name="group_id" id="group-id">
                    <option value="0">None (Read Only)</option>
                    <option disabled>-</option>
                    <?php listGenerator(ROUTING_GROUPS_LIST, PERMISSIONS, $user['group_id']); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col">
        <input type="submit" value="Save" class="confirm-btn">
        <input type="button" value="Cancel" onClick="window.history.back()" class="cancel-btn">
    </div>
</form>
<?php IS_DEVELOPER ? pre_array($user) : null?>