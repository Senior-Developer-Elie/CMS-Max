var Manage_Affiliate = {

    descriptionCKEditor : false,

    init: function(){
        this.initComponents();
        this.initAddEditActions();
    },

    initComponents: function(){
        //Create CK Editor
        ClassicEditor
        .create( $("#affiliate-description")[0], {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList'
            ]
        })
        .then( editor => {
            Manage_Affiliate.descriptionCKEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );
    },

    initAddEditActions: function(){

        //Add Affiliate Button Click
        $("#add-affiliate-button").click(function(){
            Manage_Affiliate_Modal.showModal(-1);
        });

        //Edit Affiliate Button Click
        $(".edit-button").click(function(){
            affiliateId = $(this).closest('tr').attr('data-affiliate-id');
            Manage_Affiliate_Modal.showModal(affiliateId);
        });

        //Delete Affiliate Button Click
        $(".delete-button").click(function(){
            $("#delete-affiliate-modal").attr("data-affiliate-id", $(this).closest('tr').attr('data-affiliate-id'));
            $("#delete-affiliate-modal").modal('show');

        });

        //Confirm Affiliate Delete Button
        $("#delete-affiliate-modal .confirm-btn").click(function(){
            affiliateId = $("#delete-affiliate-modal").attr("data-affiliate-id");
            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-affiliate',
                data: {
                    _token: csrf_token,
                    affiliateId
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

var Manage_Affiliate_Modal = {
    selectedAffiliateId : -1,
    init: function(){
        this.initActions();
    },

    initActions: function(){

        //confirm save action
        $("#add-affiliate-modal .confirm-btn").click(function(){
            if( $(this).hasClass('disabled') )
                return;
            if( !Manage_Affiliate_Modal.validateForm() )
                return;

            $("#add-affiliate-modal .confirm-btn").addClass('disabled');

            $.ajax({
                type: 'POST',
                url: siteUrl + '/add-edit-affiliate',
                data: {
                    _token: csrf_token,
                    affiliateId: Manage_Affiliate_Modal.selectedAffiliateId,
                    name: $("#affiliate-name").val(),
                    description: Manage_Affiliate.descriptionCKEditor.getData()
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

    showModal: function(affiliateId){
        Manage_Affiliate_Modal.selectedAffiliateId = affiliateId;

        if(Manage_Affiliate_Modal.selectedAffiliateId == -1) {
            $("#affiliate-name").val('');
            Manage_Affiliate.descriptionCKEditor.setData("");
            $("#add-affiliate-modal .modal-title").html("Add Affiliate");
            $("#add-affiliate-modal").modal('show');
        }
        else{
            //Get Affiliate Data
            $.ajax({
                type : "GET",
                url : siteUrl + "/get-affiliate-data",
                data: {
                    _token: csrf_token,
                    affiliateId : Manage_Affiliate_Modal.selectedAffiliateId,
                },
                success: function(data){
                    if(data.status == 'success'){
                        let affiliate = data.affiliate;
                        $("#affiliate-name").val(affiliate.name);
                        Manage_Affiliate.descriptionCKEditor.setData(affiliate.description == null ? "" : affiliate.description);
                        $("#add-affiliate-modal .modal-title").html("Edit Affiliate");
                        $("#add-affiliate-modal").modal('show');
                    }
                }
            })
        }
    },

    validateForm: function()
    {
        //name
        if( $("#affiliate-name").val() == "" )
        {
            $("#affiliate-name").focus();
            return false;
        }
        return true;
    }
};

$(document).ready(function(){
    Manage_Affiliate.init();
    Manage_Affiliate_Modal.init();
});
