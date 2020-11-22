var Client_Add_Edit = {
    init: function(){
        this.initComponents();
        this.initActions();
    },

    initComponents: function(){
        //Init Tooltip
        $('[data-toggle="tooltip"]').tooltip()

        //init Ckeditor
        if( $("#notes").length > 0 ){
            ClassicEditor
            .create( $("#notes")[0], {
                toolbar: [
                    'bold', 'italic', 'bulletedList', 'numberedList', 'link', 'item'
                ],
                link: {
                    // Automatically add target="_blank" and rel="noopener noreferrer" to all external links.
                    addTargetToExternalLinks: true,

                    // Let the users control the "download" attribute of each link.
                    decorators: [
                        {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'download'
                            }
                        }
                    ]
                }
            })
            .then( editor => {
            } )
            .catch( error => {
                console.error( error );
            } );
        }

        if( $("#contacts").length > 0 ){
            ClassicEditor
            .create( $("#contacts")[0], {
                toolbar: [
                    'bold', 'italic', 'bulletedList', 'numberedList', 'link', 'item'
                ],
                link: {
                    // Automatically add target="_blank" and rel="noopener noreferrer" to all external links.
                    addTargetToExternalLinks: true,

                    // Let the users control the "download" attribute of each link.
                    decorators: [
                        {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'download'
                            }
                        }
                    ]
                }
            })
            .then( editor => {
            } )
            .catch( error => {
                console.error( error );
            } );
        }
    },

    initActions: function(){
        $("#sync-client-btn").click(function(){
            //Loading Spinner
            $('body').waitMe({
                effect : 'bounce',
                text : 'Please wait while syncing client info...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000'
            });

            $.ajax({
                type: "POST",
                url: "/client-single-sync",
                data: {
                    _token: csrf_token,
                    clientId
                },
                success: function(){
                    location.reload();
                }
            });
        })

        $(".client-drive-input").keyup(function(){
            $(".show-drive").attr('href', $(this).val().trim());
        });

        $(".show-drive").click(function(e){
            let driverUrl = $(".client-drive-input").val().trim();
            if( driverUrl == "" )
                e.preventDefault();
            return true;
        })
    },
};

$(document).ready(function(){
    Client_Add_Edit.init();
})
