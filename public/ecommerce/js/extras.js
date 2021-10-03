$(document).ready(function () {
    $("input[name=product_var]").change( function(e) {
        // var table = document.getElementById(tableVarian);
        // var rowCount = table.rows.length;
        // table.deleteRow(rowCount -1);

        var var_id = this.value;
        console.log(var_id);
        $.ajax({
            type: 'GET',
            url: "/product/variant/" + var_id,
            data: { var: var_id },
            dataType: 'JSON',
            success: function(res){
                showVariantInfo(res);
            }
        });
    });
});

function showVariantInfo(info) {
    $('#tableVarian').show();
    $('#var_stock').text(info[0].stock);
    $('#var_weight').text(info[0].weight);
    $('#var_price').text(info[0].price);
}

function toggleSearch() {
    if (document.getElementById("myOverlay").style.display == "none") {
        $("#myOverlay").fadeIn(400);
        document.getElementById("myOverlay").style.display = "block";
    } else {
        $("#myOverlay").fadeOut(400);
        document.getElementById("myOverlay").style.display = "none";
    }
}

$('.nav li a').click(function() {

    $('.nav li.active').removeClass('active');

    var $parent = $(this).parent();
    $parent.addClass('active');
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

