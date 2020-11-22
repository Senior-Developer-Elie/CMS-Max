var Download_Blog = {

    init: function(){

        $("a[data-download-url]").click(function(e){
            e.preventDefault();
            e.stopPropagation();
            $.ajax({
                type: 'GET',
                url: $(this).attr('data-download-url'),
                success: function(response){
                    if( response.status == 'success' ){
                        Download_Adapter.process(response.downloadData);
                    }
                }
            })
        });
    }
}

$(document).ready(function(){
    Download_Blog.init();
})
