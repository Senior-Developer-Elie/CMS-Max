var Social_Media_Inactive_List = {

    deletingWebsiteId: false,

    init: () => {
        Social_Media_Inactive_List.initDataTable();
        Social_Media_Inactive_List.initMarkAsActiveAction();
    },

    initDataTable: () => {
        $("#inactive-websites-list").DataTable({
            "order"         : [[ 0, "asc" ]],
            'paging'        : true,
            'searching'     : true,
            'pageLength'    : 100
        });
    },

    initMarkAsActiveAction: () => {
        $(".mark-as-active-button").click(function() {
            Social_Media_Inactive_List.deletingWebsiteId = $(this).attr('data-website-id');
            $("#mark-as-active-modal").modal('show');
        })

        $("#mark-as-active-modal .confirm-btn").click(function() {
            $.ajax({
                type: "POST",
                url: siteUrl + "/social-media/update-social-media-archived/" + Social_Media_Inactive_List.deletingWebsiteId,
                data: {
                    _token: csrf_token,
                    value: 'unarchived',
                },
                success: function(response) {
                    location.reload();
                }
            })   
        })
    },
};

$(document).ready(function(){
    Social_Media_Inactive_List.init();
})