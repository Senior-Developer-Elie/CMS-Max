var signaturePad = false;
$(document).ready(function(){
    var canvas = document.querySelector("#sign-pad");
    signaturePad = new SignaturePad(canvas);

    canvas.width = 600;
    canvas.height = 200;
    signaturePad.clear(); // otherwise isEmpty() might return incorrect value


    $("#sign-form").submit(function(){
        $("#signature-field").val(signaturePad.toDataURL());
        return true;
    });
});
