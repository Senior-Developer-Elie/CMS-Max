var Client_History = {
    init: function(){
        this.initComponents();
        this.initActions();
    },

    initComponents: function(){
    },

    initActions: function(){

        //Clear Blog Image Button Click
        $(".clear-blog-image-button").click(function(){
            $("#clear-blog-image-modal").attr('data-blog-id', $(this).attr("data-blog-id"));
            $("#clear-blog-image-modal").modal('show');
        });

        //Confirm Clear Blog Image Button
        $("#clear-blog-image-modal .confirm-btn").click(function(){
            ajaxData = {
                '_token'    : csrf_token,
                'blogId'    : $("#clear-blog-image-modal").attr("data-blog-id")
            };

            $.ajax({
                type: 'POST',
                url: siteUrl + '/clear-blog-image',
                data: ajaxData,
                success: function(data){
                    if(data.status == 'success'){
                        location.href = siteUrl + '/blog-dashboard';
                    }
                }
            });
        });
    }
};

$(document).ready(function(){
    Client_History.init();
})
