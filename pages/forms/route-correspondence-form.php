<?php
    $ctsID = $_GET['cts-id'];

    if (isset($_POST['route-action'])) {
        // If we've submitted the form to take action... take action on it.
        $process = curl(CORRESPONDENCE_ROUTE, array("cts_id"=>$ctsID, "user_id"=>$session['id'], "group_id"=>$session['group_id'], "action"=>$_POST['action'], "remarks"=>$_POST['remarks']));
        if ($process['status'] !== 200) {
            print_r($process['message']);
        }
    }

    $data = curl(CORRESPONDENCE_DETAILS, array("cts_id"=>$ctsID));  // Fetch the data from the API...
    if ($data['status'] == 200) {
        // If successful, break it down into the correspondence and history items
        $details = $data['message']['correspondence'];
        $history = $data['message']['history'];
    } else {
        echo $data['message'];
    }
?><h1 class="page-title"><?php echo " <span class=\"bubble\">CTS ID: " . $details['id'] . "</span>" . $details['subject'] ?></h1>
<span class="subtitle"><strong><?php echo $details['type_name'] ?></strong> submitted on <strong><?php echo $details['date_entered'] ?></strong></span>
<a href="cts-report.php?cts-id=<?php echo $ctsID ?>" target="_blank" id="print-report" style="margin-left: auto"><i class="fas fa-print"></i> Print Report</a>
<div class="col">
    <ul class="routing-chain">
        <?php
            foreach ($details['routing_chain'] as $stop) {
                $name = $stop['name'];
                if (in_array($stop['id'], $details['completed_chops'])) {
                    echo "<li class=\"routing-stop routing-complete\"><i class=\"fas fa-check-circle\"></i> $name</li>";
                } else {
                    echo "<li class=\"routing-stop\"><i class=\"fas fa-circle\"></i> $name</li>";
                }
            }
        ?>
    </ul>
    <table style="margin-top: 10px">
        <thead>
            <th>User</th>
            <th>Action Taken</th>
            <th>Date</th>
            <th>Remarks</th>
        </thead>
        <tbody>
            <?php
                foreach ($history as $item) {
                    $user = $item['display_name'];
                    $actionTaken = $item['action_taken'];
                    $date = $item['date_received'];
                    $remarks = $item['remarks'];
                    echo "<tr>\n";
                    echo "<td>$user</td>";
                    echo "<td>$actionTaken</td>";
                    echo "<td>$date</td>";
                    echo "<td style=\"max-width: 350px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-right: 10px\" title=\"$remarks\">$remarks</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
    <form action="" method="post" class="row gap" style="margin-top: 20px; width: 100%">
        <div class="col" style="width: 100%">
            <div class="row gap">
                <div class="col" style="width: 100%">
                    <label for="user">User</label>
                    <input type="text" name="username" id="user" value="<?php echo LOGGED_IN_DISPLAY_NAME ?>" readonly>
                </div>
                <div class="col">
                    <label for="action">Action</label>
                    <select name="action" id="action" style="width: 200px">
                        <option value="Comment">Comment</option>
                        <option value="Reviewed">Review</option>
                        <option value="Processed">Process</option>
                        <?php
                            $routingChain = $details['routing_chain'];
                            if ($session['group_id'] == end($routingChain)['id']) {
                                // Only the final group in the routing chain can approve.
                                echo "<option value=\"Approved\">Approve</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <label for="remarks">Remarks</label>
            <textarea name="remarks" id="" cols="remarks" rows="10"></textarea>
        </div>
        <div class="col">
            <input type="hidden" name="route-action" value="true">
            <input type="submit" class="confirm-btn" value="Submit">
            <input type="reset" class="cancel-btn" value="Cancel">
        </div>
    </form>
</div>

<?php
    // echo "<pre>";
    // print_r($details['routing_chain']);
    // echo "</pre>";
?>