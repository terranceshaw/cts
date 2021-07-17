<?php 
    if (!empty($_GET)) {
        foreach ($_GET as $key=>$value) {
            if (is_null($value) || $value == '') {
                unset($_GET[$key]);
            }
        }
    }
?><h1 class="page-title">View Correspondence</h1>
<div class="col" style="margin-bottom: 10px">
    <input type="hidden" name="page" value="view-correspondence">
    <label>Filters</label>
    <div class="row gap">
        <div class="col" style="width: 100px">
            <label for="cts-id">CTS ID</label>
            <input type="text" name="cts-id" class="search-box" id="cts-id" data-filter="cts-id">
        </div>
        <div class="col" style="width: 100%">
            <label for="subject">Subject</label>
            <input type="text" name="subject" class="search-box" id="subject" data-filter="subject">
        </div>
        <?php if (PERMISSIONS['has_command_access'] == 1) { // They should only be able to change this if they have command-wide access ?>
        <div class="col" style="width: 500px">
            <label for="department-id">Originating Department</label>
            <select name="department-id" class="search-list" id="department-id" data-filter="origin-dept">
                <option value="">All Departments</option>
                <option disabled>-</option>
                <?php listGenerator(DEPARTMENT_LIST, PERMISSIONS)?>
            </select>
        </div>
        <?php } ?>
        <div class="col" style="width: 500px">
            <label for="type-id">Correspondence Type</label>
            <select name="type-id" class="search-list" id="type-id" data-filter="correspondence-type">
                <option value="">All Types</option>
                <option disabled>-</option>
                <?php listGenerator(CORRESPONDENCE_TYPES_LIST, PERMISSIONS)?>
            </select>
        </div>
    </div>
    <label for="show-closed" class="checkbox-label"><input type="checkbox" name="show-closed" id="show-closed"> Show closed out correspondence.</label>
</div>

<?php $correspondence = curl(CORRESPONDENCE_LIST, PERMISSIONS); // Fetch the current correspondence. ?>

<table>
    <thead>
        <tr>
            <th style="width: 50px" title="Toggles correspondence privacy."><i class="fas fa-eye"></i></th>
            <th>CTS ID <i class="fas fa-sort sort-glyph"></i></th>
            <th>Entry Date <i class="fas fa-sort sort-glyph"></i></th>
            <th>Subject <i class="fas fa-sort sort-glyph"></i></th>
            <th class="table-extra">Status <i class="fas fa-sort sort-glyph"></i></th>
            <th class="table-extra">Originating Department <i class="fas fa-sort sort-glyph"></i></th>
            <th class="table-extra">Type <i class="fas fa-sort sort-glyph"></i></th>
        </tr>
    </thead>
    <tbody id="cts-items">
        <?php
            $message = $correspondence['message'];
            if ($correspondence['status'] == 500) {
                // There was an error of some sort. Display it.
                echo "<td colspan=\"5\">$message</td>";
            } else {
                // We got results; display them.
                foreach ($message as $row) {
                    $isPrivate = $row['is_private'] == 0 ? "<i class=\"fas fa-eye privacy-toggle\" data-cts-id=\"$ctsID\"  title=\"Not Private\"></i>" : "<i class=\"fas fa-eye-slash privacy-toggle\" data-cts-id=\"$ctsID\" title=\"Private\"></i>";
                    $ctsID = $row['id'];
                    $entryDate = $row['date_entered'];
                    $subject = $row['subject'];
                    $originDepartment = $row['dept_name'];
                    $type = $row['type_name'];
                    $status = $row['status'];
                    echo "<tr class=\"cts-item\" data-href=\"?page=view-correspondence&cts-id=$ctsID\">\n";
                    echo "<td>$isPrivate</td>\n";
                    echo "<td>$ctsID</td>\n";
                    echo "<td>$entryDate</td>\n";
                    echo "<td>$subject</td>\n";
                    echo "<td class=\"table-extra\">$status</td>\n";
                    echo "<td class=\"table-extra\">$originDepartment</td>\n";
                    echo "<td class=\"table-extra\">$type</td>\n";
                    echo "</tr>\n";
                }
            }
        ?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        // As per good UX, set one of the text fields' focus when loaded.
        $("#cts-id").focus();
        $("#show-closed").attr("checked", false);

        // React to the enter key being pressed
        $(".search-box").keydown(function(e) {
            if (e.keyCode == 13) {
                var filter = $(this).attr("data-filter");
                var value = $(this).val();
                $.post("<?php echo CORRESPONDENCE_LIST ?>", {
                    "search":true,
                    "filter":filter,
                    "value":value
                }, function(data) {
                    console.log(data);
                    listCorrespondence(data);
                });
            }
        });

        $(".search-list").change(function(e) {
            var filterType = $(this).attr("data-filter");
            var filterQuery = $(this).val();
            var deptID = $("#department-id").val();
            var typeID = $("#type-id").val();
            console.log("List changed: " + filterType + " (" + filterQuery + ")");
            $.post("<?php echo CORRESPONDENCE_LIST ?>", {
                "search":true,
                "filter":filterType,
                "value":filterQuery
            }, function(data) {
                console.log(data);
                listCorrespondence(data);
            });
        });

        $("#cts-items").on("click", ".cts-item", function() {
            window.location = $(this).data("href");
        });

        $("#cts-items").on("click", ".privacy-toggle", function(e) {
            e.stopPropagation();
            var ctsID = $(this).attr("data-cts-id");
            console.log("Toggled privacy for " + ctsID);
        });

        var toggle = null;
        $("#show-closed").click(function(e) {
            toggle = $(this).is(":checked") ? 1 : 0;
            // console.log("Clicked the checkbox (" + toggle + ").")
            $.post("<?php echo CORRESPONDENCE_LIST ?>", {
                "show-closed":toggle
            }, function(data) {
                listCorrespondence(data);
            });
        });

        function listCorrespondence(data) {
            var json = JSON.parse(data);
            $("#cts-items").empty();    // Empty the current list.
            if (json.status == 200) {
                // Status is good, proceed
                $.each(json.message, function (k, v) { 
                    var ctsID = v.id;
                    var isPrivate = v.is_private == 0 ? "<i class=\"fas fa-eye privacy-toggle\" data-cts-id=\"" + ctsID + "\"  title=\"Not Private\"></i>" : "<i class=\"fas fa-eye-slash privacy-toggle\" data-cts-id=\"" + ctsID + "\" title=\"Private\"></i>";
                        $("#cts-items").append("<tr class=\"cts-item\" data-href=\"?page=view-correspondence&cts-id=" + ctsID + "\">\n<td>" + isPrivate + "</td>\n<td>" + ctsID + "</td>\n<td>" + v.date_entered + "</td>\n<td>" + v.subject + "</td>\n<td>" + v.status + "</td>\n<td>" + v.dept_name + "</td>\n<td>" + v.type_name + "</td>\n</tr>\n");
                });
            } else {
                // Status no good.
                $("#cts-items").append("<tr><td colspan=\"6\">" + json.message + "</td></tr>")
            }
        }
    });
</script>