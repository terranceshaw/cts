<?php
    if (!empty($_POST)) {   // We have post vars!
        $_POST['routing-chain'] = array_reverse($_POST['routing-chain']);   // Reverse the array so we can 
        $typeID = $_POST['correspondence-type'];    // For our next trick, we'll need the type ID...
        $routingChain = empty($_POST['routing-chain']) ? null : serialize($_POST['routing-chain']); // And the chain, even if it's blank.
        $query = "UPDATE cts_correspondence_types SET routing_chain='$routingChain' WHERE id=$typeID";
        if (!mysqli_query($connection, $query)) {

        }
    }
?>
<div class="col padded">
    <h1 class="page-title">Manage Routing Chains</h1>
    <form action="" method="post" class="col" id="routing-chain-form">
        <div class="row gap">
            <div class="col" style="flex: 1 0 auto">
                <div class="row">
                    <div class="col" style="width: 100%">
                        <label for="correspondence-type">Correspondence Type</label>
                        <select name="correspondence-type" id="correspondence-type">
                            <?php // listGenerator(CORRESPONDENCE_TYPES_LIST, array("command_id"=>COMMAND_ID)) 
                            
                                // Gonna have to do some home-rolled dealies for this guy to speed up development
                                $firstType = null;
                                $query = "SELECT * FROM cts_correspondence_types WHERE command_id=" . COMMAND_ID . " ORDER BY name ASC";
                                if ($result = mysqli_query($connection, $query)) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $rows[] = $row;
                                        $id = $row['id'];
                                        $name = $row['name'];
                                        echo "<option value=\"$id\">$name</option>\n";
                                    }
                                    $firstType = $rows[0]['id'];  // Store the ID of the first item to fetch the routing chain.
                                }
                            
                            ?>
                        </select>
                    </div>
                    <input type="button" value="+" id="add-type-btn" class="list-btn" title="Add a new correspondence type.">
                    <input type="button" value="-" id="remove-type-btn" class="list-btn" title="Remove the selected correspondence type.">
                </div>
                <div class="row gap" style="justify-content: space-between; flex-wrap: nowrap">
                    <div class="col" style="width: 50%">
                        <label for="routing-chain">Routing Chain <i data-tooltip="The routing chain is the order in which the correspondence will travel, with the top group being the final approving authority." class="fas fa-question-circle tooltip"></i></label>
                        <select name="routing-chain[]" id="routing-chain" size="10" style="height: 200px" multiple>
                            <?php
                                $query = "SELECT routing_chain FROM cts_correspondence_types WHERE id=$firstType";
                                // We're gonna have to save the list of types that are currently assigned to use later, so that we don't offer those same types as ones that can be chosen.
                                $types = null;  // Setting this as null until populated via query.
                                $orderedItems = null;
                                if ($result = mysqli_query($connection, $query)) {
                                    // Grab the routing groups from the routing_chain, unserialize the array and reverse it, then turn it into a list of comma-separated values for our next query.
                                    $types = implode(", ", array_reverse(unserialize(mysqli_fetch_row($result)[0])));
                                    if (!empty($types)) {
                                        $query = "SELECT * FROM cts_routing_groups WHERE id IN ($types)";
                                        if ($result = mysqli_query($connection, $query)) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $rows2[] = $row;
                                            }
                                            
                                            $orderedItems = explode(", ", $types);
                                            foreach ($orderedItems as $item) {
                                                foreach($rows2 as $row2) {
                                                    if ($row2['id'] == $item) {
                                                        $id = $row2['id'];
                                                        $name = $row2['name'];
                                                        echo "<option value=\"$id\">$name</option>\n";
                                                    }
                                                }
                                            }
                                        } else {
                                            $error = mysqli_error($connection);
                                            echo "<option>$error</option>";
                                        }
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col" style="justify-content: center">
                        <input type="button" class="large-btn move-btn" id="up-btn" value="🞁">
                        <input type="button" class="large-btn" id="add-btn" value="<">
                        <input type="button" class="large-btn" id="remove-btn" value=">">
                        <input type="button" class="large-btn move-btn" id="down-btn" value="🞃">
                    </div>
                    <div class="col" style="width: 50%">
                        <label for="available-groups">Available Routing Groups</label>
                        <select name="available-groups" id="available-groups" size="10" style="height: 200px" multiple>
                            <?php // listGenerator(CORRESPONDENCE_GROUPS_LIST, array("command_id"=>COMMAND_ID)) 
                            // Another thing to speed things up because you procrastinated, broh.
                            $types = !empty($types) ? " AND id NOT IN ($types)" : null;
                            $query = "SELECT * FROM cts_routing_groups WHERE command_id=1 $types ORDER BY name ASC";
                            if ($result = mysqli_query($connection, $query)) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['id'];
                                    $name = $row['name'];
                                    echo "<option value=\"$id\">$name</option>\n";
                                }
                            } else {
                                $error = mysqli_error($connection);
                                echo "<option disabled>$error</option>\n";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col">
                <input type="submit" class="confirm-btn" value="Save">
                <input type="reset" class="cancel-btn" value="Cancel" onClick="window.history.back()">
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        // Reorder routing chain
        $('.move-btn').click(function(){
            var $op = $('#routing-chain option:selected'),
                $this = $(this);
            if($op.length){
                ($this.val() == '🞁') ? 
                    $op.first().prev().before($op) : 
                    $op.last().next().after($op);
            }
        });

        // Add new correspondence types
        $("#add-type-btn").click(function() {
            var typeName = prompt("Please specify a name for the new correspondence type.");
            if (typeName) {
                // if the typeName var isn't blank, proceed with the AJAX.
                $.post("<?php echo CORRESPONDENCE_TYPE_NEW?>", {
                    "command_id": <?php echo $_SESSION['cts']['command_id'] ?>,
                    "type_name": typeName
                }, function(data) {
                    var json = JSON.parse(data);
                    if (json['status'] == 200) {
                        // Results are good, update the list.
                        $("#correspondence-type").empty();
                        $.each(json['message'], function (k, v) { 
                            $("#correspondence-type").append("<option value=\"" + v.id + "\">" + v.name + "</option>");
                        });
                    } else {
                        alert(json['message']);
                    }
                });
            }
        })

        // Remove selected correspondence type
        $("#remove-type-btn").click(function() {
            var typeID = $("#correspondence-type option:selected").val();
            if (typeID !== null) {
                if (confirm("Delete the correspondence type '" + $("#correspondence-type option:selected").text() + "'?")) {
                    // if the typeName var isn't blank, and they've confirmed the deletion, proceed with the AJAX.
                    $.post("<?php echo CORRESPONDENCE_TYPE_DELETE ?>", {
                        "command_id": <?php echo COMMAND_ID ?>,
                        "type_id": typeID
                    }, function(data) {
                        var json = JSON.parse(data);
                        if (json['status'] == 200) {
                            // Results are good, update the list.
                            $("#correspondence-type").empty();
                            $.each(json['message'], function (k, v) { 
                                $("#correspondence-type").append("<option value=\"" + v.id + "\">" + v.name + "</option>");
                            });
                            getLists();
                        } else {
                            alert(json['message']);
                        }
                    });
                }
            }
        })

        // Add or remove options from the routing chain.
        $("#add-btn").click(function(){
            $("#available-groups option:selected").remove().appendTo($("#routing-chain"));
        })

        $("#remove-btn").click(function(){
            $("#routing-chain option:selected").remove().appendTo($("#available-groups"));
            // And also sort alphabetically to keep things nice.
            var options = $('#available-groups option');
            var arr = options.map(function(_, o) { return { t: $(o).text(), v: o.value }; }).get();
            arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
            options.each(function(i, o) {
            o.value = arr[i].v;
            $(o).text(arr[i].t);
            });
        })

        $("#routing-chain-form").on("submit", selectAll);
        function selectAll() {
            $("#routing-chain option").prop("selected", true);
        }

        // Get new routing chain if a new correspondence type is selected.
        $("#correspondence-type").change(function() {
            getLists();
        });

        function getLists(list) {
            var typeID = $("#correspondence-type").val()
            $.post("<?php echo CORRESPONDENCE_ROUTE_CHAIN_LIST ?>", {
                "type-id":typeID
            }, function(data) {
                $("#routing-chain").empty();    // Empty the list if nothing is returned.
                $("#available-groups").empty();
                if (data) {
                    data = JSON.parse(data);
                    $.each(data['selected-groups'], function (k, v) { 
                        $("#routing-chain").append("<option value=\"" + v.id + "\">" + v.name + "</option>")
                    });
                    $.each(data['available-groups'], function (k, v) { 
                        $("#available-groups").append("<option value=\"" + v.id + "\">" + v.name + "</option>")
                    });
                }
            });
            
        }
    });
</script>