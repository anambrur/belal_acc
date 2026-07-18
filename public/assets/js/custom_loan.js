$(document).ready(function () {
    $(".select").select2();
    $("#datetimepicker").datetimepicker({
        format: "Y-m-d",
        timepicker: false,
        lang: "ru",
        value: new Date(),
    });

    $("#jqvalidation").validate({
        rules: {
            loan_holder: "required",
            amount: "required",
            date: "required",
        },
        messages: {
            loan_holder: "Please select a Loan Holder Name",
            amount: "Please Give Amount",
            date: "Enter date",
        },
    });
});
