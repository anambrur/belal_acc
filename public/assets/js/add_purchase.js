$(document).ready(function () {
    $(".select").select2();
    $("#datetimepicker").datetimepicker({
        format: "Y-m-d",
        timepicker: false,
        lang: "ru",
        value: new Date(),
    });


    var base_Url = window.MyAppGlobals.base_Url;
    var i = 1;
    $("#product_data").change(function () {
        var product_id = $(this).val();
        var product = laravelData;
        var data = product.filter((p) => p.id == product_id);
        var TableRow = `<tr class="" id="tRow">
            <td class="productimgname">
                <a class="product-img">
                    <img src="${base_Url}${data[0].photo}" alt="product" />
                </a>
                <div class="form-group mt-3">
                    <input type="text" name="product_name[]" value="${data[0].product_name}" placeholder="Product Name">
                    <input type="hidden" name="product_id[]" value="${data[0].id}" placeholder="Product Name" class="product_name">
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="quantity[]" id='q_${i}' onkeyup='calculate(${i})'  class="quantity" value="" placeholder="0" required>
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="purchase_price[]" id="p_${i}" onkeyup='calculate(${i})' class="purchase_price" value="${data[0].price}" placeholder="0" required>
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="total_price[]" class="total_price" id='t_${i}' value="" placeholder="0" required>
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="profit[]" class="profit" id='l_${i}' onkeyup='calculate(${i})' value="25" placeholder="0" required>
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="code[]" id='co_${i}' class="code" value="" placeholder="Code" required>
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="sell_price[]" class="sell_price" id='sel_price${i}'  value="" placeholder="0" required>
                </div>
            </td>
            <td>
                <a class="delete-set" onclick="row_del(this)"><span><i class="fa-solid fa-xmark fa-2xl" style="color: #FF0000;"></i></span></a>
            </td>
        </tr>`;

        $("#tBody").append(TableRow);
        i += 1;
        /////////////////////////////////////////////////////////
        // Convert the price to a string
        var price = parseInt(data[0].price);
        var priceStr = price.toString();
        console.log(priceStr);

        // Convert each digit of the price to a letter
        var output = "";
        for (var i = 0; i < priceStr.length; i++) {
            var digit = parseInt(priceStr[i]);
            var alphabetCharacter = convertNumberToLetter(digit);
            output += alphabetCharacter;
        }

        // Display the converted output
        console.log("Output:", output);
        $("#co_" + i).val(output);
        $(".code").val(output);

        //////////////////////////////////////////
        inivalidation()
    });
});

////////////////////////////
function convertNumberToLetter(number) {
    var numberToLetterMap = {
        0: "A",
        1: "B",
        2: "C",
        3: "D",
        4: "E",
        5: "F",
        6: "G",
        7: "H",
        8: "I",
        9: "J",
    };
    return numberToLetterMap[number];
}
///////////////////////////

//validation
function inivalidation() {
    $("#jqvalidation").validate({
        rules: {
            supplier_id: "required",
            product_id: "required",
            "product_name[]": "required",
            "quantity[]": "required",
            "purchase_price[]": "required",
            "total_price[]": "required",
            "profit[]": "required",
            "code[]": "required",
            "sell_price[]": "required",
            total_price: "required",
            paid_amount: "required",
            sub_total: "required",
            discount_price: "required",
        },
        messages: {
            supplier_id: "Please enter Supplier",
            product_id: "Please enter Product",
            "product_name[]": "Enter Product Name",
            "quantity[]": "Enter Quantity",
            "purchase_price[]": "Enter Purchase Price",
            "total_price[]": "Enter Total Price",
            "profit[]": "Enter Profit",
            "code[]": "Enter Code",
            "sell_price[]": "Enter Sell Price",
            total_price: "Please Enter Total Price",
            paid_amount: "Please Enter Amount",
            sub_total: "Please Enter Amount",
            discount_price: "Please Enter Amount",
        },
    });
}

///////////////////////
//delete Row
function row_del(v) {
    $(v).parent().parent().remove();
}

//calculation
function calculate(i) {
    var qty = parseFloat($("#q_" + i).val());
    var price = parseFloat($("#p_" + i).val());
    var profit = parseFloat($("#l_" + i).val());
    var total = qty * price;
    $("#t_" + i).val(total);
    if (profit !== "") {
        var profit = (price * profit) / 100;
        var sell_price = price + profit;
        function adjustPrice(price) {
            return Math.ceil(price / 5) * 5;
        }
        var adjustedPrice = adjustPrice(sell_price);
        $("#sel_price" + i).val(adjustedPrice);
    } else {
        // alert('error')
    }
    //

    var totalSum = 0;
    $("input[name='total_price[]']").each(function () {
        var value = parseFloat($(this).val());
        if (!isNaN(value)) {
            totalSum += value;
        }
    });
    // console.log(totalSum)
    $("#sub_total").val(totalSum);

    //grand total calculation
    var subtotal = $("#sub_total").val();

    var discount = parseFloat($("#discount_price").val());
    if (isNaN(discount)) {
        discount = 0;
        $("#discount_price").val(0);
    }
    var grand_total = subtotal - discount;
    $("#total_price").val(grand_total);
    var paid = parseFloat($("#paid_amount").val());
    if (isNaN(paid)) {
        paid = 0;
        $("#paid_amount").val(0);
    }
    var due = grand_total - paid;
    $("#due").val(due);

    
}

$("#paid_amount ,#discount_price").keyup(function () {
    var total = $("#sub_total").val();

    var discount = parseFloat($("#discount_price").val());
    if (isNaN(discount)) {
        discount = 0;
        $("#discount_price").val(0);
    }
    var grand_total = total - discount;
    $("#total_price").val(grand_total);
    var paid = parseFloat($("#paid_amount").val());
    if (isNaN(paid)) {
        paid = 0;
        $("#paid_amount").val(0);
    }
    var due = grand_total - paid;
    $("#due").val(due);
});
