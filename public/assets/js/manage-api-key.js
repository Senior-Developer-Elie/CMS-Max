var Manage_Mailgun_Api_Keys = {

    selectedApiKeyId : -1,
    deletingApiKeyId : false,
    init: function(){

        Manage_Mailgun_Api_Keys.initActions();
    },

    initActions: function(){

        //Add button
        $("#add-api-key-btn").click(function(){
            $("#add-api-key-modal .modal-title").html('Add Mailgun Api Key');
            $("#add-api-key-modal #domain").val('');
            $("#add-api-key-modal #api-key").val('');
            Manage_Mailgun_Api_Keys.selectedApiKeyId = -1;
            $("#add-api-key-modal").modal('show');
        });
        $(document).on("click", ".api-key-row .edit-button", function(){
            let apiKeyRow = $(this).closest('.api-key-row');
            $("#add-api-key-modal .modal-title").html('Update Mailgun Api Key');
            Manage_Mailgun_Api_Keys.selectedApiKeyId = apiKeyRow.attr('data-mailgun-api-key-id');
            $("#add-api-key-modal #domain").val(apiKeyRow.find('.domain-value').attr('data-value').trim());
            $("#add-api-key-modal #api-key").val(apiKeyRow.find('.key-value').attr('data-value').trim());
            $("#add-api-key-modal").modal('show');
        });

        //Confirm Add Button
        $("#add-api-key-modal .confirm-btn").click(function(){
            let ajaxData = {
                _token: csrf_token,
                mailgunApiKeyId: Manage_Mailgun_Api_Keys.selectedApiKeyId,
                domain: $("#add-api-key-modal #domain").val(),
                key: $("#add-api-key-modal #api-key").val()
            };

            if( ajaxData.domain.trim() == "" ){
                $("#add-api-key-modal #domain").focus();
                return false;
            }
            if( ajaxData.key.trim() == ""  ){
                $("#add-api-key-modal #api-key").focus();
                return false;
            }

            $('body').waitMe({
                effect : 'bounce',
                text : 'Please wait while updating webhooks...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000'
            });

            $.ajax({
                type: 'POST',
                url: siteUrl + '/add-mailgun-api-key',
                data: ajaxData,
                success: function(data){
                    $("#add-api-key-modal").modal('hide');
                    $('body').waitMe('hide');
                    if( data.status == 'success' ){
                        let message = 'Mailgun Api Key is created successfully';
                        if( Manage_Mailgun_Api_Keys.selectedApiKeyId != -1 )
                            message = "Mailgun Api Key is updated successfully";
                        $.notify(message, {
                            type: 'success',
                            animate: {
                                enter: 'animated lightSpeedIn',
                                exit: 'animated lightSpeedOut'
                            }
                        })
                        location.reload();
                    }
                    else if( data.message != undefined ){
                        $.notify(data.message, {
                            type: 'danger',
                            animate: {
                                enter: 'animated lightSpeedIn',
                                exit: 'animated lightSpeedOut'
                            }
                        })
                    }
                    else{
                        $.notify('Something went wrong', {
                            type: 'danger',
                            animate: {
                                enter: 'animated lightSpeedIn',
                                exit: 'animated lightSpeedOut'
                            }
                        })
                    }
                }
            });
        })

        //Delete Action
        $(document).on("click", ".api-key-row .delete-button", function(){
            let apiKeyRow = $(this).closest('.api-key-row');
            Manage_Mailgun_Api_Keys.deletingApiKeyId = apiKeyRow.attr('data-mailgun-api-key-id');
            $("#delete-api-key-modal .domain-value").html(apiKeyRow.find('.domain-value').attr('data-value').trim());
            $("#delete-api-key-modal .key-value").html(apiKeyRow.find('.key-value').attr('data-value').trim());
            $("#delete-api-key-modal").modal('show');
        });
        //Delete modal confirm btn
        $("#delete-api-key-modal .confirm-btn").click(function(){

            $('body').waitMe({
                effect : 'bounce',
                text : 'Please wait while deleting webhooks...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000'
            });

            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-mailgun-api-key',
                data: {
                    _token: csrf_token,
                    mailgunApiKeyId: Manage_Mailgun_Api_Keys.deletingApiKeyId
                },
                success: function(data){
                    $("#delete-api-key-modal").modal('hide');
                    if( data.status == 'success' ){
                        $.notify('Api Key is deleted successfully!', {
                            type: 'success',
                            animate: {
                                enter: 'animated lightSpeedIn',
                                exit: 'animated lightSpeedOut'
                            }
                        })
                        location.reload();
                    }
                    else{
                        $.notify('Somethign went wrong please try again!', {
                            type: 'danger',
                            animate: {
                                enter: 'animated lightSpeedIn',
                                exit: 'animated lightSpeedOut'
                            }
                        })
                    }
                },
                complete: function(){
                    $('body').waitMe('hide');
                }
            })
        })
    }
};

$(document).ready(function(){
    Manage_Mailgun_Api_Keys.init();
})
