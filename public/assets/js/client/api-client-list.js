var Api_Client_List = {

    archivingClientId: false,
    unarchivingClientId: false,
    init: function(){
        this.initDatatable();
        this.initActions();
    },

    initDatatable: function(){
        $('#pending-websites-table').DataTable({
            "order"     : [[ 1, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [2], type: 'sorttypevalue'},
                {targets: [0, 4], orderable: false}
            ]
        });
        $('#archived-websites-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [1], type: 'sorttypevalue'},
                {targets: [3], orderable: false}
            ]
        });
        $.fn.dataTable.ext.type.order['sorttypevalue-pre'] = function (a, b) {
            if( a.trim().startsWith("<a") )
                return $(a).attr("data-value");

            return "";
        };
    },

    initActions: function(){
        //Check all api clients action
        $(".check-all").change(function(){
            var checked = $(this).prop('checked');
            $(".api-client-check").prop("checked", checked);
        });
        //Add Selected Api Clients
        $("#add-api-clients").click(function(){
            if( $(".api-client-check:checked").length == 0 )
                return;
            $("#sync-all-modal .sync-client-count").html($(".api-client-check:checked").length);
            $("#sync-all-modal").modal('show');
        })
        $("#sync-all-modal .confirm-btn").click(function(){
            apiClientIds = [];
            $(".api-client-check:checked").each(function(index, element){
                apiClientIds.push($(element).closest("tr").attr("data-api-client-id"));
            });
            //Loading Spinner
            $('body').waitMe({
                effect : 'bounce',
                text : 'Please wait while adding clients from API ...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000'
            });

            $.ajax({
                type: "POST",
                url: "/client-add-api-clients",
                data: {
                    _token: csrf_token,
                    apiClientIds
                },
                success: function(){
                    location.reload();
                }
            });
        });

        //Archive button click
        $(document).on('click', '.archive-btn', function(){
            let apiClientId = $(this).closest('tr').attr('data-api-client-id');
            Api_Client_List.archivingClientId = apiClientId;
            $("#archive-api-client-modal").modal('show');
        })
        //Confirm
        $("#archive-api-client-modal .confirm-btn").click(function(){
            $.ajax({
                type : 'POST',
                url : siteUrl + '/archive-api-client',
                data:{
                    _token: csrf_token,
                    apiClientId : Api_Client_List.archivingClientId
                },
                success: function(data){
                    if( data.status == 'success' ){
                        $("#archive-api-client-modal").modal('hide');
                        location.reload();
                    }
                }
            })
        })

        //Archive button click
        $(document).on('click', '.unarchive-btn', function(){
            let apiClientId = $(this).closest('tr').attr('data-api-client-id');
            Api_Client_List.unarchivingClientId = apiClientId;
            $("#unarchive-api-client-modal").modal('show');
        })
        //Confirm
        $("#unarchive-api-client-modal .confirm-btn").click(function(){
            $.ajax({
                type : 'POST',
                url : siteUrl + '/unarchive-api-client',
                data:{
                    _token: csrf_token,
                    apiClientId : Api_Client_List.unarchivingClientId
                },
                success: function(data){
                    if( data.status == 'success' ){
                        $("#unarchive-api-client-modal").modal('hide');
                        location.reload();
                    }
                }
            })
        })
    },
};
$(document).ready(function(){
    Api_Client_List.init();
})
