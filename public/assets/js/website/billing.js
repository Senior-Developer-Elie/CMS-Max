var Websites_Billing = {

    init : function(){
        this.initInlineEditAction();
        this.initDataTable();

        this.refreshStates();
    },

    initDataTable: function(){
        clientListTable = $('#website-list-table').DataTable({
            dom: 'Bfrtip',
            buttons: [],
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            fixedHeader: true,
            columnDefs: [
                {targets: [1], type: 'sortme'},
                {targets: [2], type: 'pricesort'},
            ],
        });
        
        $.fn.dataTable.ext.type.order['sortme-pre'] = function (a, b) {
            return $(a).attr('data-value');
        };
        $.fn.dataTable.ext.type.order['pricesort-pre'] = function (a, b) {
            return parseFloat($(a).text().replace("$",""));
            //return $(a).attr('data-value');
        };
    },

    initInlineEditAction: function(){

        $.fn.editable.defaults.send = "always";
        $.fn.editable.defaults.ajaxOptions = {
            type : 'POST'
        };
        $.fn.editable.defaults.url = siteUrl+"/update-website-attribute";
        $.fn.editable.defaults.mode = 'popup';
        $.fn.editable.defaults.params = function(params) {
            params._token = csrf_token;
            return params;
        };

        let prettyBillingTypeSource = allBillingTypes;
        $("a.billing-type-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                pk          : websiteId,
                source      : prettyBillingTypeSource,
                showbuttons : false,
                name        : 'billing_type',
            });
        })

        $("a.billing-type-value").on('save', function(e, params) {
            let billingType = params.newValue;
            $(this).closest('tr').attr('data-billing-type', billingType);

            Websites_Billing.initBililngAmountComponent($(this).closest('tr').find('a.billing-amount-value'));

            Websites_Billing.refreshStates();
        });

        // Init billing amount
        $("a.billing-amount-value").each(function(index, element){
            Websites_Billing.initBililngAmountComponent($(element));
        })

        $("a.billing-amount-value").on('save', function(e, params) {
            let billingAmount = params.newValue;
            $(this).closest('tr').attr('data-billing-amount', billingAmount);

            Websites_Billing.refreshStates();
        });
    },

    initBililngAmountComponent: ($element) => {

        let billingType = $element.closest('tr').attr('data-billing-type');
        if (billingType == 'chargebee') {
            websiteId = $element.closest('tr').attr('data-website-id');
            $element.editable({
                type        : 'text',
                pk          : websiteId,
                showbuttons : true,
                name        : 'billing_amount',
                display     : function( value){
                    $(this).html("$" + value);
                }
            });
        } else {
            $element.editable("destroy");
            
            let cmsMaxPrice = parseFloat($element.closest('tr').attr('data-cms-max-price'))
            $element.text("$" + (cmsMaxPrice > 0 ? cmsMaxPrice : 0));
        }
    },

    refreshStates: () => {
        let invoiceNinjaValue = 0;
        let chargebeeValue = 0;
        
        $('#website-list-table tr').each(function(index, row) {
            let billingType = $(row).closest('tr').attr('data-billing-type');
            let billingAmount = $(row).closest('tr').attr('data-billing-amount');
            let cmsMaxPrice = $(row).closest('tr').attr('data-cms-max-price');

            if (billingType == 'cms-max') {
                invoiceNinjaValue += parseFloat(cmsMaxPrice) > 0 ? parseFloat(cmsMaxPrice) : 0;
            }

            if (billingType == 'chargebee') {
                chargebeeValue += parseFloat(billingAmount) > 0 ? parseFloat(billingAmount) : 0;
            }
        });

        $(".invoice-ninja-states-value").html("$ " + invoiceNinjaValue);
        $(".chargebee-states-value").html("$ " + chargebeeValue);
    }
};

$(document).ready(function(){
    Websites_Billing.init();
})
