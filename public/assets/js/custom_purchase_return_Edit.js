$(document).ready(function () {
    $("#quantity , #purchase_price , #purchase_total_price").keyup(function () {
        var quantity = parseFloat($("#quantity").val());
        if (isNaN(quantity)) {
            quantity = 0;
        }
        var purchase_price = parseFloat($("#purchase_price").val());
        if (isNaN(purchase_price)) {
            purchase_price = 0;
        }
        var row_total_price = quantity * purchase_price;
        $("#purchase_total_price").val(row_total_price);
    });

    $("#jqvalidation").validate({
        rules: {
            "sell_price": "required",
            "quantity": "required",
            "product_name": "required",
            "purchase_total_price": "required",
            "product_id": "required",
            supplier_name: "required",
            date: "required",
        },
        messages: {
            "sell_price": "Please enter your price",
            "quantity": "Please enter quantity",
            "product_name": "Please enter Product Name",
            "purchase_total_price": "Please enter Total Price",
            "product_id": "Please enter Product",
            supplier_name: "Please select a Supplier",
            date: "Please select a date",
        },
    });
});
