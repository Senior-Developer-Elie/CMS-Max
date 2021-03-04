var Upload_Action = {
    init: function() {
        this.initUploadActions();
    },

    //Upload Word/Images Button click event
    initUploadActions: function() {
        //upload blog button
        $(document).on('click', '.upload-blog-button', function(e){
            e.stopPropagation();
            $("#blogFile").attr('data-blog-id', $(this).attr('data-blog-id'));
            $("#blogFile").trigger('click');
        });

        //upload image button
        $(document).on('click', '.upload-image-button', function(e){
            e.stopPropagation();
            $("#blogImageFile").attr('data-blog-id', $(this).attr('data-blog-id'));
            $("#blogImageFile").trigger('click');
        });

        //blog file change event
        $("#blogFile").change(function(event){
            if( event.target.files.length > 0){

                //Loading Spinner
                $('body').waitMe({
                    effect : 'bounce',
                    text : 'Please wait while uploading blog...',
                    bg : 'rgba(255,255,255,0.7)',
                    color : '#000'
                });

                let blogId = $(this).attr('data-blog-id');

                let ajaxData = new FormData();
                ajaxData.append('_token', csrf_token);
                ajaxData.append('blogId', blogId);
                ajaxData.append('blogFile', event.target.files[0]);

                //Send Form Data
                $.ajax({
                    type: 'POST',
                    url: siteUrl + '/upload-blog',
                    processData: false,
                    contentType: false,
                    data: ajaxData,
                    success: function(response){
                        if( response.status == 'success' )
                            location.href = siteUrl + '/blog-dashboard';
                    },
                    complete: function(){
                        $('body').waitMe('hide');
                        $("#submit").removeClass('disabled');
                    }
                });
            }
        });

        //image file change event
        $("#blogImageFile").change(function(event){
            if( event.target.files.length > 0){

                //Loading Spinner
                $('body').waitMe({
                    effect : 'bounce',
                    text : 'Please wait while uploading image...',
                    bg : 'rgba(255,255,255,0.7)',
                    color : '#000'
                });

                let blogId = $(this).attr('data-blog-id');

                let ajaxData = new FormData();
                ajaxData.append('_token', csrf_token);
                ajaxData.append('blogId', blogId);
                for(let i = 0; i < event.target.files.length; i++)
                    ajaxData.append('blogImageFile[]', event.target.files[i]);

                //Send Form Data
                $.ajax({
                    type: 'POST',
                    url: siteUrl + '/upload-blog-image',
                    processData: false,
                    contentType: false,
                    data: ajaxData,
                    success: function(response){
                        if( response.status == 'success' )
                            location.href = siteUrl + '/blog-dashboard';
                    },
                    complete: function(){
                        $('body').waitMe('hide');
                        $("#submit").removeClass('disabled');
                    }
                });
            }
        });
    }
};

$(document).ready(function(){
    Upload_Action.init();
})
