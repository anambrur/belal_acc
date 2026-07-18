$(document).ready(function () {
    var qty = [];
    $(".quantity").each(function () {
        qty.push($(this).val());
    });
    var purchase_price = [];
    $(".purchase_price").each(function () {
        purchase_price.push($(this).val());
    });
    var result = [];
    for (var i = 0; i < qty.length; i++) {
        var product = parseFloat(qty[i]) * parseFloat(purchase_price[i]);
        result.push(product);
    }
    var total_price = 0;
    result.map((d, i) => {
        total_price += d;
        $("#sub_total" + i).val(d);
    });
    

    $(".quantity ,.purchase_price ,.total_price").keyup(function () {
        var qty = [];
        $(".quantity").each(function () {
            qty.push($(this).val());
        });
        var purchase_price = [];
        $(".purchase_price").each(function () {
            purchase_price.push($(this).val());
        });
        var result = [];
        for (var i = 0; i < qty.length; i++) {
            var product = parseFloat(qty[i]) * parseFloat(purchase_price[i]);
            result.push(product);
        }
        var total_price = 0;
        result.map((d, i) => {
            total_price += d;
            $("#total_price" + i).val(d);
           
        });
        
    });
});
