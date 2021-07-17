<?php
    if (!empty($_POST)) {
        curl(ROUTING_GROUPS_NEW, array("group-name"=>$_POST['group-name'], "command_id"=>PERMISSIONS['command_id']));
    }
?><div class="col padded">
    <h1 class="page-title">Manage Routing Groups</h1>
    <span class="subtitle">Create, view, edit, and delete existing routing groups.</span>
    <form action="" method="post" class="col" id="routing-group-form">
        <div class="row gap">
            <div class="col" style="width: 100%">
                <select name="group-list" id="group-list">
                    <?php listGenerator(ROUTING_GROUPS_LIST, PERMISSIONS) ?>
                </select>
                <div class="row gap" style="margin-top: 10px">
                    <input type="button" value="Add Group" class="fluffy" id="add-group-btn">
                    <input type="button" value="Rename Group" class="fluffy" id="rename-group-btn">
                    <input type="button" value="Delete Group" class="fluffy" id="delete-group-btn">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        var groupID = $("#group-list :selected").val();

        $("#group-list").change(function(e) {
            // If the user selects a group, update the groupID variable
            groupID = $("#group-list :selected").val();
        });

        $("#add-group-btn").click(function() {
            var groupName = null;
            if (groupName = prompt("Provide a name for the new routing group.")) {
                $.post("<?php echo ROUTING_GROUPS_NEW ?>", {
                    "group_name":groupName
                }, function(data) {
                    refreshList(data, groupName);
                });
            }
        });

        $("#rename-group-btn").click(function() {
            // Rename the group with provided group name.
            if (groupName = prompt("Please enter a name to rename this group.")) {
                $.post("<?php echo ROUTING_GROUPS_UPDATE ?>", {
                    "group_id":groupID,
                    "group_name":groupName
                }, function(data) {
                    refreshList(data, groupName);
                });
            }
        })

        $("#delete-group-btn").click(function() {
            if (groupID !== null) {
                $.post("<?php echo ROUTING_GROUPS_DELETE ?>", {
                    "group_id":groupID
                }, function(data) {
                    refreshList(data);
                });
            } else {
                alert("Please select a group to proceed.");
            }
        });

        function refreshList(data, selectedItem=null) {
            var json = JSON.parse(data);
            if (json.status == 200) {
                $("#group-list").empty();
                $.each(json.message, function (k, v) { 
                    if (v.name == selectedItem) {
                        // If the current item is the one we created, select it.
                        $("#group-list").append("<option value=\"" + v.id + "\" selected>" + v.name + "</option>");
                    } else {
                        $("#group-list").append("<option value=\"" + v.id + "\">" + v.name + "</option>");
                    }
                });
                groupID = $("#group-list :selected").val(); // Update the groupID to currently selected item.
            } else {
                // There was an error; alert the media!
                alert(json.message);
            }
        }
    });
</script>