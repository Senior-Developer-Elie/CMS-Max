var Websites_Billing = {

    init : function(){
        this.initDataTable();
        this.initInlineEditAction();
    },

    initDataTable: function(){
        console.log($(window).height())
        clientListTable = $('#website-list-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ],
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            fixedHeader: true,
            columnDefs: [
                {targets: ([1]), type: 'sortme'},
            ],
        });
        
        $.fn.dataTable.ext.type.order['sortme-comment'] = function (a, b) {
            return $(a).attr('data-value');
        };
    },

    initInlineEditAction: function(){
        //X Editable Options
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
    },
};

$(document).ready(function(){
    Websites_Billing.init();
})
