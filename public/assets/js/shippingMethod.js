var Manage_ShippingMethod = {

    descriptionCKEditor : false,

    init: function(){
        this.initComponents();
        this.initAddEditActions();
    },

    initComponents: function(){
        //Create CK Editor
        ClassicEditor
        .create( $("#shippingMethod-description")[0], {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList'
            ]
        })
        .then( editor => {
            Manage_ShippingMethod.descriptionCKEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );
    },

    initAddEditActions: function(){

        //Add ShippingMethod Button Click
        $("#add-shippingMethod-button").click(function(){
            Manage_ShippingMethod_Modal.showModal(-1);
        });

        //Edit ShippingMethod Button Click
        $(".edit-button").click(function(){
            shippingMethodId = $(this).closest('tr').attr('data-shippingMethod-id');
            Manage_ShippingMethod_Modal.showModal(shippingMethodId);
        });

        //Delete ShippingMethod Button Click
        $(".delete-button").click(function(){
            $("#delete-shippingMethod-modal").attr("data-shippingMethod-id", $(this).closest('tr').attr('data-shippingMethod-id'));
            $("#delete-shippingMethod-modal").modal('show');

        });

        //Confirm ShippingMethod Delete Button
        $("#delete-shippingMethod-modal .confirm-btn").click(function(){
            shippingMethodId = $("#delete-shippingMethod-modal").attr("data-shippingMethod-id");
            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-shippingMethod',
                data: {
                    _token: csrf_token,
                    shippingMethodId
                },
                success: function(data){
                    if( data.status == 'success' )
                    {
                        location.reload();
                    }
                }
            });
        });
    }
};

var Manage_ShippingMethod_Modal = {
    selectedShippingMethodId : -1,
    init: function(){
        this.initActions();
    },

    initActions: function(){

        //confirm save action
        $("#add-shippingMethod-modal .confirm-btn").click(function(){
            if( $(this).hasClass('disabled') )
                return;
            if( !Manage_ShippingMethod_Modal.validateForm() )
                return;

            $("#add-shippingMethod-modal .confirm-btn").addClass('disabled');

            $.ajax({
                type: 'POST',
                url: siteUrl + '/add-edit-shippingMethod',
                data: {
                    _token: csrf_token,
                    shippingMethodId: Manage_ShippingMethod_Modal.selectedShippingMethodId,
                    name: $("#shippingMethod-name").val(),
                    description: Manage_ShippingMethod.descriptionCKEditor.getData()
                },
                success: function(data){
                    if( data.status == 'success' )
                    {
                        location.reload();
                    }
                }
            });
        });
    },

    showModal: function(shippingMethodId){
        Manage_ShippingMethod_Modal.selectedShippingMethodId = shippingMethodId;

        if(Manage_ShippingMethod_Modal.selectedShippingMethodId == -1) {
            $("#shippingMethod-name").val('');
            Manage_ShippingMethod.descriptionCKEditor.setData("");
            $("#add-shippingMethod-modal .modal-title").html("Add ShippingMethod");
            $("#add-shippingMethod-modal").modal('show');
        }
        else{
            //Get ShippingMethod Data
            $.ajax({
                type : "GET",
                url : siteUrl + "/get-shippingMethod-data",
                data: {
                    _token: csrf_token,
                    shippingMethodId : Manage_ShippingMethod_Modal.selectedShippingMethodId,
                },
                success: function(data){
                    if(data.status == 'success'){
                        let shippingMethod = data.shippingMethod;
                        $("#shippingMethod-name").val(shippingMethod.name);
                        Manage_ShippingMethod.descriptionCKEditor.setData(shippingMethod.description == null ? "" : shippingMethod.description);
                        $("#add-shippingMethod-modal .modal-title").html("Edit ShippingMethod");
                        $("#add-shippingMethod-modal").modal('show');
                    }
                }
            })
        }
    },

    validateForm: function()
    {
        //name
        if( $("#shippingMethod-name").val() == "" )
        {
            $("#shippingMethod-name").focus();
            return false;
        }
        return true;
    }
};

$(document).ready(function(){
    Manage_ShippingMethod.init();
    Manage_ShippingMethod_Modal.init();
});
