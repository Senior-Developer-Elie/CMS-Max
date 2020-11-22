var Manage_Dns = {

    descriptionCKEditor : false,

    init: function(){
        this.initComponents();
        this.initAddEditActions();
    },

    initComponents: function(){
        //Create CK Editor
        ClassicEditor
        .create( $("#dns-description")[0], {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList'
            ]
        })
        .then( editor => {
            Manage_Dns.descriptionCKEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );
    },

    initAddEditActions: function(){

        //Add Dns Button Click
        $("#add-dns-button").click(function(){
            Manage_Dns_Modal.showModal(-1);
        });

        //Edit Dns Button Click
        $(".edit-button").click(function(){
            dnsId = $(this).closest('tr').attr('data-dns-id');
            Manage_Dns_Modal.showModal(dnsId);
        });

        //Delete Dns Button Click
        $(".delete-button").click(function(){
            $("#delete-dns-modal").attr("data-dns-id", $(this).closest('tr').attr('data-dns-id'));
            $("#delete-dns-modal").modal('show');

        });

        //Confirm Dns Delete Button
        $("#delete-dns-modal .confirm-btn").click(function(){
            dnsId = $("#delete-dns-modal").attr("data-dns-id");
            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-dns',
                data: {
                    _token: csrf_token,
                    dnsId
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

var Manage_Dns_Modal = {
    selectedDnsId : -1,
    init: function(){
        this.initActions();
    },

    initActions: function(){

        //confirm save action
        $("#add-dns-modal .confirm-btn").click(function(){
            if( $(this).hasClass('disabled') )
                return;
            if( !Manage_Dns_Modal.validateForm() )
                return;

            $("#add-dns-modal .confirm-btn").addClass('disabled');

            $.ajax({
                type: 'POST',
                url: siteUrl + '/add-edit-dns',
                data: {
                    _token: csrf_token,
                    dnsId: Manage_Dns_Modal.selectedDnsId,
                    name: $("#dns-name").val(),
                    description: Manage_Dns.descriptionCKEditor.getData()
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

    showModal: function(dnsId){
        Manage_Dns_Modal.selectedDnsId = dnsId;

        if(Manage_Dns_Modal.selectedDnsId == -1) {
            $("#dns-name").val('');
            Manage_Dns.descriptionCKEditor.setData("");
            $("#add-dns-modal .modal-title").html("Add Dns");
            $("#add-dns-modal").modal('show');
        }
        else{
            //Get Dns Data
            $.ajax({
                type : "GET",
                url : siteUrl + "/get-dns-data",
                data: {
                    _token: csrf_token,
                    dnsId : Manage_Dns_Modal.selectedDnsId,
                },
                success: function(data){
                    if(data.status == 'success'){
                        let dns = data.dns;
                        $("#dns-name").val(dns.name);
                        Manage_Dns.descriptionCKEditor.setData(dns.description == null ? "" : dns.description);
                        $("#add-dns-modal .modal-title").html("Edit Dns");
                        $("#add-dns-modal").modal('show');
                    }
                }
            })
        }
    },

    validateForm: function()
    {
        //name
        if( $("#dns-name").val() == "" )
        {
            $("#dns-name").focus();
            return false;
        }
        return true;
    }
};

$(document).ready(function(){
    Manage_Dns.init();
    Manage_Dns_Modal.init();
});
