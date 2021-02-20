var Websites_Social_Media = {

    activeWebsitesTable: false,
    archivedWebsitesTable: false,

    init : function(){
        this.initDataTable();
        this.initInlineEditableForNotes();
        this.initArchiveActions();
        this.initTooltip();
    },

    initTooltip: function() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    initDataTable: function(){
        Websites_Social_Media.activeWebsitesTable = $('#website-list-table').DataTable({
            "order"     : [[ 3, "desc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [1, 3], type: 'sortme'},
            ],
            fixedHeader: true,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var floatConversion = function ( i ) {
                    if( i.toString().startsWith("<span") ) {
                        value = parseFloat($(i).attr('data-value'));
                        if( value < 0 )
                            return 0;
                        return value;
                    }
                    else
                        return 0;
                };

                for( let i = 3; i <= 3; i++ ) {
                    // Total over this page
                    let columnData = api
                    .column( i, { page: 'current'} )
                    .data();

                    let pageTotal = 0;

                    for( j = 0; j < columnData.length; j++ ){
                        pageTotal += floatConversion(columnData[j]);
                    }

                    // Update footer
                    $( api.column( i ).footer() ).html(
                        '$' + Math.round(pageTotal).toLocaleString()
                    );
                }
            }
        });
        Websites_Social_Media.archivedWebsitesTable = $('#archived-website-list-table').DataTable({
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
            return parseFloat($(a).attr('data-value'));
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

        $("a.social-media-notes").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'textarea',
                pk          : websiteId,
                name        : 'social_media_notes',
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
                url : siteUrl + '/social-media/archive-website',
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
                url : siteUrl + '/social-media/un-archive-website',
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
    Websites_Social_Media.init();
})
