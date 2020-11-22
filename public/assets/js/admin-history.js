var Admin_History = {
    init: function(){
        this.initDataTable();
        this.initFilterAction();
    },

    initDataTable: function() {
        proposalTable = $('#admin-history-table').DataTable({
            "order"         : [[ 0, "desc" ]],
            'paging'        : true,
            'searching'     : true,
            'pageLength'    : 100
        });
    },

    initFilterAction: function(){
        $("#client-filter").change(function(){
            location.href = siteUrl + "/admin-history?userId=" + $(this).val();
        });
    }
};

$(document).ready(function(){
    Admin_History.init();
})
