<h1 class="page-title">Trouble Ticket</h1>
<span class="subtitle">Submit a new trouble ticket or view the status of existing ones.</span>
<form action="" method="post" class="row gap">
    <div class="col" style="width: 100%">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo $session['username'] ?>" readonly>
        <label for="subject">Brief description of the problem</label>
        <input type="text" name="subject" id="subject">
        <label for="problem">Problem</label>
        <textarea name="problem" id="problem" cols="30" rows="10"></textarea>
    </div>
    <div class="col">
        <input type="submit" value="Submit" class="confirm-btn">
        <input type="reset" value="Reset" class="cancel-btn">
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#subject").focus();  // Give the first text field the focus.
    });
</script>