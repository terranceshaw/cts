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

    $("#add-type-btn").click(function() {
        var typeName = prompt("Please specify a name for the new correspondence type.");
        if (typeName !== "") {
            // if the typeName var isn't blank, proceed with the AJAX.
            $.post("./api/admin/correspondence-type-new.php", {
                "command_id": <?php echo $_SESSION['cts']['command_id'] ?>,
                "type_name": typeName
            }, function(data) {
                var json = JSON.parse(data);
                if (json['status'] == 200) {
                    // Results are good, update the list.
                    $("#correspondence-type").empty()
                    $.each(json['message'], function (k, v) { 
                        $("#correspondence-type").append("<option value=\"" + v.id + "\">" + v.name + "</option>");
                    });
                } else {

                }
            });
        }
    })

    // Add or remove options from the routing chain.
    $("#add-btn").click(function(){
        $("#available-groups option:selected").remove().appendTo($("#routing-chain"));
    })
    $("#remove-btn").click(function(){
        $("#routing-chain option:selected").remove().appendTo($("#available-groups"));
        // And also sort alphabetically to keep thingsn ice.
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
        var typeID = $(this).val()
        $.post("./api/admin/correspondence-routing-chain-list.php", {
            "type-id":typeID
        }, function(data) {
            $("#routing-chain").empty();    // Empty the list if nothing is returned.
            $("#available-groups").empty();
            if (data) {
                // $("#output").val(data);
                data = JSON.parse(data);
                $.each(data['selected-groups'], function (k, v) { 
                    $("#routing-chain").append("<option value=\"" + v.id + "\">" + v.name + "</option>")
                });
                $.each(data['available-groups'], function (k, v) { 
                    $("#available-groups").append("<option value=\"" + v.id + "\">" + v.name + "</option>")
                });
            }
        })
    })
});