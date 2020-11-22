var Manage_Sender = {

    init: function(){

        Manage_Sender.initDatatable();
        Manage_Sender.initInlineEditableForSender();
    },

    initDatatable: function(){

        $('#manage-sender-table').DataTable({
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
        $('#manage-sender-arrchived-table').DataTable({
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

    initInlineEditableForSender: function(){
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

        $("a.sender-name-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'text',
                pk          : websiteId,
                name        : 'mailgun_sender',
            });
        })
    },
};

$(document).ready(function(){
    Manage_Sender.init();
});
