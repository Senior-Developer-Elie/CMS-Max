var Manage_PaymentGateway = {

    descriptionCKEditor : false,

    init: function(){
        this.initComponents();
        this.initAddEditActions();
    },

    initComponents: function(){
        //Create CK Editor
        ClassicEditor
        .create( $("#paymentGateway-description")[0], {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList'
            ]
        })
        .then( editor => {
            Manage_PaymentGateway.descriptionCKEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );
    },

    initAddEditActions: function(){

        //Add PaymentGateway Button Click
        $("#add-paymentGateway-button").click(function(){
            Manage_PaymentGateway_Modal.showModal(-1);
        });

        //Edit PaymentGateway Button Click
        $(".edit-button").click(function(){
            paymentGatewayId = $(this).closest('tr').attr('data-paymentGateway-id');
            Manage_PaymentGateway_Modal.showModal(paymentGatewayId);
        });

        //Delete PaymentGateway Button Click
        $(".delete-button").click(function(){
            $("#delete-paymentGateway-modal").attr("data-paymentGateway-id", $(this).closest('tr').attr('data-paymentGateway-id'));
            $("#delete-paymentGateway-modal").modal('show');

        });

        //Confirm PaymentGateway Delete Button
        $("#delete-paymentGateway-modal .confirm-btn").click(function(){
            paymentGatewayId = $("#delete-paymentGateway-modal").attr("data-paymentGateway-id");
            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-paymentGateway',
                data: {
                    _token: csrf_token,
                    paymentGatewayId
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

var Manage_PaymentGateway_Modal = {
    selectedPaymentGatewayId : -1,
    init: function(){
        this.initActions();
    },

    initActions: function(){

        //confirm save action
        $("#add-paymentGateway-modal .confirm-btn").click(function(){
            if( $(this).hasClass('disabled') )
                return;
            if( !Manage_PaymentGateway_Modal.validateForm() )
                return;

            $("#add-paymentGateway-modal .confirm-btn").addClass('disabled');

            $.ajax({
                type: 'POST',
                url: siteUrl + '/add-edit-paymentGateway',
                data: {
                    _token: csrf_token,
                    paymentGatewayId: Manage_PaymentGateway_Modal.selectedPaymentGatewayId,
                    name: $("#paymentGateway-name").val(),
                    description: Manage_PaymentGateway.descriptionCKEditor.getData()
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

    showModal: function(paymentGatewayId){
        Manage_PaymentGateway_Modal.selectedPaymentGatewayId = paymentGatewayId;

        if(Manage_PaymentGateway_Modal.selectedPaymentGatewayId == -1) {
            $("#paymentGateway-name").val('');
            Manage_PaymentGateway.descriptionCKEditor.setData("");
            $("#add-paymentGateway-modal .modal-title").html("Add PaymentGateway");
            $("#add-paymentGateway-modal").modal('show');
        }
        else{
            //Get PaymentGateway Data
            $.ajax({
                type : "GET",
                url : siteUrl + "/get-paymentGateway-data",
                data: {
                    _token: csrf_token,
                    paymentGatewayId : Manage_PaymentGateway_Modal.selectedPaymentGatewayId,
                },
                success: function(data){
                    if(data.status == 'success'){
                        let paymentGateway = data.paymentGateway;
                        $("#paymentGateway-name").val(paymentGateway.name);
                        Manage_PaymentGateway.descriptionCKEditor.setData(paymentGateway.description == null ? "" : paymentGateway.description);
                        $("#add-paymentGateway-modal .modal-title").html("Edit PaymentGateway");
                        $("#add-paymentGateway-modal").modal('show');
                    }
                }
            })
        }
    },

    validateForm: function()
    {
        //name
        if( $("#paymentGateway-name").val() == "" )
        {
            $("#paymentGateway-name").focus();
            return false;
        }
        return true;
    }
};

$(document).ready(function(){
    Manage_PaymentGateway.init();
    Manage_PaymentGateway_Modal.init();
});
