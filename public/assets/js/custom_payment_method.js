$(document).ready(function () {
    var initialId = 1;
    getPayId(initialId);
});
var base_Url = window.MyAppGlobals.base_Url;

function getPayId(id) {
    $.ajax({
        url: base_Url + `/payment-method-edit/${id}`,
        success: function (result) {
            $("#payment_method").val(result.payment_method);
            var nId = result.id;
            $("#paymentMethodForm").attr(
                "action",
                `{{ route('paymentMethod.update', ':nId') }}`.replace(
                    ":nId",
                    nId
                )
            );
        },
    });
}
