
var Show_Mockup = {

    currentImageIndex : 0,

    init: function() {

        Show_Mockup.initActions();
        Show_Mockup.showCurrentImage();
        //Show_Mockup.setButtonsStyle();
    },

    showCurrentImage : function(){

        let currentImage = mockupDetails.images[Show_Mockup.currentImageIndex];

        //$(document).find("html").css("min-width", currentImage['imageWidth'] + "px");

        if( currentImage['imageWidth'] >= document.documentElement.clientWidth)
        {
            $("#content").width('100%');
            $("#content").height(parseInt(currentImage['imageHeight'] * document.documentElement.clientWidth / currentImage['imageWidth']));
        }
        else{
            $("#content").width(currentImage['imageWidth']);
            $("#content").height(currentImage['imageHeight']);
        }
        $("#content").css("background-image", "url(" + currentImage['public_image_url'] + ")");
    },

    setButtonsStyle :function(){
        $(".leps-directives li").removeClass('leps-inactive');

        if( Show_Mockup.currentImageIndex == 0 )
            $(".leps-directives .leps-prev").addClass('leps-inactive');

        if( Show_Mockup.currentImageIndex >= mockupDetails.images.length - 1  )
            $(".leps-directives .leps-next").addClass('leps-inactive');
    },

    initActions: function()
    {
        $(".leps-directives .leps-next").click(function(){
            Show_Mockup.currentImageIndex = (Show_Mockup.currentImageIndex+1)%mockupDetails.images.length;
            Show_Mockup.showCurrentImage();
                /*
            if( Show_Mockup.currentImageIndex < mockupDetails.images.length-1 )
                Show_Mockup.currentImageIndex++;
                Show_Mockup.showCurrentImage();
            Show_Mockup.setButtonsStyle();*/
        });

        $(".leps-directives .leps-prev").click(function(){
            Show_Mockup.currentImageIndex--;

            if( Show_Mockup.currentImageIndex <= -1 )
                Show_Mockup.currentImageIndex = mockupDetails.images.length - 1;

            Show_Mockup.showCurrentImage();
            /*
            if( Show_Mockup.currentImageIndex > 0 )
                Show_Mockup.currentImageIndex--;
                Show_Mockup.showCurrentImage();
            Show_Mockup.setButtonsStyle();
            */
        });
    }
}
$(document).ready(function(){
    Show_Mockup.init();
});
