var Client_List = {
    init: function(){
        this.initDataTable();
        this.initActions();
    },

    initDataTable : function(){
        clientListTable = $('#clients-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : false,
            'searching' : true,
            "pageLength": 100,
            "columnDefs": [
                isSuperAdmin ? {
                    "targets": 3,
                    "orderable": false
                } : {}
            ]
        });
        archivedClientListTable = $('#archived-clients-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : false,
            'searching' : true,
            "pageLength": 100
        });
    },

    initActions: function(){

        //Sync All Client
        $("#sync-all-clent-info").click(function(){
            //Loading Spinner
            $('body').waitMe({
                effect : 'bounce',
                text : 'Please wait while syncing clients from API ...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000'
            });
            $.ajax({
                type: "POST",
                url: "/client-all-sync",
                data: {
                    _token: csrf_token,
                },
                success: function(){
                    location.reload();
                }
            });

        });

        //Single Sync Client
        $(".sync-single-client-info").click(function(){
            clientId = $(this).closest("tr").attr("data-client-id");

            //Loading Spinner
            $('body').waitMe({
                effect : 'bounce',
                text : 'Please wait while syncing client from API ...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000'
            });
            $.ajax({
                type: "POST",
                url: "/client-single-sync",
                data: {
                    _token: csrf_token,
                    clientId
                },
                success: function(){
                    location.reload();
                }
            });
        })
    },
};
$(document).ready(function(){
    Client_List.init();
})
