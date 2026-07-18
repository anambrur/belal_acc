$(document).ready(function () {
    $(".select").select2();
    $("#datetimepicker").datetimepicker({
        format: "Y-m-d",
        timepicker: false,
        lang: "ru",
        value: new Date(),
    });
});
//validation

$("#jqvalidation").validate({
    rules: {
        customer_id: "required",
        paid_amount: "required",
        total_price: "required",
        sub_total: "required",
        discount_price: "required",
        date: "required",
    },
    messages: {
        customer_id: "Please select a Customer",
        paid_amount: "Please Give Amount",
        total_price: "Enter Total Price",
        sub_total: "Enter Price",
        discount_price: "Enter Price",
        date: "Enter a Date",
    },
});

//row delete
// function row_del(v) {
//     $(v).parent().parent().remove();
// }

//calculation
function calculate(i) {
    var qty = parseFloat($("#q_" + i).val());
    var price = parseFloat($("#p_" + i).val());
    var discount = parseFloat($("#d_" + i).val());
    if (isNaN(qty)) {
        qty = 0;
    }
    if (isNaN(price)) {
        price = 0;
    }
    var total = qty * price;
    if (isNaN(discount)) {
        discount = 0;
    }

    if (!isNaN(total) && !isNaN(discount)) {
        var discountedPrice = total - total * (discount / 100);
        $("#row_total_price" + i).val(discountedPrice.toFixed(2));
    } else {
    }

    var totalSum = 0;
    $("input[name='row_total_price[]']").each(function () {
        var value = parseFloat($(this).val());
        if (!isNaN(value)) {
            totalSum += value;
        }
    });

    $("#sub_total").val(totalSum);

    var subtotal = $("#sub_total").val();

    var discount = parseFloat($("#discount_price").val());
    if (isNaN(discount)) {
        discount = 0;
        // $("#discount_price").val(0);
    }
    var grand_total = subtotal - discount;
    $("#total_price").val(grand_total);
    var paid = parseFloat($("#paid_amount").val());
    if (isNaN(paid)) {
        paid = 0;
        // $("#paid_amount").val(0);
    }
    var due = grand_total - paid;
    $("#due").val(due);
}
//grand total calculation

$("#sub_total,#paid_amount,#discount_price").click(function () {
    $(this).val("");
});

$("#sub_total,#paid_amount,#discount_price").keyup(function () {
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
