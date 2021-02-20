var Websites_Social_Media = {

    activeWebsitesTable: false,
    // archivedWebsitesTable: false,

    init : function(){
        this.initDataTable();
        this.initInlineEditable();
        this.initFilterActions();
        // this.initArchiveActions();
        this.initTooltip();
    },

    initTooltip: function() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    initDataTable: function(){
        $.fn.dataTable.ext.type.order['float-desc'] = function (x, y) {
            x = parseFloat($(x).attr('data-value'));
            y = parseFloat($(y).attr('data-value'));

            x = isNaN(x) ? 0 : x;
            y = isNaN(y) ? 0 : y;

            if ( x > y)
            {
                return -1;
            }

            return 1;
        };

        $.fn.dataTable.ext.type.order['float-asc'] = function (x, y) {
            x = parseFloat($(x).attr('data-value'));
            y = parseFloat($(y).attr('data-value'));

            x = isNaN(x) ? 0 : x;
            y = isNaN(y) ? 0 : y;

            if ( x > y)
            {
                return 1;
            }

            return -1;
        };

        Websites_Social_Media.activeWebsitesTable = $('#website-list-table').DataTable({
            "order"     : [[ 3, "desc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [3], type: 'float'},
            ],
            fixedHeader: true,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var floatConversion = function ( i ) {
                    if( i.toString().startsWith("<a") ) {
                        value = parseFloat($(i).attr('data-value'));
                        value = isNaN(value) ? 0 : value;
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
            },
            'language' : {
                'lengthMenu': `
                    Show _MENU_ entries<div class="form-check">
                        <input type="checkbox" class="form-check-input" id="show-clients-only" checked>
                        <label class="form-check-label" for="show-clients-only">Show clients only</label>
                    </div>`,
            },
            "rowCallback": function( row, data ) {
                let budgetValue = parseFloat($(row).find('a.social-budget-value').attr('data-value'));
                budgetValue = isNaN(budgetValue) ? 0 : budgetValue;

                if ($("#show-clients-only").prop('checked') && budgetValue < 1) {
                    $(row).hide();
                } else {
                    $(row).show();
                }
            },
            "drawCallback":function(){
                $("#websites-count").text('(' +$("#website-list-table tbody tr:visible").length + ')');
            },
        });
        // Websites_Social_Media.archivedWebsitesTable = $('#archived-website-list-table').DataTable({
        //     "order"     : [[ 0, "asc" ]],
        //     'paging'    : true,
        //     'searching' : true,
        //     "pageLength": -1,
        //     "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        //     fixedHeader: true,
        // });
    },

    initFilterActions: function() {
        $(document).on('change', '#show-clients-only', function() {
            Websites_Social_Media.activeWebsitesTable.draw();
        });
    },

    initInlineEditable: function(){
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

        $("a.social-budget-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'text',
                pk          : websiteId,
                name        : 'social_budget',
                display     : function( value, sourceData ){
                    if( value == "0" )
                    {
                        $(this).html("$" + value);
                    }
                    else if( parseFloat(value) >= 0 )
                        $(this).html("$" + value);
                    else
                        $(this).html("$0");
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
