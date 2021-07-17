<ul class="nav-bar main-nav">
    <li class="nav-item"><a href="cts.php" title="Home" class="nav-link"><i class="fas fa-home"></i></a></li>
    <li class="nav-item"><a href="#" class="nav-link">Correspondence</a>
        <ul class="nav-bar">
            <li class="nav-item"><a href="?page=new-correspondence" class="nav-link">New Correspondence</a></li>
            <li class="nav-item"><a href="?page=view-correspondence" class="nav-link">View Correspondence</a></li>
        </ul>
    </li>
    <?php if (PERMISSIONS['is_admin'] == 1 ) { // Only if the user is an admin should they see this set of options. ?>
    <li class="nav-item"><a href="#" class="nav-link">Admin Tools</a>
        <ul class="nav-bar">
            <li class="nav-item"><a href="?view=admin&page=cts-settings" class="nav-link">CTS Settings</a></li>
            <li class="nav-item"><a href="?view=admin&page=user-accounts" class="nav-link">User Accounts <span class="chevron">&rsaquo;</span></a>
                <ul class="nav-bar sub-menu">
                    <li class="nav-item"><a href="?view=admin&page=account-roles" class="nav-link">Account Roles</a></li>
                    <li class="nav-item"><a href="?view=admin&page=user-accounts" class="nav-link">View Accounts</a></li>
                    <li class="nav-item"><a href="?view=admin&page=user-accounts-new" class="nav-link">New Account</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="#" class="nav-link">Correspondence <span class="chevron">&rsaquo;</span></a>
                <ul class="nav-bar sub-menu">
                    <li class="nav-item"><a href="?view=admin&page=routing-groups" class="nav-link">Manage Routing Groups</a></li>
                    <li class="nav-item"><a href="?view=admin&page=routing-chains" class="nav-link">Manage Routing Chains</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="?view=admin&page=trouble-tickets" class="nav-link">Trouble Tickets</a></li>
        </ul>
    </li>
    <?php } ?>
    <?php if (PERMISSIONS['is_developer'] == 1 ) { // Only if the user is a developer should they see this set of options. ?>
    <li class="nav-item"><a href="#" class="nav-link">Developer Tools</a>
        <ul class="nav-bar">
            <li class="nav-item"><a href="?view=admin&page=command-setup" class="nav-link">Command Setup</a></li>
            <li class="nav-item"><a href="?view=dev&page=api-testing" class="nav-link">API Testing</a></li>
            <li class="nav-item"><a href="?view=dev&page=session-data" class="nav-link">Session Data</a></li>
        </ul>
    </li>
    <?php } ?>
    <li class="nav-item"><a href="#" class="nav-link">Help</a>
        <ul class="nav-bar">
            <li class="nav-item"><a href="?page=faq" class="nav-link">FAQ</a></li>
            <li class="nav-item"><a href="?page=user-guide" class="nav-link">User Guide</a></li>
            <li class="nav-item"><a href="?page=trouble-ticket" class="nav-link">Trouble Ticket</a></li>
            <li class="nav-item"><a href="?page=about" class="nav-link">About CTS</a></li>
        </ul>
    </li>
    <li class="nav-item end"><a href="?page=user-settings" class="nav-link" title="Logged in as <?php echo LOGGED_IN_USER ?>. Click to view and change personal account settings."><span id="username"><i class="fas fa-user-circle nav-glyph"></i> <?php echo LOGGED_IN_DISPLAY_NAME ?></a></span></li>
    <li class="nav-item"><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
</ul>