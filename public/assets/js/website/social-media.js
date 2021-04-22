var Websites_Social_Media = {
    init: () => {
        Websites_Social_Media.initToolTip();
        Websites_Social_Media.iniWebsiteShowAction();
        Websites_Social_Media.initWebsiteIconActions();
    },

    initToolTip: () => {
        $('[data-toggle="tooltip"]').tooltip()
    },

    iniWebsiteShowAction: () => {
        $(document).on("click", ".website-row .social-grid-name-cell", function() {
            let websiteId = $(this).closest(".social-grid-row").attr("data-website-id");
            Social_Details_Widget.showWebsite(websiteId);
        })
    },

    initWebsiteIconActions: () => {
        $(".website-row .website-icon").click(function(e) {
            e.stopPropagation();
        })
    },
};

var Social_Details_Widget = {

    website: false,
    socialMediaPlansSource : [],
    socialMediaStagesSource: [],
    socialFieldNames: [
        'linkedin_url',
        'youtube_url',
        'twitter_url',
        'facebook_url',
        'instagram_url',
        'pinterest_url',
    ],

    init: function() {
        Social_Details_Widget.prettifySources();
        Social_Details_Widget.initSocialMediaCheckListsActions();
        Social_Details_Widget.initToolBarButtonActions();
        Social_Details_Widget.initMarkAsInactiveAction();
    },

    prettifySources: function() {
        Social_Details_Widget.socialMediaPlansSource = Object.keys(socialMediaPlans).reduce((acc, cur) => {
            acc.push({
                value: cur,
                text: socialMediaPlans[cur]
            });
            return acc;
        }, []);

        Social_Details_Widget.socialMediaStagesSource = socialMediaStages.map((stage) => {
            return {
                'value': stage.id,
                'text': stage.name,
            }
        });
    },

    showWebsite: function(websiteId){

        $("#website-details-wrapper").show();
        $("#website-details-wrapper").animate({left: '60%'}, 350, function(){
            $(".social-grid").css("width", "60%");
        });

        // Show loading
        $('#website-details-wrapper').waitMe({
            effect : 'bounce',
            text : '',
            bg : 'rgba(255,255,255,0.7)',
            color : '#000'
        });

        // Get Website Details
        $.ajax({
            type: "GET",
            url: siteUrl + "/social-media/website-details/" + websiteId,
            success: function(response){
                if( response.status == "success" ) {
                    Social_Details_Widget.setWebsite(response.website);
                }
            }
        })
    },

    setWebsite: function(website){
        //Remove Selected
        if( Social_Details_Widget.website != false ){
            $(".website-row[data-website-id='" + Social_Details_Widget.website.id + "']").removeClass("selected");
        }
        Social_Details_Widget.website = website;

        //Add selected
        $(".website-row[data-website-id='" + Social_Details_Widget.website.id + "']").addClass("selected");

        $('#website-details-wrapper').waitMe('hide');

        Social_Details_Widget.setVariables();
        Social_Details_Widget.setInlineEdit();
    },

    setVariables: function() {
        let website = Social_Details_Widget.website;

        $("#website-details-wrapper .stage-value").attr('data-value', website.social_media_stage_id);
        $("#website-details-wrapper .website-name-value").text(website.name);
        $("#website-details-wrapper .website-edit-link").attr('href', siteUrl + "/websites/" + website.id + "/edit");
        $("#website-details-wrapper .client-name-value").text(website.client.name);
        $("#website-details-wrapper .client-edit-link").attr('href', siteUrl + "/client-history?clientId=" + website.client.id);
        $("#website-details-wrapper .website-url-value").text(website.website);
        $("#website-details-wrapper .website-url-link").attr('href', '//' + website.website);
        $("#website-details-wrapper .budget-value").text("$" + (website.social_ad_spend + website.social_management_fee));
        $("#website-details-wrapper .social-plan-value").attr('data-value', website.manual_social_plan);
        $("#website-details-wrapper .ad-spend-value").attr('data-value', website.social_ad_spend);
        $("#website-details-wrapper .management-fee-value").attr('data-value', website.social_management_fee);
        $("#website-details-wrapper .notes-value").attr('data-value', website.social_media_notes);

        let websiteSocialMediaKeys = Social_Details_Widget.website.socialMediaCheckLists.reduce((acc, cur) => {
            return [...acc, cur.key];
        }, [])

        $("#website-details-wrapper input.check-list-option").each(function() {
            const socialMediaKey = $(this).attr('data-check-list-key');
            if (websiteSocialMediaKeys.includes(socialMediaKey)) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        })

        // Set social link icons
        $("#website-details-wrapper .social-icon").hide();
        Social_Details_Widget.socialFieldNames.forEach((socialField) => {
            if (website[socialField]) {
                $("#website-details-wrapper .social-icon[data-field-name='" + socialField + "']").show();
                $("#website-details-wrapper .social-icon[data-field-name='" + socialField + "']").attr('href', website[socialField]);
            }
        })
    },

    setInlineParams: function() {
        $.fn.editable.defaults.send = "always";
        $.fn.editable.defaults.ajaxOptions = {
            type : 'POST'
        };
        $.fn.editable.defaults.url = siteUrl+"/update-website-attribute";
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.params = function(params) {
            params._token = csrf_token;
            return params;
        };
        $.fn.editable.defaults.onblur = 'submit';
        $.fn.editable.defaults.showbuttons = false;
        $.fn.editable.defaults.inputclass = 'attribute-edit-input';
        $.fn.editable.defaults.pk = Social_Details_Widget.website.id;
    },

    setInlineEdit: function() {
        Social_Details_Widget.setInlineParams();

        // Stage
        $("#website-details-wrapper .stage-value").editable("destroy");
        $("#website-details-wrapper .stage-value").editable({
            type        : 'select',
            source      : Social_Details_Widget.socialMediaStagesSource,
            name        : 'social_media_stage_id',
        });
        $('#website-details-wrapper .stage-value').editable('setValue', $("#website-details-wrapper .stage-value").attr('data-value'));

        // Social Manual Plan
        $("#website-details-wrapper .social-plan-value").editable("destroy");
        $("#website-details-wrapper .social-plan-value").editable({
            type        : 'select',
            source      : Social_Details_Widget.socialMediaPlansSource,
            name        : 'manual_social_plan',
        });
        $('#website-details-wrapper .social-plan-value').editable('setValue', $("#website-details-wrapper .social-plan-value").attr('data-value'));

        // Social Ad Spend
        $("#website-details-wrapper .ad-spend-value").editable("destroy");
        $("#website-details-wrapper .ad-spend-value").editable({
            type        : 'text',
            name        : 'social_ad_spend',
            display     : function( value){
                $(this).html("$" + value);
            }
        });
        $('#website-details-wrapper .ad-spend-value').editable('setValue', $("#website-details-wrapper .ad-spend-value").attr('data-value'));

        // Social Management Fee
        $("#website-details-wrapper .management-fee-value").editable("destroy");
        $("#website-details-wrapper .management-fee-value").editable({
            type        : 'text',
            name        : 'social_management_fee',
            display     : function( value){
                $(this).html("$" + value);
            }
        });
        $('#website-details-wrapper .management-fee-value').editable('setValue', $("#website-details-wrapper .management-fee-value").attr('data-value'));

        // Social Management Fee
        $("#website-details-wrapper .notes-value").editable("destroy");
        $("#website-details-wrapper .notes-value").editable({
            type        : 'textarea',
            name        : 'social_media_notes',
        });
        $('#website-details-wrapper .notes-value').editable('setValue', $("#website-details-wrapper .notes-value").attr('data-value'));

        // Update budget
        $("#website-details-wrapper .ad-spend-value").on('save', function(e, params) {
            let value = parseFloat(params.newValue);
            value = value > 0 ? value : 0;
            Social_Details_Widget.website.social_ad_spend = value;

            Social_Details_Widget.updateBudgetValue();
        });
        $("#website-details-wrapper .management-fee-value").on('save', function(e, params) {
            let value = parseFloat(params.newValue);
            value = value > 0 ? value : 0;
            Social_Details_Widget.website.social_management_fee = value;

            Social_Details_Widget.updateBudgetValue();
        });

        // Update stage
        $("#website-details-wrapper .stage-value").on('save', function(e, params) {
            let stageId = parseFloat(params.newValue);
            
            let websiteRow = $(".website-row[data-website-id='" + Social_Details_Widget.website.id + "']").detach();
            $(".social-grid-stage-wrapper[data-stage-id='" + stageId + "']").find('.social-grid-stage-body').append(websiteRow);
        });
    },

    updateBudgetValue: function() {
        $("#website-details-wrapper .budget-value").text("$" + (Social_Details_Widget.website.social_ad_spend + Social_Details_Widget.website.social_management_fee));
    },

    initSocialMediaCheckListsActions: function() {
        $("#website-details-wrapper input.check-list-option").change(function() {
            const ajaxData = {
                social_media_key: $(this).attr('data-check-list-key'),
                value: $(this).prop('checked') ? 'on' : 'off',
                _token: csrf_token,
            }

            $.ajax({
                type: "POST",
                url: siteUrl + "/social-media/update-social-media-checklist/" + Social_Details_Widget.website.id,
                data: ajaxData,
                success: function(response) {
                    if (response.status == 'success') {
                        if (ajaxData.value == 'off') {
                            Social_Details_Widget.removeSocialMediaKey(ajaxData.social_media_key);
                        } else {
                            Social_Details_Widget.addSocialMediaKey(response.websiteSocialMediaCheckList);
                        }
                    }
                }
            })
        })
    },

    initToolBarButtonActions: function() {
        $("#website-details-wrapper .header-tool-button.hide-button").click(function(){
            Social_Details_Widget.hideDetailsWidget();
        })
    },

    hideDetailsWidget: function() {
        $(".website-row").removeClass("selected");
        $("#website-details-wrapper").animate({left:'100%'}, 350, function(){
            $("#website-details-wrapper").hide();
            $(".social-grid").css("width", "100%");
        });

        //Replace Url without refreshing page
        Social_Details_Widget.website = false;
    },

    initMarkAsInactiveAction: function() {
        $("#website-details-wrapper .header-tool-button.mark-inactive-button").click(function() {
            $("#mark-as-inactive-modal .website-name").text(Social_Details_Widget.website.name);
            $("#mark-as-inactive-modal").modal('show');
        })

        $("#mark-as-inactive-modal .confirm-btn").click(function() {
            $.ajax({
                type: "POST",
                url: siteUrl + "/social-media/update-social-media-archived/" + Social_Details_Widget.website.id,
                data: {
                    _token: csrf_token,
                    value: 'archived',
                },
                success: function(response) {
                    $("#mark-as-inactive-modal").modal('hide');
                    $(".website-row[data-website-id='" + Social_Details_Widget.website.id + "']").remove();
                    Social_Details_Widget.hideDetailsWidget();
                }
            })            
        })
    },

    removeSocialMediaKey: function(socialMediaKey) {
        Social_Details_Widget.website.socialMediaCheckLists = Social_Details_Widget.website.socialMediaCheckLists.filter((checkList) => checkList.key != socialMediaKey);
        Social_Details_Widget.updateProgressCount();
    },

    addSocialMediaKey: function(websiteSocialMediaCheckList) {
        Social_Details_Widget.website.socialMediaCheckLists.push(websiteSocialMediaCheckList);
        Social_Details_Widget.updateProgressCount();
    },
    
    updateProgressCount: function() {
        $(".website-row[data-website-id='" + Social_Details_Widget.website.id + "']").find(".social-media-checklist-count-value").text(Social_Details_Widget.website.socialMediaCheckLists.length);
    }
};

$(document).ready(function(){
    Websites_Social_Media.init();
    Social_Details_Widget.init();
})