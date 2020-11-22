var Action_Notification = {
    init: function() {
        this.initNotificationToolBarAction();
    },
    initNotificationToolBarAction: function() {
        $(".notification-item .archive-button").click(function(e){
            e.preventDefault();
            notificationItem = $(this).closest('.notification-item');
            notificationId = $(this).closest('.notification-item').attr('data-notification-id');
            $.ajax({
                type: 'POST',
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
    }
};
$(document).ready(function(){
    Action_Notification.init();
})