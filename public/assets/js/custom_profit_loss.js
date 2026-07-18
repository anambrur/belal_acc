$(document).ready(function () {
    $(".select").select2();
    $("#datetimepicker").datetimepicker({
        format: "Y-m-d",
        timepicker: false,
        lang: "ru",
        // value: new Date(),
    });
    $("#datetimepickerto").datetimepicker({
        format: "Y-m-d",
        timepicker: false,
        lang: "ru",
        // value: new Date(),
    });
});
