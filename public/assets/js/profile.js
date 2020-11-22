var Profile = {
    imageWidth: 200,
    imageHeight: 200,
    rawImg: false,
    cropper: false,
    init: function() {
        this.initPhotoUploadActions();
    },

    initPhotoUploadActions: function() {

        Profile.cropper = $('#upload-demo').croppie({
            viewport: {
                width: Profile.imageWidth,
                height: Profile.imageHeight,
                type: 'circle',
                quality: 1
            },
            enforceBoundary: false,
            enableExif: true
        });
        
        //Click photo image
        $(".avatar-wrapper").click(function(){
            $("#photo-file").trigger('click');
        });

        //File change event
        $("#photo-file").change(function(event){
            if( event.target.files.length > 0 ) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.upload-demo').addClass('ready');
                    $('#photo-upload-modal').modal('show');
                    Profile.rawImg = e.target.result;
                    $("#photo-file").val("");
                }
                reader.readAsDataURL($("#photo-file")[0].files[0]);
            }
        })

        //Modal Show Event
        $('#photo-upload-modal').on('shown.bs.modal', function(){
            // alert('Shown pop');
            Profile.cropper.croppie('bind', {
                url: Profile.rawImg
            }).then(function(){
                console.log('jQuery bind complete');
            });
        });

        //Image Crop Complete Action
        $('#photo-upload-modal .confirm-btn').on('click', function () {

            if( $(this).hasClass('disabled') )
                return;
            
            $(this).addClass('disabled');
            Profile.cropper.croppie('result', {
                type: 'base64',
                format: 'png',
                size: {width: Profile.imageWidth, height: Profile.imageHeight}
            }).then( (resp) => {

                ajaxData = new FormData();
                ajaxData.append('_token', csrf_token);
                ajaxData.append('image', dataURItoBlob(resp))
                $.ajax({
                    type: 'POST',
                    url: siteUrl + '/upload-photo',
                    data: ajaxData,
                    processData: false,
                    contentType: false,
                    success: function(data){
                        if(data.status == 'success'){
                            location.reload();
                        }
                    }
                });
            });
        });
    }
};

$(document).ready(function(){
    Profile.init();
})

function dataURItoBlob(dataURI) {
    // convert base64/URLEncoded data component to raw binary data held in a string
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0)
        byteString = atob(dataURI.split(',')[1]);
    else
        byteString = unescape(dataURI.split(',')[1]);

    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

    // write the bytes of the string to a typed array
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ia], {type:mimeString});
}