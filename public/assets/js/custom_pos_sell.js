/**
 * Sell create/edit helpers
 * Formula: grandTotal = max(subTotal - discount, 0)
 *          due        = grandTotal - paid
 */
(function ($) {
    "use strict";

    function toNumber(value) {
        var n = parseFloat(String(value).replace(/,/g, ""));
        return Number.isFinite(n) ? n : 0;
    }

    function money(value) {
        return toNumber(value).toFixed(2);
    }

    function readField(selector) {
        return toNumber($(selector).val());
    }

    function writeField(selector, value) {
        $(selector).val(money(value));
    }

    /**
     * Recalculate grand total + due from summary fields.
     */
    function recalculateTotals() {
        var subTotal = readField("#sub_total");
        var discount = readField("#discount_price");
        var paid = readField("#paid_amount");

        var grandTotal = Math.max(subTotal - discount, 0);
        var due = grandTotal - paid;

        writeField("#total_price", grandTotal);
        writeField("#due", due);

        return {
            subTotal: subTotal,
            discount: discount,
            grandTotal: grandTotal,
            paid: paid,
            due: due,
        };
    }

    /**
     * Line-item row calculation (legacy POS rows, if present).
     * Row discount (#d_{i}) is treated as a percentage.
     */
    window.calculate = function (rowIndex) {
        var qty = toNumber($("#q_" + rowIndex).val());
        var price = toNumber($("#p_" + rowIndex).val());
        var rowDiscountPct = toNumber($("#d_" + rowIndex).val());

        var lineTotal = qty * price;
        var discounted = lineTotal - lineTotal * (rowDiscountPct / 100);

        $("#row_total_price" + rowIndex).val(money(discounted));

        var subTotal = 0;
        $("input[name='row_total_price[]']").each(function () {
            subTotal += toNumber($(this).val());
        });

        writeField("#sub_total", subTotal);
        recalculateTotals();
    };

    function initDatePicker() {
        var $dateInput = $("#datetimepicker");
        if (!$dateInput.length || typeof $dateInput.datetimepicker !== "function") {
            return;
        }

        var options = {
            format: "Y-m-d",
            timepicker: false,
            lang: "en",
            scrollInput: false,
        };

        // Create pages: default today. Edit pages: keep existing value.
        if (!$dateInput.val()) {
            options.value = new Date();
        }

        $dateInput.datetimepicker(options);
    }

    function initValidation() {
        var $form = $("#jqvalidation");
        if (!$form.length || typeof $form.validate !== "function") {
            return;
        }

        $form.validate({
            rules: {
                customer_id: "required",
                date: "required",
                accounts_id: "required",
                sub_total: "required",
                discount_price: "required",
                total_price: "required",
                paid_amount: "required",
            },
            messages: {
                customer_id: "Please select a customer",
                date: "Please choose a date",
                accounts_id: "Please select an account",
                sub_total: "Enter sub total",
                discount_price: "Enter discount (use 0 if none)",
                total_price: "Enter total price",
                paid_amount: "Enter paid amount (use 0 if unpaid)",
            },
        });
    }

    function bindAmountInputs() {
        $(document).on(
            "input change",
            "#sub_total, #discount_price, #paid_amount",
            function () {
                recalculateTotals();
            }
        );
    }

    $(function () {
        if ($(".select").length && typeof $.fn.select2 === "function") {
            $(".select").select2();
        }

        initDatePicker();
        initValidation();
        bindAmountInputs();
        recalculateTotals();
    });

    // Expose for debugging / external calls
    window.SellCalculator = {
        recalculateTotals: recalculateTotals,
        toNumber: toNumber,
        money: money,
    };
})(jQuery);
