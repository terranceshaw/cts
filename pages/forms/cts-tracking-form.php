<form action="" method="post" id="tracking-form" class="col" style="width: 250px; margin: 0 auto">
    <div id="logo-img"></div>
    <h2 style="text-align: center; margin-bottom: 10px">Track Correspondence</h2>
    <label for="cts-id">CTS ID</label>
    <input type="text" name="cts-id" id="cts-id">
    <input type="submit" value="Submit" class="confirm-btn" style="margin: 10px auto">
</form>

<script>
    $(document).ready(function () {
        $("#cts-id").focus();   // As usual, set focus on the field for better UX goodness.
    });
</script>