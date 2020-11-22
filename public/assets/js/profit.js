var Manage_Profit = {

    descriptionCKEditor : false,

    init: function(){
        this.initComponents();
        this.initAddEditActions();
    },

    initComponents: function(){
        //Create CK Editor
        ClassicEditor
        .create( $("#profit-description")[0], {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList'
            ]
        })
        .then( editor => {
            Manage_Profit.descriptionCKEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );
    },

    initAddEditActions: function(){

        //Add Profit Button Click
        $("#add-profit-button").click(function(){
            Manage_Profit_Modal.showModal(-1);
        });

        //Edit Profit Button Click
        $(".edit-button").click(function(){
            profitId = $(this).closest('tr').attr('data-profit-id');
            Manage_Profit_Modal.showModal(profitId);
        });

        //Delete Profit Button Click
        $(".delete-button").click(function(){
            $("#delete-profit-modal").attr("data-profit-id", $(this).closest('tr').attr('data-profit-id'));
            $("#delete-profit-modal").modal('show');

        });

        //Confirm Profit Delete Button
        $("#delete-profit-modal .confirm-btn").click(function(){
            profitId = $("#delete-profit-modal").attr("data-profit-id");
            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-profit',
                data: {
                    _token: csrf_token,
                    profitId
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

var Manage_Profit_Modal = {
    selectedProfitId : -1,
    init: function(){
        this.initActions();
    },

    initActions: function(){

        //confirm save action
        $("#add-profit-modal .confirm-btn").click(function(){
            if( $(this).hasClass('disabled') )
                return;
            if( !Manage_Profit_Modal.validateForm() )
                return;

            $("#add-profit-modal .confirm-btn").addClass('disabled');

            $.ajax({
                type: 'POST',
                url: siteUrl + '/add-edit-profit',
                data: {
                    _token: csrf_token,
                    profitId: Manage_Profit_Modal.selectedProfitId,
                    name: $("#profit-name").val(),
                    price: $("#profit-price").val(),
                    description: Manage_Profit.descriptionCKEditor.getData()
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

    showModal: function(profitId){
        Manage_Profit_Modal.selectedProfitId = profitId;

        if(Manage_Profit_Modal.selectedProfitId == -1) {
            $("#profit-name").val('');
            $("#profit-price").val('');
            Manage_Profit.descriptionCKEditor.setData("");
            $("#add-profit-modal .modal-title").html("Add Profit");
            $("#add-profit-modal").modal('show');
        }
        else{
            //Get Profit Data
            $.ajax({
                type : "GET",
                url : siteUrl + "/get-profit-data",
                data: {
                    _token: csrf_token,
                    profitId : Manage_Profit_Modal.selectedProfitId,
                },
                success: function(data){
                    if(data.status == 'success'){
                        let profit = data.profit;
                        $("#profit-name").val(profit.name);
                        $("#profit-price").val(profit.price);
                        Manage_Profit.descriptionCKEditor.setData(profit.description == null ? "" : profit.description);
                        $("#add-profit-modal .modal-title").html("Edit Profit");
                        $("#add-profit-modal").modal('show');
                    }
                }
            })
        }
    },

    validateForm: function()
    {
        //name
        if( $("#profit-name").val() == "" )
        {
            $("#profit-name").focus();
            return false;
        }

        if( $("#profit-price").val() == "" )
        {
            $("#profit-price").focus();
            return false;
        }
        return true;
    }
};

$(document).ready(function(){
    Manage_Profit.init();
    Manage_Profit_Modal.init();
});
