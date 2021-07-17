<div class="col padded">
    <h1 class="page-title">Manage Chop Chains</h1>
    <form action="" method="post" class="col">
        <label for="correspondence-type">Correspondence Type</label>
        <select name="correspondence-type" id="correspondence-type">
            <?php listGenerator(CORRESPONDENCE_TYPES_LIST, array("command_id"=>COMMAND_ID)) ?>
        </select>
        <div class="row gap" style="justify-content: space-between; flex-wrap: nowrap">
            <div class="col" style="width: 50%">
                <label for="current-chain">Current Chain</label>
                <select name="current-chain[]" id="current-chain" size="5">
                </select>
            </div>
            <div class="col" style="justify-content: center">
                <input type="button" value="▲" class="move-button" id="up-btn">
                <input type="button" value="<" id="add-btn" class="button">
                <input type="button" value=">" id="remove-btn" class="button">
                <input type="button" value="▼" class="move-button" id="down-btn">
            </div>
            <div class="col" style="width: 50%">
                <label for="available-groups">Available Groups</label>
                <select name="available-groups" id="available-groups" size="5">
                    <option value="0">Dept LCPOs</option>
                    <option value="1">Dept Heads</option>
                    <option value="2">LCPOs</option>
                </select>
            </div>
        </div>
        <input type="submit" value="Save" class="confirm-btn">
    </form>

    <pre>
        <?php
            if ($_POST) {
                print_r($_POST);
            }
        ?>
    </pre>

    <script>
        $(document).ready(function(){
            // Logic to add/remove users from the chop chain.
            // https://stackoverflow.com/questions/1058517/jquery-moving-multiselect-values-to-another-multiselect
             $('#add-btn').click(function() {  
                return !$('#available-groups option:selected').remove().appendTo('#current-chain');  
            });  
            $('#remove-btn').click(function() {  
                return !$('#current-chain option:selected').remove().appendTo('#available-groups');  
            });

            // Logic to reorder the list of selected groups/users in the chop chain.
            $('.move-button').click(function() {
                var $op = $('#current-chain option:selected'),
                    $this = $(this);
                if ($op.length) {
                    ($this.val() == '▲') ? 
                        $op.first().prev().before($op) : 
                        $op.last().next().after($op);
                }
            });
        });
    </script>
</div>