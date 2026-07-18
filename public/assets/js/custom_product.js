$(document).ready(function () {
    // alert("ok");
    $(".select").select2();
    $("#datetimepicker").datetimepicker({
        format: "Y-m-d",
        timepicker: false,
        lang: "ru",
        value: new Date(),
    });

    $("#price").keyup(function () {
        //Convert the price to a string
        var price = parseInt($("#price").val());
        if (isNaN(price)) {
            price = '';
        }
        var priceStr = price.toString();
        // Convert each digit of the price to a letter
        var output = "";
        for (var i = 0; i < priceStr.length; i++) {
            var digit = parseInt(priceStr[i]);
            var alphabetCharacter = convertNumberToLetter(digit);
            output += alphabetCharacter;
        }
        // Display the converted output
        $("#code").val(output);
    });

    var barcodeHeight = $("#barcode").height();
    $(".barcode-text").css("top", barcodeHeight + 10);

    $("#codeAddbtn").click(function () {
        var barcodeRow = $("#barcodeRow").html();
        console.log(barcodeRow);
        $("#codeContainer").append(barcodeRow);
    });

    ///////////////
    //print barcode
    $("#printButton").click(function () {
        window.print();
    });
});

function convertNumberToLetter(number) {
    var numberToLetterMap = {
        0: "M",
        1: "N",
        2: "O",
        3: "P",
        4: "Q",
        5: "R",
        6: "S",
        7: "T",
        8: "U",
        9: "V",
    };
    return numberToLetterMap[number];
}
