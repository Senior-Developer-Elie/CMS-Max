var Websites_Marketing = {

    clientListTable: false,

    init : function(){
        this.initTooltip();
        this.initDataTable();
        this.initInlineEditableForNotes();
        this.initFilterAction();
    },

    initTooltip: function() {
        $('[data-toggle="tooltip"]').tooltip();
    },

    initDataTable: function(){
        Websites_Marketing.clientListTable = $('#website-list-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: (isGoogleAdsUser ? [1, 2] : [1, 2/*, 3, 4, 5, 6, 7*/]), type: 'sortme'},
                {targets: (isGoogleAdsUser ? [3] : [3/*8*/]), type: 'sortme-comment'},
            ],
            fixedHeader: true,
            "footerCallback": function ( row, data, start, end, display ) {
                debugger;
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

                let totalValues = new Array(4);

                for( let i = 1; i <= 3; i++ ) {
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

                    totalValues[i] = pageTotal;
                }

                // Update total profit
                let totalProfit = totalValues[3] * 0.15 + totalValues[2];
                $(".total-profit-value").html("$" + totalProfit.toLocaleString());
            }
        });
        $.fn.dataTable.ext.type.order['sortme-pre'] = function (a, b) {
            return parseFloat($(a).attr('data-value'));
        };
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

    initFilterAction: function(){
        $("#greater-than-one").change(function(){
            if( $(this).prop('checked') ){
                location.href = siteUrl + '/marketing?filterStatus=on';
            }
            else{
                location.href = siteUrl + '/marketing?filterStatus=off';
            }
        });
    }
};

$(document).ready(function(){
    Websites_Marketing.init();
})
