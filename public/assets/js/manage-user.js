var Manage_User = {

    descriptionCKEditor: false,
    
    init: function() {
        this.initCKEditor();
        this.initEditPermissionActions();
    },

    initCKEditor: function() {
        ClassicEditor
        .create( $("#edit-description-modal .description")[0], {
            toolbar: [
                'bold', 'italic'
            ]
        })
        .then( editor => {
            Manage_User.descriptionCKEditor = editor;
        } )
        ;
    },

    initEditPermissionActions: function() {
        $(".permission-row .name").click(function(e){
            e.preventDefault();
            permissionId = $(this).closest('tr').attr('data-permission-id');

            $.ajax({
                type: 'GET',
                url: '/get-permission',
                data: {
                    permissionId
                },
                success: function(data){
                    if(data.status == 'success'){
                        $("#edit-description-modal").attr('data-permission-id', permissionId);
                        $("#edit-description-modal .permission-name").html(data.permission.name);
                        if(data.permission.description == null)
                            Manage_User.descriptionCKEditor.setData("");
                        else
                            Manage_User.descriptionCKEditor.setData(data.permission.description);
                        $("#edit-description-modal").modal("show");
                    }
                }
            });
        });

        $("#edit-description-modal .confirm-btn").click(function(){
            permissionId = $("#edit-description-modal").attr("data-permission-id");
            $.ajax({
                type: 'POST',
                url: '/update-permission',
                data: {
                    "_token"    : csrf_token,
                    permissionId,
                    'description'   : Manage_User.descriptionCKEditor.getData()
                },
                success: function(data){
                    if(data.status == 'success'){
                        location.reload();
                    }
                }
            });
        });
    }
};

$(document).ready(function(){
    Manage_User.init();
});