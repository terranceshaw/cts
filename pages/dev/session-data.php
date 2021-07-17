<div class="col padded">
    <h1 class="page-title">Session Data</h1>
    <span class="subtitle">Used to view session data to ensure everything is set correctly.</span>
    <?php
        pre_array($session);
        pre_array($_SESSION);
        pre_array(PERMISSIONS);
    ?>
</div>