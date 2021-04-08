
var Proposal_Generator = {

    ckeditorInstances : [],

    //Init Function
    init: function(){

        this.initServicesData();
        this.initCkeditor();
        this.initActions();
        this.initPreFillActions();
        this.initTemplateChangeActions();

        if( typeof editMode != 'undefined' && editMode == true ){
            this.initEditAction();
        }
    },

    initServicesData: function(){
        services.forEach(service => {
            services[service.name] = service;
        });
    },

    initCkeditor: function(){

        //Ckeditor for bottom-description
        Proposal_Generator.createClassicCkEditor('bottom-description');
    },

    initActions: function(){
        //Services Checkbox Action
        $(".service-container input[type='checkbox']").change(function(){

            //Enable or Disable Price Tag
            var targetPriceInput = $("input[type='text'][name='" + $(this).attr('name') + "-price']");

            if( $(this).is(':checked') )
                targetPriceInput.removeAttr('disabled');
            else
                targetPriceInput.attr('disabled', 'disabled');

            //Add hide Content Editor
            var contentEditorId = $(this).attr('name') + '-content';
            if( $(this).is(':checked') )
            {
                //IF textarea is not created yet, then create
                if( $("#"+contentEditorId).length == 0 )
                {
                    var textAreaElement = '<div class = "col-md-12"><textarea class="form-control" id = "' + contentEditorId
                        + '" name = "' + contentEditorId + '"></textarea></div>';
                    $(this).parent().parent().next().after(textAreaElement);
                }

                //IF Ckeditor is not created yet, then create
                if( typeof Proposal_Generator.ckeditorInstances[contentEditorId]  === 'undefined' )
                {
                    Proposal_Generator.createClassicCkEditor(contentEditorId);
                }
            }
            else
            {
                //Destroy Ckeditor
                Proposal_Generator.ckeditorInstances[contentEditorId].destroy();
                delete Proposal_Generator.ckeditorInstances[contentEditorId];
                $("#"+contentEditorId).remove();
            }
        });

        //Email Checkbox Action
        $("#email-contact").change(function(){
            if( $(this).is(':checked') ){
                $("input#client-email").show();
                $("#add-signature").prop('checked', true);
            }
            else{
                $("input#client-email").hide();
            }
        })

        //Generate Button Action
        $("#download-button").click(function(){
            $("#proposal-form").removeAttr('target');
            $("#request-type-field").val('normal-download');

            $("#proposal-form").submit();
        });

        //Preview Button Action
        $("#preview-button").click(function(){

            if( Proposal_Generator.validateForm() )
            {
                $("#proposal-form").attr('target', "preview-frame");
                $("#request-type-field").val('preview');
                $("#proposal-form").submit();
                $("#preview-modal").modal('show');
            }
        });

        //Confirm Proposal Action
        $("#confirm-proposal-button").click(function(){
            $("#proposal-form").removeAttr('target');
            $("#request-type-field").val('confirm');
            $("#proposal-form").submit();
            $("#preview-modal").modal('hide');
        });

        //Update Proposal Button
        $("#update-proposal-button").click(function(){
            $("#proposal-form").submit();
        });
    },

    initEditAction: function(){
        services.forEach(service => {
            if( typeof request[service.name] != 'undefined' )
            {
                $("input#" + service.name).trigger('click');
            }
        });
    },

    createClassicCkEditor : function(id){
        ClassicEditor
        .create( $("#" + id)[0], {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList'
            ]
        })
        .then( editor => {
            this.ckeditorInstances[editor.sourceElement.id] = editor;

            if(editor.sourceElement.id == "bottom-description")
            {
                if( typeof editMode != 'undefined' && editMode == true )
                {
                    editor.setData(request['bottomDescription']);
                }
                else
                {
                    editor.setData(bottomDescription);
                }
            }
            //Set Default Content
            let originServiceName = editor.sourceElement.id.substring(0, editor.sourceElement.id.indexOf('-content'));
            if( typeof editMode != 'undefined' && editMode == true && typeof request[editor.sourceElement.id] != 'undefined' && request[editor.sourceElement.id] != null )
            {
                editor.setData(request[editor.sourceElement.id]);
            }
            else if( typeof services[originServiceName] != 'undefined' )
                editor.setData(services[originServiceName].content);
        } )
        .catch( error => {
            console.error( error );
        } );
    },

    validateForm: function(){
        if( $("input[name='clientName']").val().trim() == '' )
        {
            $.notify('Please input Client Name', {
                type: 'danger',
                animate: {
                    enter: 'animated lightSpeedIn',
                    exit: 'animated lightSpeedOut'
                }
            })
            $("input[name='clientName']").focus();
            return false;
        }
        if($("#email-contact").is(":checked") && $("#client-email").val().trim() == '' )
        {
            $.notify('Please input Email Address', { type: 'danger' });
            $("#client-email").focus();
            return false;
        }
        return true;
    },

    initPreFillActions: function(){
        $("#proposal-form").submit(function(){
            Object.keys(Proposal_Generator.ckeditorInstances).forEach((contentEditorId)=>{
                $("#" + contentEditorId).html(Proposal_Generator.ckeditorInstances[contentEditorId].getData());
            });
            return true;
        })
    },

    initTemplateChangeActions: function() {
        $(".template-type-select").select2({
            width: "220"
        });

        $(".template-type-select").change(function() {
            if ($(this).val() == 'evolution-marketing') {
                $("#cms-logo").attr('src', 'assets/images/evolution-marketing-logo.png');
            } else if ($(this).val() == 'evolution-marketing-florida') {
                $("#cms-logo").attr('src', 'assets/images/evolution-marketing-south-fl.png');
            } else if ($(this).val() == 'venice-onward') {
                $("#cms-logo").attr('src', 'assets/images/venice-onward-logo.png');
            } else if ($(this).val() == 'liquor-cms') {
                $("#cms-logo").attr('src', 'assets/images/liquor-cms-logo.png');
            } else if ($(this).val() == 'cms-max') {
                $("#cms-logo").attr('src', 'assets/images/cms-max-logo.png');
            }
        }).trigger('change');
    }
};
$(document).ready(function(){
    Proposal_Generator.init();
})
