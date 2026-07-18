$(document).ready(function(){
    $(".select").select2();
    $("#datetimepicker").datetimepicker({
        format: "Y-m-d",
        timepicker: false,
        lang: "ru",
        value: new Date(),
    });
})
$("#jqvalidation").validate({
    rules: {
        "expense_categories": "required",
        "date": "required",
        "amount": "required",
        
    },
    messages: {
        "expense_categories": "Please enter expense category",
        "date": "Please enter a date",
        "amount": "Please enter amount",
        
    },
});