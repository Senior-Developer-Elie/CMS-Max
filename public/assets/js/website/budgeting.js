var Websites_Budgeting = {

    init : function(){
        this.initDataTable();
        this.initFilterAction();
        this.initWebsiteAddEditAction();
    },

    initDataTable: function(){
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
            columnDefs: [
                {targets: ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]), type: 'sortme'},
            ],
            fixedHeader: true,
            "scrollX": true,
            scrollY:        ($(window).height()-450) + "px",
            scrollX:        true,
            scrollCollapse: true,
            fixedColumns:   {
                leftColumns: 1,
            },
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var floatConversion = function ( i ) {
                    if( i.toString().startsWith("<span") ) {
                        value = parseFloat($(i).attr('data-value'));
                        if( value < 0 || isNaN(value))
                            return 0;
                        return value;
                    }
                    else
                        return 0;
                };

                for( let i = 1; i <= 15; i++ ) {
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
        $.fn.dataTable.ext.type.order['sortme-pre'] = function (a, b) {
            return parseFloat($(a).attr('data-value'));
        };
        $.fn.dataTable.ext.type.order['sortme-comment'] = function (a, b) {
            return $(a).attr('data-value');
        };
    },

    initFilterAction: function(){
        $("#show-unsynced").change(function(){
            if( $(this).prop('checked') ){
                location.href = siteUrl + '/budgeting?filterType=unsynced';
            }
            else{
                location.href = siteUrl + '/budgeting?filterType=all';
            }
        });
    },

    initWebsiteAddEditAction: function(){
        //Add Website Button
        $("#add-website-button").click(function(){
            Website_Add_Edit_Modal.showModal(-1);
        });

        //Edit Website Action
        $(document).on('click', '.website-url', function(e){
            e.preventDefault();
            websiteId = $(this).closest('tr').attr('data-website-id');
            Website_Add_Edit_Modal.showModal(websiteId);
        });
    },
};

$(document).ready(function(){
    Websites_Budgeting.init();
})
