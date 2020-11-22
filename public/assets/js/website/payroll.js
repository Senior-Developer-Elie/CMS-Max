var Websites_Payroll = {

    activeWebsitesTable: false,
    archivedWebsitesTable: false,

    init : function(){
        this.initDataTable();
        this.initInlineEditableForNotes();
        this.initArchiveActions();
    },

    initDataTable: function(){
        Websites_Payroll.activeWebsitesTable = $('#website-list-table').DataTable({
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
        Websites_Payroll.archivedWebsitesTable = $('#archived-website-list-table').DataTable({
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

    initInlineEditableForNotes: function(){
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

        $("a.marketing-notes").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'textarea',
                pk          : websiteId,
                name        : 'marketing_notes',
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
                url : siteUrl + '/payroll-archive-website',
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
                url : siteUrl + '/payroll-un-archive-website',
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
    }
};

$(document).ready(function(){
    Websites_Payroll.init();
})
