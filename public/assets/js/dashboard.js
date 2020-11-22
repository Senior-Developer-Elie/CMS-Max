var Dashboard = {

    failedMailsTable : false,

    init: function(){
        this.initPopover();
        this.initActions();
        this.initMissingGoogleDriveWebsitesActions();
    },

    initActions: function(){
        //Archive Notification
        $(".archive-notification-button").click(function(){
            notificationId = $(this).closest(".inbox-thread").attr("data-notification-id");
            $.ajax({
                type: 'GET',
                url: siteUrl + '/archive-notification',
                data: {
                    "_token"    : csrf_token,
                    notificationId
                },
                success: function(data) {
                    if( data.status =='success' ){
                        location.reload();
                    }
                }
            });
        });

        //Archive All Button Action
        $(".archive-all-button").click(function(){
            $.ajax({
                type: 'POST',
                url: siteUrl + '/archive-all-notifications',
                data: {
                    "_token"    : csrf_token
                },
                success: function(data) {
                    if( data.status =='success' ){
                        location.reload();
                    }
                }
            });
        });
    },

    initPopover: function(){
        $('[data-toggle="popover"]').popover({
            container: 'body'
        })
        $('body').on('click', function (e) {
            $('[data-toggle=popover]').each(function () {
                // hide any open popovers when the anywhere else in the body is clicked
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    },

    initMissingGoogleDriveWebsitesActions: function() {
        $(".website-edit-link").click(function(){
            let websiteId = $(this).attr('data-website-id');
            Website_Add_Edit_Modal.showModal(websiteId);
        })
    }
};
$(document).ready(function(){
    Dashboard.init();
})
