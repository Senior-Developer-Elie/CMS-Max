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

        $.fn.dataTable.ext.type.order['plan-desc'] = function (x, y) {
            x = $(x).attr('data-value');
            y = $(y).attr('data-value');

            return x.localeCompare(y);
        };

        $.fn.dataTable.ext.type.order['plan-asc'] = function (x, y) {
            x = $(x).attr('data-value');
            y = $(y).attr('data-value');

            return y.localeCompare(x);
        };

        Websites_Social_Media.activeWebsitesTable = $('#website-list-table').DataTable({
            "order"     : [[ 3, "desc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [3, 4, 5], type: 'float'},
                {targets: [2], type: 'plan'},
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

                for( let i = 3; i <= 5; i++ ) {
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
        });
    },

    initFilterActions: function() {
        $(document).on('change', '#show-clients-only', function() {
            location.href = siteUrl + "/social-media?show_clients_only=" + ($(this).prop('checked') ? 'on' : '');
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

        $("a.social-ad-spend-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'text',
                pk          : websiteId,
                name        : 'social_ad_spend',
                display     : function( value ){
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

        $("a.social-management-fee-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'text',
                pk          : websiteId,
                name        : 'social_management_fee',
                display     : function( value ){
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

        let socialPlanSource = Websites_Social_Media.makePrettySource(socialMediaPlans);
        $("a.manual-social-plan-value").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                pk          : websiteId,
                name        : 'manual_social_plan',
                showbuttons : false,
                source      : socialPlanSource,
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
    },

    makePrettySource: function(object){
        let array = [];
        Object.keys(object).forEach(key => {
            array.push({
                value: key,
                text : object[key]
            })
        });

        array.sort( (a, b) => {
            return a.text.localeCompare(b.text);
        } );

        return array;
    },
};

$(document).ready(function(){
    Websites_Social_Media.init();
})
