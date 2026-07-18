$(document).ready(function () {
    $(".select").select2();
    $("#datetimepicker").datetimepicker({
        format: "Y-m-d",
        timepicker: false,
        lang: "ru",
        value: new Date(),
    });

    $("#paid_amount").click(function () {
        $(this).val("");
    });
    $("#paid_amount").keyup(function () {
        $.ajax({
            url: "/get-total-balance",
            type: "GET",
            dataType: "json",

            success: function (data) {
                var cash_in_hand = data.total_balance;
                var paid_amount = $("#paid_amount").val();
                paid_amount = parseFloat(paid_amount);
                if (paid_amount > cash_in_hand) {
                    alert("You can't pay more than cash in hand. Your amount is " + cash_in_hand);
                }
            },

            error: function (xhr, status, error) {
                console.error("Failed to fetch total balance:", error);
            },
        });
    });

    $("#due_amount, #paid_amount, #due").keyup(function () {
        var due_amount = parseFloat($("#due_amount").val());
        var paid_amount = parseFloat($("#paid_amount").val());
        if (isNaN(paid_amount)) {
            paid_amount = 0;
        }
        var due = due_amount - paid_amount;
        $("#due").val(due);
        if (paid_amount > due_amount) {
            alert("Paid amount cannot be greater than Due amount!");
            var paid_amount = $("#paid_amount").val(due_amount);
            $("#due").val(0);
        }
    });

    $("#jqvalidation").validate({
        rules: {
            supplier_name: "required",
            paymentMethod_id: "required",
            due_amount: "required",
            paid_amount: "required",
        },
        messages: {
            supplier_name: "Please enter supplier name",
            paymentMethod_id: "Please select payment method",
            due_amount: "Please enter due amount",
            paid_amount: "Please enter paid amount",
        },
    });
});
