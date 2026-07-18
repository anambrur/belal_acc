$(document).ready(function () {
    alert("error");
    alert('ok')
    var qty = parseFloat($("#quantity").val());
    var sell_price = parseFloat($("#sell_price").val());
    var total_price = qty * sell_price;
    $("#sell_total_price").val(total_price);
    $("#sub_total").val(total_price);

    //////////////////
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

    $("#quantity , #sell_price").keyup(function () {
        var qty = parseFloat($("#quantity").val());
        var sell_price = parseFloat($("#sell_price").val());
        if (isNaN(qty)) {
            qty = 0;
        }
        if (isNaN(sell_price)) {
            sell_price = 0;
        }
        var total_price = qty * sell_price;
        $("#sell_total_price").val(total_price);
        $("#sub_total").val(total_price);

        /////////////////
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

    $("#jqvalidation").validate({
        rules: {
            customer_name: "required",
            date: "required",
            product_name: "required",
            quantity: "required",
            sell_price: "required",
            sell_total_price: "required",
            sub_total: "required",
            discount_price: "required",
            total_price: "required",
            paid_amount: "required",
        },
        messages: {
            customer_name: "Please Enter Customer Name",
            date: "Please Enter Date",
            product_name: "Please Enter Product Name",
            quantity: "Please Enter Quantity",
            sell_price: "Please Enter Sell Price",
            sell_total_price: "Please Enter Total Price",
            sub_total: "Please Enter Sub Total",
            discount_price: "Please Enter Discount Price",
            total_price: "Please Enter Total Price",
            paid_amount: "Please Enter Paid Amount",
        },
    });
});
