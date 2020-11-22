var Credit_Card_Processing = {

    tsysDataTable : false,
    pendingWebsitesTable : false,
    archivedWebsitesTable : false,

    init: function(){

        this.initDataTable();
        this.initInlineEditing();
        this.initArchiveActions();
        this.initManualEntryAction();
    },

    initDataTable: function(){
        Credit_Card_Processing.tsysDataTable = $('#credit-card-processing-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: ([0, 2, 3, 4, 5]), type: 'sortme'},
            ],
            fixedHeader: true
        });
        $.fn.dataTable.ext.type.order['sortme-pre'] = function (a, b) {
            return $(a).attr('data-value');
        };

        Credit_Card_Processing.pendingWebsitesTable = $('#credit-card-processing-pending-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [1, 2], type: 'sortme'},
            ],
            fixedHeader: true,
        });
        Credit_Card_Processing.archivedWebsitesTable = $('#credit-card-processing-archived-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [1, 2], type: 'sortme'},
            ],
            fixedHeader: true,
        });
        $.fn.dataTable.ext.type.order['sortme-comment'] = function (a, b) {
            return $(a).attr('data-value');
        };
    },

    initInlineEditing: function(){
        $.fn.editable.defaults.send = "always";
        $.fn.editable.defaults.ajaxOptions = {
            type : 'POST'
        };
        $.fn.editable.defaults.mode = 'popup';
        $.fn.editable.defaults.params = function(params) {
            params._token = csrf_token;
            return params;
        };

        $("a.mid-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                url         : siteUrl+"/update-website-attribute",
                type        : 'textarea',
                pk          : websiteId,
                name        : 'mid',
            });
        })

        $("a.control-scan-user-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                url         : siteUrl+"/update-website-attribute",
                type        : 'textarea',
                pk          : websiteId,
                name        : 'control_scan_user',
            });
        })

        $("a.control-scan-pass-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                url         : siteUrl+"/update-website-attribute",
                type        : 'textarea',
                pk          : websiteId,
                name        : 'control_scan_pass',
            });
        })

        $("a.control-scan-renewal-date-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                url         : siteUrl+"/update-website-attribute",
                type        : 'date',
                pk          : websiteId,
                name        : 'control_scan_renewal_date',
                display     : function(value){
                    if( value == null || value == undefined || value == '' )
                    {
                        $(this).html('');
                    }
                    else{
                        var date = new Date(value);
                        $(this).html(((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());
                    }
                }
            });
        })

        $("a.credit-card-notes").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                url         : siteUrl+"/update-website-attribute",
                type        : 'textarea',
                pk          : websiteId,
                name        : 'credit_card_notes',
            });
        })
    
        $("a.manual-mid-value").each(function(index, element){
            creditCardProcessingId = $(element).closest('tr').attr('data-credit-card-processing-id');
            $(element).editable({
                url         : siteUrl+"/credit-card-processing/update-attribute",
                type        : 'textarea',
                pk          : creditCardProcessingId,
                name        : 'mid',
            });
        })

        $("a.manual-control-scan-user-value").each(function(index, element){
            creditCardProcessingId = $(element).closest('tr').attr('data-credit-card-processing-id');
            $(element).editable({
                url         : siteUrl+"/credit-card-processing/update-attribute",
                type        : 'textarea',
                pk          : creditCardProcessingId,
                name        : 'control_scan_user',
            });
        })

        $("a.manual-control-scan-pass-value").each(function(index, element){
            creditCardProcessingId = $(element).closest('tr').attr('data-credit-card-processing-id');
            $(element).editable({
                url         : siteUrl+"/credit-card-processing/update-attribute",
                type        : 'textarea',
                pk          : creditCardProcessingId,
                name        : 'control_scan_pass',
            });
        })

        $("a.manual-control-scan-renewal-date-value").each(function(index, element){
            creditCardProcessingId = $(element).closest('tr').attr('data-credit-card-processing-id');
            $(element).editable({
                url         : siteUrl+"/credit-card-processing/update-attribute",
                type        : 'date',
                pk          : creditCardProcessingId,
                name        : 'control_scan_renewal_date',
                display     : function(value){
                    if( value == null || value == undefined || value == '' )
                    {
                        $(this).html('');
                    }
                    else{
                        var date = new Date(value);
                        $(this).html(((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());
                    }
                }
            });
        })

    },

    initArchiveActions: function(){
        $(document).on('click', '.archive-btn', function(){
            websiteId = $(this).closest('tr').attr('data-website-id');
            $("#archive-website-modal").attr('data-website-id', websiteId);
            $("#add-website-modal").modal('hide');
            $("#archive-website-modal").modal('show');
        });

        $("#archive-website-modal .confirm-btn").click(function(){
            websiteId = $("#archive-website-modal").attr('data-website-id');
            $.ajax({
                type : 'POST',
                url : siteUrl + '/credit-card-processing/archive-website',
                data : {
                    _token : csrf_token,
                    websiteId
                },
                success: function(data){
                    if( data.status == 'success' ){
                        location.reload();
                    }
                }
            });
        });

        $(document).on('click', '.unarchive-btn', function(){
            websiteId = $(this).closest('tr').attr('data-website-id');
            $.ajax({
                type : 'POST',
                url : siteUrl + '/credit-card-processing/un-archive-website',
                data : {
                    _token : csrf_token,
                    websiteId
                },
                success: function(data){
                    if( data.status == 'success' ){
                        location.reload();
                    }
                }
            });
        });
    },

    initManualEntryAction: function() {

        $('#add-credit-card-processing-modal .website-payment-gateway-list').select2()

        $("#add-manual-entry-button").click(function(){
            $("#add-credit-card-processing-modal #company-name").val('');
            $("#add-credit-card-processing-modal .website-payment-gateway-list").val([]).trigger('change');
            $("#add-credit-card-processing-modal").modal('show');
        })

        // Confirm save action
        $("#add-credit-card-processing-modal .confirm-btn").click(function(){
            let ajaxData = {
                _token: csrf_token,
                company_name : $("#add-credit-card-processing-modal #company-name").val(),
                payment_gateway : $("#add-credit-card-processing-modal .website-payment-gateway-list").val()
            }

            if (! Credit_Card_Processing.validateSaveCreditCardProcessing(ajaxData)) {
                return;
            }

            $.ajax({
                type: "POST",
                data: ajaxData,
                url: siteUrl + '/credit-card-processing/store',
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();
                    }
                }
            })
        })
    
        $('.delete-manual-entry-btn').click(function(){
            let creditCardProcessingId = $(this).closest('tr').attr('data-credit-card-processing-id');
            
            if (confirm("Are you sure you want to remove this manual entry?")) {
                $.ajax({
                    type: "POST",
                    url: siteUrl + '/credit-card-processing/destroy',
                    data: {
                        _token: csrf_token,
                        creditCardProcessingId
                    },
                    success: function() {
                        location.reload();
                    }
                })
            }
        })
    },

    validateSaveCreditCardProcessing: function(ajaxData) {
        
        if (ajaxData.company_name == '') {
            $("#add-credit-card-processing-modal #company-name").focus();
            return false;
        }

        if (ajaxData.payment_gateway.length == 0) {
            $("#add-credit-card-processing-modal .website-payment-gateway-list").focus()
            return false;
        }

        return true;
    }
};

$(document).ready(function(){
    Credit_Card_Processing.init();
})
