$(document).ready(function () {
    $(".select").select2();
    $("#datetimepicker").datetimepicker({
        format: "Y-m-d",
        timepicker: false,
        lang: "ru",
        value: new Date(),
    });

    var base_Url = window.MyAppGlobals.base_Url;
    // var base = base_Url + `storage/`;
    var i = laram;

    let isBarcodeScanning = false;
    function handleBarcodeScanning(barcode) {
        isBarcodeScanning = true;
        $.ajax({
            url: base_Url + `getProductDataBarcode/${barcode}`,
            success: function (result) {
                isBarcodeScanning = false;

                var TableRow = `<tr class="" id="tRow">
            <td class="productimgname">
                <a class="product-img">
                    <img src="${base_Url + `storage/`}${
                    result.photo
                }" alt="product" />
                </a>
                <div class="form-group mt-3">
                    <input type="text" name="product_name[]" value="${
                        result.product_name
                    }" placeholder="Product Name">
                    <input type="hidden" name="product_id[]" value="${
                        result.id
                    }" placeholder="Product Name" class="product_name">
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="quantity[]" id='q_${i}' onkeyup='calculate(${i})'  class="quantity" value="" placeholder="0" required autocomplete="off">
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="purchase_price[]" id="p_${i}" onkeyup='calculate(${i})' class="purchase_price" value="${
                    result.price
                }" placeholder="0" required autocomplete="off">
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="total_price[]" class="total_price" id='t_${i}' value="" placeholder="0" required autocomplete="off">
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="profit[]" class="profit" id='l_${i}' onkeyup='calculate(${i})' value="25" placeholder="0" required autocomplete="off">
                </div>
            </td>
            
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="sell_price[]" class="sell_price" id='sel_price${i}'  value="" placeholder="0" required autocomplete="off">
                </div>
            </td>
            <td>
                <a class="delete-set" onclick="row_del(this)"><span><i class="fa-solid fa-xmark fa-2xl" style="color: #FF0000;"></i></span></a>
            </td>
            </tr>`;

                $("#tBody").append(TableRow);
                i += 1;
            },
        });
        $("#barcode").val("");
    }

    $(document).on("keydown", "#barcode", function (event) {
        if (event.key === "Enter") {
            let barcode = $(this).val();
            handleBarcodeScanning(barcode);
            event.preventDefault();
        }
    });

    $(document).on("submit", "#jqvalidation", function (e) {
        if (isBarcodeScanning) {
            e.preventDefault();
        }
    });

    $("#product_data").on("change", function () {
        var product_id = $(this).val();
        var product = laravelData;
        var data = product.filter((p) => p.id == product_id);
        var TableRow = `<tr class="" id="tRow">
            <td class="productimgname">
                <a class="product-img">
                    <img src="${base_Url + `storage/`}${
            data[0].photo
        }" alt="product" />
                </a>
                <div class="form-group mt-3">
                    <input type="text" name="product_name[]" value="${
                        data[0].product_name
                    }" placeholder="Product Name">
                    <input type="hidden" name="product_id[]" value="${
                        data[0].id
                    }" placeholder="Product Name" class="product_name">
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="quantity[]" id='q_${i}' onkeyup='calculate(${i})'  class="quantity" value="" placeholder="0" required autocomplete="off">
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="purchase_price[]" id="p_${i}" onkeyup='calculate(${i})' class="purchase_price" value="${
            data[0].price
        }" placeholder="0" required autocomplete="off">
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="total_price[]" class="total_price" id='t_${i}' value="" placeholder="0" required autocomplete="off">
                </div>
            </td>
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="profit[]" class="profit" id='l_${i}' onkeyup='calculate(${i})' value="25" placeholder="0" required autocomplete="off">
                </div>
            </td>
            
            <td>
                <div class="form-group mt-3">
                    <input type="text" name="sell_price[]" class="sell_price" id='sel_price${i}'  value="" placeholder="0" required autocomplete="off">
                </div>
            </td>
            <td>
                <a class="delete-set" onclick="row_del(this)"><span><i class="fa-solid fa-xmark fa-2xl" style="color: #FF0000;"></i></span></a>
            </td>
        </tr>`;

        $("#tBody").append(TableRow);
        i += 1;
    });
});

function row_del(v) {
    $(v).parent().parent().remove();
}

////calculation
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

$("#jqvalidation").validate({
    rules: {
        "sell_price[]": "required",
        "quantity[]": "required",
        "product_name[]": "required",
        "discount[]": "required",
        "total_price[]": "required",
        "product_id[]": "required",
        supplier_id: "required",
        paid_amount: "required",
        total_price: "required",
        sub_total: "required",
        discount_price: "required",
        paymentMethod_id: "required",
        accounts_id: "required",
    },
    messages: {
        "sell_price[]": "Please enter your price",
        "quantity[]": "Please enter quantity",
        "product_name[]": "Please enter Product Name",
        "discount[]": "Please enter Discount",
        "total_price[]": "Please enter Total Price",
        "product_id[]": "Please enter Product",
        supplier_id: "Please select a Customer",
        paid_amount: "Please Give Amount",
        total_price: "Enter Total Price",
        sub_total: "Enter Price",
        discount_price: "Enter Price",
        paymentMethod_id: "Please select a payment method",
        accounts_id: "Please select an account",
    },
});
