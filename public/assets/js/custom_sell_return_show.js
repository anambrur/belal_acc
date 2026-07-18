$(document).ready(function () {
    var qty = [];
    $(".quantity").each(function () {
        qty.push($(this).val());
    });
    var sell_price = [];
    $(".sell_price").each(function () {
        sell_price.push($(this).val());
    });
    var result = [];
    for (var i = 0; i < qty.length; i++) {
        var product = parseFloat(qty[i]) * parseFloat(sell_price[i]);
        result.push(product);
    }
    var total_price = 0;
    result.map((d, i) => {
        total_price += d;
        $("#sub_total" + i).val(d);
    });
    $("#sub_total").val(total_price);
    $("#paid_amount ,#discount_price").keyup(function () {
        var total = $("#sub_total").val();

        var discount = parseFloat($("#discount_price").val());
        if (isNaN(discount)) {
            discount = 0;
        }
        var grand_total = total - discount;

        $("#total_price").val(grand_total);
        var paid = parseFloat($("#paid_amount").val());
        if (isNaN(paid)) {
            paid = 0;
        }
        var due = grand_total - paid;
        $("#due").val(due);
    });

    $(".quantity ,.sell_price ,.total_price").keyup(function () {
        var qty = [];
        $(".quantity").each(function () {
            qty.push($(this).val());
        });
        var sell_price = [];
        $(".sell_price").each(function () {
            sell_price.push($(this).val());
        });
        var result = [];
        for (var i = 0; i < qty.length; i++) {
            var product = parseFloat(qty[i]) * parseFloat(sell_price[i]);
            result.push(product);
        }
        var total_price = 0;
        result.map((d, i) => {
            total_price += d;
            $("#total_price" + i).val(d);
            // console.log(i);
            // console.log(d);
        });
        $("#sub_total").val(total_price);
        $("#paid_amount ,#discount_price").keyup(function () {
            var total = $("#sub_total").val();

            var discount = parseFloat($("#discount_price").val());
            if (isNaN(discount)) {
                discount = 0;
                // $("#discount_price").val(0);
            }
            var grand_total = total - discount;
            $("#total_price").val(grand_total);
            var paid = parseFloat($("#paid_amount").val());
            if (isNaN(paid)) {
                paid = 0;
                // $("#paid_amount").val(0);
            }
            var due = grand_total - paid;
            $("#due").val(due);
        });
    });
});
