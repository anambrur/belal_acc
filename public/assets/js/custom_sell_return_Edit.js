$(document).ready(function () {
    $("#quantity , #sell_price , #sell_total_price").keyup(function () {
        var quantity = parseFloat($("#quantity").val());
        if (isNaN(quantity)) {
            quantity = 0;
        }
        var sell_price = parseFloat($("#sell_price").val());
        if (isNaN(sell_price)) {
            sell_price = 0;
        }
        var row_total_price = quantity * sell_price;
        $("#sell_total_price").val(row_total_price);
    });

    $("#jqvalidation").validate({
        rules: {
            "sell_price": "required",
            "quantity": "required",
            "product_name": "required",
            "sell_total_price": "required",
            "product_id": "required",
            customer_name: "required",
            date: "required",
        },
        messages: {
            "sell_price": "Please enter your price",
            "quantity": "Please enter quantity",
            "product_name": "Please enter Product Name",
            "sell_total_price": "Please enter Total Price",
            "product_id": "Please enter Product",
            customer_name: "Please select a Customer",
            date: "Please select a date",
        },
    });
});
