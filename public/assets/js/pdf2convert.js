$(document).ready(function(){

    //Parse Button Action
    $("#convert-button").click(function(){

        //
        if( $("#upload-form input[type='file']")[0].files.length == 0 )
        {
            alert("Please select your PDF File");
            return;
        }

        //Loading Spinner
        $('body').waitMe({
            effect : 'bounce',
            text : 'Please wait while converting PDF',
            bg : 'rgba(255,255,255,0.7)',
            color : '#000'

        });

        //Make FormData
        var formData = new FormData();
        formData.append('file', $("#upload-form input[type='file']")[0].files[0]);
        formData.append('width', $("input#width").val());
        formData.append('space', $("input#space").val());
        formData.append('quality', $("input#quality").val());
        formData.append('rotate', $("#rotate-pages").is(":checked"));
        formData.append('_token', csrf_token);

        $.ajax({
            url: siteUrl + "/process-pdf-convert",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST', // For jQuery < 1.9
            xhr:function(){// Seems like the only way to get access to the xhr object
                var xhr = new XMLHttpRequest();
                xhr.responseType= 'blob'
                return xhr;
            },
            success: function(data){
                //Generate Image File name same as PDF
                var uploadFileName = $("#upload-form input[type='file']")[0].files[0].name;
                var imageFileName = (uploadFileName.substr(0, uploadFileName.lastIndexOf('.')) || uploadFileName) + ".jpg";

                //Make download Link
                const url = window.URL.createObjectURL(new Blob([data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', imageFileName); //or any other extension
                document.body.appendChild(link);
                link.click();
                link.remove();

                //Reset Form
                $("#upload-form")[0].reset();

                //Hide Spinner
                $('body').waitMe('hide');
            },
            error: function(){
                alert("something is wrong with your uploaded file!");
                $('body').waitMe('hide');
            }
        })
    })
})
