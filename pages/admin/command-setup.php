<div class="padded">
    <h1 class="page-title">Command Setup</h1>
    <div class="col">
        <label for="selected-organization">Selected Organization</label>
        <select name="selected-organization" id="selected-organization">
            <?php listGenerator(COMMAND_LIST, array("command_id"=>COMMAND_ID)) ?>
        </select>
    </div>
</div>