<div class="col padded">
    <h1 class="page-title">API Testing</h1>
    <label for="test-btn">Test <code>$_SESSION</code> vars.</label>
    <input type="button" value="Click Me" id="test-btn" class="confirm-btn">
</div>

<script>
    $(document).ready(function () {
        $("#test-btn").click(function() {
            $.post("<?php echo API_TEST ?>", {

            }, function(data) {
                console.log(data);
            })
        })
    });
</script>