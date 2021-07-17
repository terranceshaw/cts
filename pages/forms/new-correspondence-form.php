<form action="" method="post" class="row gap" id="new-correspondence-form" autocomplete="off">
    <div class="col" style="flex-grow: 1">
        <div class="row gap" style="flex-grow: 1">
            <div class="col" style="width: 50%">
                <label for="originator-department">Originating Department <i data-tooltip="Contact a CTS administrator to modify this list." class="fas fa-question-circle tooltip"></i></label>
                <select name="originator-department" id="originator-department">
                    <?php listGenerator(DEPARTMENT_LIST, PERMISSIONS) ?>
                </select>
                <label for="originator-name">Originator Name</label>
                <input type="text" name="originator-name" id="originator-name">
                <label for="subject">Subject</label>
                <input type="text" name="subject" id="subject">
                <label for="correspondence-type">Correspondence Type <i data-tooltip="Contact a CTS administrator to modify this list." class="fas fa-question-circle tooltip"></i></label>
                <select name="correspondence-type" id="correspondence-type">
                    <?php listGenerator(CORRESPONDENCE_TYPES_LIST, PERMISSIONS) ?>
                </select>
                <label for="private-correspondence" class="checkbox-label"><input type="checkbox" name="private-correspondence" id="private-correspondence" checked> Private Correspondence <i data-tooltip="Private correspondence cannot be tracked by those outside of the routing chain." class="fas fa-question-circle tooltip"></i></label>
            </div>
            <div class="col" style="width: 50%">
                <label for="requestee-contact">Requestee Contact</label>
                <input type="text" name="requestee-contact" id="requestee-contact" placeholder="(e.g., J-DIAL, HYDRA, etc.)">
                <div class="row gap">
                    <div class="col" style="width: 125px">
                        <label for="requestee-rank">Requestee Rank/Rate</label>
                        <input type="text" name="requestee-rank" id="requestee-rank">
                    </div>
                    <div class="col" style="flex: 1 0 auto">
                        <label for="requestee-name">Requestee Name</label>
                        <input type="text" name="requestee-name" id="requestee-name">
                    </div>
                </div>
                <label for="priority">Priority</label>
                <select name="priority" id="priority">
                    <?php listGenerator(CORRESPONDENCE_PRIORITY_LIST, PERMISSIONS) ?>
                </select>
                <label for="return-date">Requested Return Date</label>
                <input type="date" name="return-date" id="return-date">
            </div>
        </div>
    <div class="col">
        <label for="remarks">Remarks</label>
        <textarea name="remarks" id="remarks" cols="30" rows="10"></textarea>
    </div>
    </div>

    <div class="col">
        <input class="confirm-btn" type="submit" value="Submit" style="margin: 5px 0">
        <input class="cancel-btn" type="reset" value="Reset" style="margin: 5px 0">
    </div>
</form>