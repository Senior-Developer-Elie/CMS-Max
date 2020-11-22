$(document).ready(function(){

    $('#picker').farbtastic('#color');

    //Togle Color
    $('#togglecolour').change(function() {

        if($(this).is(":checked"))
            $('#bgcolour').show(400);
        else
            $('#bgcolour').hide(400);
    });

    //Toggle Email
    $('#toggleEmailBox').change(function() {

        if($(this).is(":checked"))
            $('#emailBox').show(400);
        else
            $('#emailBox').hide(400);
    });

    //File choose Button
    $("#file-upload-button").click( function(){
        $("#image-file").trigger('click');
    })

    //File Change Event
    $("#image-file").change(function(event){
        if( event.target.files.length == 0)
            $("#file-choosen-text").html("No file choosen");
        else
            $("#file-choosen-text").html(event.target.files.length + " Files Selected");
    })

    //Submit Button Action
    $("#submit").click(function(){

        if( $(this).hasClass('disabled') )
            return;

        //Validate
        if( !validateForm() )
            return;


        //Loading Spinner
        $('body').waitMe({
            effect : 'bounce',
            text : 'Please wait as we are mocking up...',
            bg : 'rgba(255,255,255,0.7)',
            color : '#000'
        });

        //Send Form Data
        //setTimeout(sendFormData, 1000);
        sendFormData();
    })
});

function sendFormData() {

    $("#submit").addClass('disabled');
    var ajaxData = new FormData();

    ajaxData.append('_token', csrf_token);

    //Upload Image Files
    let files = $('#image-file')[0].files;
    for(let i = 0; i < files.length; i++)
    {
        ajaxData.append('images[]', files[i]);
    }

    ajaxData.append('title', $("#title").val().trim());

    var color = $("#togglecolour").is(":checked") ? $("#color").val().trim() : "#FFFFFF";
    ajaxData.append('color', color);

    if( $("#toggleEmailBox").is(":checked") )
        ajaxData.append('email', $("#myemail").val().trim());

    ajaxData.append('align', $("#aligncentre").is(":checked") ? 'center' : 'left');

    //Send Form Data
    $.ajax({
        type: 'POST',
        url: siteUrl + '/mockups/create',
        processData: false,
        contentType: false,
        data: ajaxData,
        success: function(response){
            if( response.status == 'success' )
                location.href = response.url;
        },
        complete: function(response){
            $('body').waitMe('hide');
            $("#submit").removeClass('disabled');
        }
    });
}
function validateForm(){

    var form = $("#goForm");

    //Check File Selection
    if( $("#image-file").val() == "" ) {
        toastr.warning('Please provide mockup file!');
        return false;
    }

    //Check mockup name
    if( $("#title").val().trim() == "" ){
        toastr.warning('Please input your mock up title!');
        return false;
    }

    //Back Color Validation
    if( $("#togglecolour").is(":checked") == true && /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test($("#color").val().trim()) == false ){
        toastr.warning('Please input correct background color!');
        return false;
    }

    //Email validation
    if( $("#toggleEmailBox").is(":checked") == true && !isValidEmailAddress($("#myemail").val().trim()) ){
        toastr.warning('Please input correct email!');
        return false;
    }

    return true;
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
};
