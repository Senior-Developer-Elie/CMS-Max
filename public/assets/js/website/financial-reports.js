var Financial_Reports = {

    init : function(){
        this.initDatatable();
    },

    initDatatable() {
        $("#financial-reports-table").DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ],
            "bSort" : false,
            'paging'    : false,
            'searching' : false,
            fixedHeader: true,
            "scrollX": true,
            scrollY:        ($(window).height() - 350) + "px",
            scrollX:        true,
            scrollCollapse: true,
            fixedColumns:   {
                leftColumns: 4,
            },
        });
    }
};

$(document).ready(function(){
    Financial_Reports.init();
})
