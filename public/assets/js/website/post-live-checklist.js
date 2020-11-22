var Post_Live_Checklist = {

    PostLiveOptionSource: [
        {
            value : 'yes',
            text : 'Yes'
        },
        {
            value : 'no',
            text : 'No'
        },
        {
            value : 'n/a',
            text : 'N/A'
        }
    ],
    PostLiveHideOnGoogleOptionSource: [
        {
            value : 'yes',
            text : 'Yes'
        },
        {
            value : 'no',
            text : 'No'
        }
    ],
    init: function(){

        Post_Live_Checklist.initDataTables();
        Post_Live_Checklist.initArchiveAction();
        Post_Live_Checklist.initInlineEditPreConfig();
        Post_Live_Checklist.initInlineEditForOptions();
    },

    initDataTables: function(){
        //init Datatable
        $('#active-websites-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            fixedHeader: true,
            columnDefs: [
                {targets: [1,2,3,4], type: 'sortme'},
                {targets: [5], orderable: false}
            ]
        });

        $('#archived-websites-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : false,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            fixedHeader: true,
            columnDefs: [
                {targets: [1,2,3,4], type: 'sortme'},
            ]
        });

        $('#completed-websites-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : false,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            fixedHeader: true,
            columnDefs: [
                {targets: [1,2,3,4], type: 'sortme'},
            ]
        });

        $.fn.dataTable.ext.type.order['sortme-pre'] = function (a, b) {
            if( a.startsWith('<span') && $(a).hasClass('disable-cell') )
                return -1;
            if( $(a).hasClass("selected") )
                return 0;
            return 1;
        };
    },

    initArchiveAction: function(){
        $(document).on('click', '.archive-website-btn', function(){
            websiteId = $(this).closest('tr').attr('data-website-id');
            $("#archive-website-modal").attr('data-website-id', websiteId);
            $("#archive-website-modal").modal('show');
        })

        $("#archive-website-modal .confirm-btn").click(function(){
            websiteId = $("#archive-website-modal").attr('data-website-id');
            $.ajax({
                type : 'POST',
                url : siteUrl + '/post-live-checklist/archive',
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

        $('#add-website-modal .unarchive-btn').click(function(){
            websiteId = Website_Add_Edit_Modal.selectedWebsiteId;
            $.ajax({
                type : 'POST',
                url : siteUrl + '/un-archive-website',
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

    initInlineEditPreConfig: function(){
        $.fn.editable.defaults.send = "always";
        $.fn.editable.defaults.ajaxOptions = {
            type : 'POST'
        };
        $.fn.editable.defaults.url = siteUrl+"/update-website-post-live-attribute";
        $.fn.editable.defaults.mode = 'popup';
        $.fn.editable.defaults.params = function(params) {
            params._token = csrf_token;
            return params;
        };
    },

    initInlineEditForOptions: function(){

        $("a.option-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                pk          : websiteId,
                source      : $(element).closest('td').attr('data-option-value') == 'hide-on-marketing' ?  Post_Live_Checklist.PostLiveHideOnGoogleOptionSource : Post_Live_Checklist.PostLiveOptionSource,
                showbuttons : false,
                name        : $(element).closest('td').attr('data-option-value'),
                display     : function( value, sourceData ){
                    if( $.fn.editableutils.itemsByValue(value, sourceData).length > 0 ) {
                        $(this).html($.fn.editableutils.itemsByValue(value, sourceData)[0].text);
                    }
                    $(this).closest('td').removeClass('yes-value');
                    $(this).closest('td').removeClass('no-value');
                    $(this).closest('td').removeClass('not-available-value');
                    if( value == 'yes' ){
                        $(this).closest('td').addClass('yes-value')
                    }
                    else if( value == 'no' ) {
                        $(this).closest('td').addClass('no-value')
                    }
                    else {
                        $(this).closest('td').addClass('not-available-value')
                    }
                }
            });
        })
    }
};
$(document).ready(function(){
    Post_Live_Checklist.init();
});
