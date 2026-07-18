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
    $("#invoice_number").keyup(function () {
        // alert("ok");
        var invoice_number = $(this).val();
        if (invoice_number.length > 0) {
            // console.log(invoice_number);
            $.ajax({
                url: base_Url + `sellsReturnData/${invoice_number}`,
                success: function (result) {
                    if (result.length === 0) {
                        $("#tBody").empty();
                        calculate();
                    } else {
                        // console.log(result)
                        $("#customer_id").val(result[0].customer_id);
                        $("#customer_name").val(
                            result[0].customer.customer_name
                        );
                        $.map(result, function (item, index) {
                            var TableRow = `<tr class="" id="tRow">
             <td class="productimgname">
                 <a class="product-img">
                     <img src="${base_Url + `storage/`}${item.product.photo}" alt="product" />
                 </a>
                 <div class="form-group mt-3">
                     <input type="text" name="product_name[]" value="${item.product.product_name}" placeholder="Product Name">
                     <input type="hidden" name="product_id[]" value="${item.product_id}" placeholder="Product Name">
                 </div>
             </td>
             <td>
                 <div class="form-group mt-3">
                     <input type="text" name="quantity[]" id='q_${i}' onkeyup='calculate(${i})'  class="text-end quantity" value="${item.quantity}" placeholder="0">
                 </div>
             </td>
             <td>
                 <div class="form-group mt-3">
                     <input type="text" name="sell_price[]" id="p_${i}" onkeyup='calculate(${i})' value="${item.sell_price}" class="text-end price"  placeholder="0">
                 </div>
             </td>
             <td>
                 <div class="form-group mt-3">
                     <input type="text" name="row_total_price[]" class="text-end total_price" onkeyup='calculate(${i})'  id='t_${i}' value="" placeholder="0">
                 </div>
             </td>

             <td>
                 <a class="delete-set" onclick="row_del(this)"><span><i class="fa-solid fa-xmark fa-2xl" style="color: #FF0000;"></i></span></a>
             </td>
         </tr>`;
                            $("#tBody").append(TableRow);
                            calculate(i);
                            i += 1;
                        });
                    }
                },
            });
        }
    });
});
function row_del(v) {
    $(v).parent().parent().remove();
}

//calculation
function calculate(i) {
    var qty = parseFloat($("#q_" + i).val());
    var price = parseFloat($("#p_" + i).val());
    if (isNaN(qty)) {
        qty = 0;
    }
    if (isNaN(price)) {
        price = 0;
    }
    var total = qty * price;
    $("#t_" + i).val(total);

    var totalSum = 0;
    $("input[name='row_total_price[]']").each(function () {
        var value = parseFloat($(this).val());
        if (!isNaN(value)) {
            totalSum += value;
        }
    });
    // console.log(totalSum)
    $("#sub_total").val(totalSum);
    ////////////////////////////

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

    ///////////////////////////
}

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
        "sell_price[]": "required",
        "quantity[]": "required",
        "product_name[]": "required",
        "discount[]": "required",
        "total_price[]": "required",
        "product_id[]": "required",

        customer_id: "required",
        paid_amount: "required",
        product_id: "required",
        total_price: "required",
        sub_total: "required",
        discount_price: "required",
    },
    messages: {
        "sell_price[]": "Please enter your price",
        "quantity[]": "Please enter quantity",
        "product_name[]": "Please enter Product Name",
        "discount[]": "Please enter Discount",
        "total_price[]": "Please enter Total Price",
        "product_id[]": "Please enter Product",
        customer_id: "Please select a Customer",
        paid_amount: "Please Give Amount",
        product_id: "Please select a Product",
        total_price: "Enter Total Price",
        sub_total: "Enter Price",
        discount_price: "Enter Price",
    },
});
