var Website_Add_Edit_Modal = {
    selectedWebsiteId : -1,
    selectedCompletedDate : null,

    init: function() {
        this.initComponents();
        this.toggleAction();
        this.initSaveActions();
        this.initRemoveAction();
        this.initArchiveAction();
    },
    initComponents: function() {
        $('#start-date').datepicker({
            autoclose: true,
            format: "mm/yyyy",
            startView: "months",
            minViewMode: "months"
        })
        $('#control-scan-renewal-date').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: true,
            onSelect: function(dateText) {
                Website_Add_Edit_Modal.selectedScanuserRenewalDate = $("#control-scan-renewal-date").data('datepicker').getFormattedDate('yyyy-mm-dd');
            }
        });
        $('#control-scan-renewal-date').change(function(){
            Website_Add_Edit_Modal.selectedScanuserRenewalDate = $("#control-scan-renewal-date").data('datepicker').getFormattedDate('yyyy-mm-dd');
        });

        $('#add-website-modal .completed-date').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: true,
            onSelect: function(dateText) {
                Website_Add_Edit_Modal.selectedCompletedDate = $("#add-website-modal .completed-date").data('datepicker').getFormattedDate('yyyy-mm-dd');
            }
        });
        $('#add-website-modal .completed-date').change(function(){
            Website_Add_Edit_Modal.selectedCompletedDate = $("#add-website-modal .completed-date").data('datepicker').getFormattedDate('yyyy-mm-dd');
        });

        $('#add-website-modal .blog-industry-list').select2();
        $('#add-website-modal .admins-list').select2();
        $('#add-website-modal .frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .website-type-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .website-affiliate-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .service-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .support_maintenance-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .internet_marketing-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .yext-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .gsuite-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .ssl-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .hosting-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .googleAds-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .googleManagementFee-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .cmsmax_software-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .cmsmax_ecommerce_software-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .social_media_management-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .domain-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .dont_go-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .order_snapp-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .cms_max_plus-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .cms_max_ecommerce_plus-frequency-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .service-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .support_maintenance-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .internet_marketing-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .yext-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .gsuite-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .ssl-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .hosting-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .googleAds-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .googleManagementFee-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .cmsmax_software-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .cmsmax_ecommerce_software-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .social_media_management-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .domain-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .dont_go-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .order_snapp-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .cms_max_plus-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .cms_max_ecommerce_plus-availability-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .website-dns-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .website-payment-gateway-list').select2()
        $('#add-website-modal .website-email-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .website-sitemap-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .website-left-review-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .website-portfolio-list').select2({
            minimumResultsForSearch: -1
        });
        $('#add-website-modal .website-shipping-method-list').select2({
            minimumResultsForSearch: -1
        });
    },

    toggleAction: function() {
        $("#blog-client-checkbox").change(function(){
            if($(this).is(":checked"))
                $("#client-detail-info-wrapper").show();
            else
                $("#client-detail-info-wrapper").hide();
        });

        $("#sync-from-client-checkbox").change(function(){
            if($(this).is(":checked"))
                $(".manual-value-input").hide();
            else
                $(".manual-value-input").show();
        })

        $('#add-website-modal .website-email-list').change(function(){
            if( $(this).val() == "g-suite")
                $("#add-website-modal .gsuite-frequency-list").closest('div.form-group').show();
            else
                $("#add-website-modal .gsuite-frequency-list").closest('div.form-group').hide();
        })

        $("#add-website-modal .value-availability-list").change(function(){
            if( $(this).val() == 'available' ){
                $(this).closest('div.form-group').find('.manual-value-input').removeAttr("disabled");
                if( !$("#sync-from-client-checkbox").prop("checked") )
                    $(this).closest('div.form-group').find('.manual-value-input').show();
                $(this).closest('div.form-group').find('select').eq(1).prop('disabled', false);
                $(this).closest('div.form-group').find('select').eq(1).next(".select2-container").show();
                if( ['n/a', 'need-to-sell', 'not-interested', 'not-needed'].includes($(this).closest('div.form-group').find('.manual-value-input').val())  )
                    $(this).closest('div.form-group').find('.manual-value-input').val(0);
            }
            else{
                $(this).closest('div.form-group').find('.manual-value-input').attr("disabled", "disabled");
                $(this).closest('div.form-group').find('.manual-value-input').hide();
                $(this).closest('div.form-group').find('select').eq(1).prop('disabled', true);
                $(this).closest('div.form-group').find('select').eq(1).next(".select2-container").hide();
                if( $(this).val() == 'need-to-sell' )
                    $(this).closest('div.form-group').find('.manual-value-input').val('need-to-sell');
                else if( $(this).val() == 'not-interested' )
                    $(this).closest('div.form-group').find('.manual-value-input').val('not-interested');
                else if( $(this).val() == 'not-needed' )
                    $(this).closest('div.form-group').find('.manual-value-input').val('not-needed');
                else
                    $(this).closest('div.form-group').find('.manual-value-input').val('n/a');
            }
        })

        $('#add-website-modal .website-type-list').change(function(){
            if( $('#add-website-modal .website-type-list').val() == 'ecommerce' )
                $('#add-website-modal .website-shipping-method-list').closest('div.form-group').show();
            else
                $('#add-website-modal .website-shipping-method-list').closest('div.form-group').hide();
        });

        $("#add-website-modal .website-payment-gateway-list").change(function(){
            if( $("#add-website-modal .website-payment-gateway-list").next().find(".select2-selection__rendered li[title='TSYS']").length > 0 )
                $("#add-website-modal .tsys-fields-wrapper").show();
            else
                $("#add-website-modal .tsys-fields-wrapper").hide();
        })
    },

    showModal: function(websiteId) {
        Website_Add_Edit_Modal.selectedWebsiteId = websiteId;
        if( Website_Add_Edit_Modal.selectedWebsiteId == -1 ) {
            $("#website-name").val("");
            $("#website-url").val("");
            $("#target-area").val("");
            $("#google-drive").val("");
            $('#add-website-modal .blog-industry-list').val("").trigger("change");
            $('#add-website-modal .admins-list').val("").trigger("change");
            $('#add-website-modal .frequency-list').val("").trigger("change");
            $('#add-website-modal .website-type-list').val("regular").trigger("change");
            $('#add-website-modal .website-affiliate-list').val("none").trigger("change");
            $('#add-website-modal .completed-date').val("");
            Website_Add_Edit_Modal.selectedCompletedDate = null;
            Website_Add_Edit_Modal.selectedScanuserRenewalDate = null;

            //Shipping Method
            $('#add-website-modal .website-shipping-method-list').val('cms-max').trigger("change");

            // ChargeBee
            $("#chargebee-checkbox").prop("checked", false).trigger("change");

            //sync From client
            $("#sync-from-client-checkbox").prop("checked", true).trigger("change");

            //Availability for service fees
            $('#add-website-modal .service-availability-list').val('available').trigger("change");
            $('#add-website-modal .support_maintenance-availability-list').val('available').trigger("change");
            $('#add-website-modal .internet_marketing-availability-list').val('available').trigger("change");
            $('#add-website-modal .yext-availability-list').val('available').trigger("change");
            $('#add-website-modal .gsuite-availability-list').val('available').trigger("change");
            $('#add-website-modal .ssl-availability-list').val('available').trigger("change");
            $('#add-website-modal .hosting-availability-list').val('available').trigger("change");
            $('#add-website-modal .googleAds-availability-list').val('available').trigger("change");
            $('#add-website-modal .googleManagementFee-availability-list').val('available').trigger("change");
            $('#add-website-modal .cmsmax_software-availability-list').val('available').trigger("change");
            $('#add-website-modal .cmsmax_ecommerce_software-availability-list').val('available').trigger("change");
            $('#add-website-modal .social_media_management-availability-list').val('available').trigger("change");
            $('#add-website-modal .domain-availability-list').val('available').trigger("change");
            $('#add-website-modal .dont_go-availability-list').val('available').trigger("change");
            $('#add-website-modal .order_snapp-availability-list').val('available').trigger("change");
            $('#add-website-modal .cms_max_plus-availability-list').val('available').trigger("change");
            $('#add-website-modal .cms_max_ecommerce_plus-availability-list').val('available').trigger("change");

            //Frequency for services fees
            $("#add-website-modal .service-frequency-list").val(1).trigger("change");
            $("#add-website-modal .support_maintenance-frequency-list").val(1).trigger("change");
            $("#add-website-modal .internet_marketing-frequency-list").val(1).trigger("change");
            $("#add-website-modal .yext-frequency-list").val(1).trigger("change");
            $("#add-website-modal .gsuite-frequency-list").val(1).trigger("change");
            $("#add-website-modal .ssl-frequency-list").val(1).trigger("change");
            $("#add-website-modal .hosting-frequency-list").val(1).trigger("change");
            $("#add-website-modal .googleAds-frequency-list").val(1).trigger("change");
            $("#add-website-modal .googleManagementFee-frequency-list").val(1).trigger("change");
            $("#add-website-modal .cmsmax_software-frequency-list").val(1).trigger("change");
            $("#add-website-modal .cmsmax_ecommerce_software-frequency-list").val(1).trigger("change");
            $("#add-website-modal .social_media_management-frequency-list").val(1).trigger("change");
            $("#add-website-modal .domain-frequency-list").val(1).trigger("change");
            $("#add-website-modal .dont_go-frequency-list").val(1).trigger("change");
            $("#add-website-modal .order_snapp-frequency-list").val(1).trigger("change");
            $("#add-website-modal .cms_max_plus-frequency-list").val(1).trigger("change");
            $("#add-website-modal .cms_max_ecommerce_plus-frequency-list").val(1).trigger("change");

            //manual input val
            $(".manual-value-input").val(0);

            //DNS
            $("#add-website-modal .website-dns-list").val("cms-max").trigger("change");

            //Payment Gateway
            $('#add-website-modal .website-payment-gateway-list').val("").trigger("change");

            //Email
            $('#add-website-modal .website-email-list').val("").trigger("change");

            //Other Attributes
            $('#add-website-modal .website-sitemap-list').val("not-installed").trigger("change");
            $('#add-website-modal .website-left-review-list').val("").trigger("change");
            $('#add-website-modal .website-portfolio-list').val("").trigger("change");

            $("#start-date").datepicker("update", new Date());
            $("#control-scan-renewal-date").datepicker("update", new Date());

            $("#blog-client-checkbox").prop("checked", false).trigger("change");

            $('#add-website-modal #data-studio-link').val("");

            $("#add-website-modal .archive-btn").hide();
            $("#add-website-modal .unarchive-btn").hide();

            $("#add-website-modal .modal-title").html("Add Website");

            $("#add-website-modal").modal('show');
        }
        else{
            //Get Website Info from server
            $.ajax({
                type: 'GET',
                url: siteUrl + "/get-website-info",
                data: {
                    websiteId : Website_Add_Edit_Modal.selectedWebsiteId
                },
                success: function(data) {
                    if( data.status == 'success'){
                        website = data.data;
                        if( !website.blog_industry_id )
                            website.blog_industry_id = "";

                        if( !website.assignee_id )
                            website.assignee_id = "";

                        $("#website-name").val(website.name);
                        $("#website-url").val(website.website);
                        $("#target-area").val(website.target_area);
                        $("#google-drive").val(website.drive);
                        $('#add-website-modal .blog-industry-list').val(website.blog_industry_id).trigger("change");
                        $('#add-website-modal .admins-list').val(website.assignee_id).trigger("change");
                        $('#add-website-modal .frequency-list').val(website.frequency).trigger("change");
                        $('#add-website-modal .website-type-list').val(website.type).trigger("change");
                        $('#add-website-modal .website-affiliate-list').val(website.affiliate).trigger("change");
                        $("#start-date").datepicker("update", website.start_date);
                        if( website.completed_at == null )
                            $('#add-website-modal .completed-date').val();
                        else
                            $('#add-website-modal .completed-date').datepicker("update", new Date(website.completed_at));
                        Website_Add_Edit_Modal.selectedCompletedDate = website.completed_at;

                        $('#add-website-modal #mid').val(website.mid);
                        $('#add-website-modal #control-scan-user').val(website.control_scan_user);
                        $('#add-website-modal #control-scan-pass').val(website.control_scan_pass);
                        $('#add-website-modal #data-studio-link').val(website.data_studio_link);

                        if( website.control_scan_renewal_date == null )
                            $('#control-scan-renewal-date').val();
                        else
                            $('#control-scan-renewal-date').datepicker("update", new Date(website.control_scan_renewal_date));
                        Website_Add_Edit_Modal.selectedScanuserRenewalDate = website.control_scan_renewal_date;

                        $("#blog-client-checkbox").prop("checked", website.is_blog_client).trigger("change");

                        $("#chargebee-checkbox").prop("checked", website.chargebee).trigger("change");
                        
                        //sync From client
                        $("#sync-from-client-checkbox").prop("checked", website.sync_from_client).trigger("change");

                        //Availability for service fees
                        $('#add-website-modal .service-availability-list').val(website.service == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .support_maintenance-availability-list').val(website.support_maintenance == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .internet_marketing-availability-list').val(website.internet_marketing == -1 ? 'not-available' : 'available').trigger("change");
                        let yextValue = "available";
                        if( website.yext == -1 )
                            yextValue = 'not-needed';
                        else if( website.yext == -3 )
                            yextValue = 'need-to-sell';
                        else if( website.yext == -4 )
                            yextValue = 'not-interested';
                        $('#add-website-modal .yext-availability-list').val(yextValue).trigger("change");
                        $('#add-website-modal .gsuite-availability-list').val(website.g_suite == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .ssl-availability-list').val(website.ssl == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .hosting-availability-list').val(website.hosting == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .googleAds-availability-list').val(website.googleAds == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .googleManagementFee-availability-list').val(website.googleManagementFee == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .cmsmax_software-availability-list').val(website.cmsmax_software == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .cmsmax_ecommerce_software-availability-list').val(website.cmsmax_ecommerce_software == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .social_media_management-availability-list').val(website.social_media_management == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .domain-availability-list').val(website.domain == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .dont_go-availability-list').val(website.dont_go == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .order_snapp-availability-list').val(website.order_snapp == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .cms_max_plus-availability-list').val(website.cms_max_plus == -1 ? 'not-available' : 'available').trigger("change");
                        $('#add-website-modal .cms_max_ecommerce_plus-availability-list').val(website.cms_max_ecommerce_plus == -1 ? 'not-available' : 'available').trigger("change");

                        //Frequency for services fees
                        $("#add-website-modal .support_maintenance-frequency-list").val(website.support_maintenance_frequency).trigger("change");
                        $("#add-website-modal .internet_marketing-frequency-list").val(website.internet_marketing_frequency).trigger("change");
                        $("#add-website-modal .service-frequency-list").val(website.service_frequency).trigger("change");
                        $("#add-website-modal .yext-frequency-list").val(website.yext_frequency).trigger("change");
                        $("#add-website-modal .gsuite-frequency-list").val(website.g_suite_frequency).trigger("change");
                        $("#add-website-modal .ssl-frequency-list").val(website.ssl_frequency).trigger("change");
                        $("#add-website-modal .hosting-frequency-list").val(website.hosting_frequency).trigger("change");
                        $("#add-website-modal .googleAds-frequency-list").val(website.googleAds_frequency).trigger("change");
                        $("#add-website-modal .googleManagementFee-frequency-list").val(website.googleManagementFee_frequency).trigger("change");
                        $("#add-website-modal .cmsmax_software-frequency-list").val(website.cmsmax_software_frequency).trigger("change");
                        $("#add-website-modal .cmsmax_ecommerce_software-frequency-list").val(website.cmsmax_ecommerce_software_frequency).trigger("change");
                        $("#add-website-modal .social_media_management-frequency-list").val(website.social_media_management_frequency).trigger("change");
                        $("#add-website-modal .domain-frequency-list").val(website.domain_frequency).trigger("change");
                        $("#add-website-modal .dont_go-frequency-list").val(website.dont_go_frequency).trigger("change");
                        $("#add-website-modal .order_snapp-frequency-list").val(website.order_snapp_frequency).trigger("change");
                        $("#add-website-modal .cms_max_plus-frequency-list").val(website.cms_max_plus_frequency).trigger("change");
                        $("#add-website-modal .cms_max_ecommerce_plus-frequency-list").val(website.cms_max_ecommerce_plus_frequency).trigger("change");

                        //manual input val
                        $("#manual-service-value").val(website.service == -1 ? 'n/a' : website.service);
                        $("#manual-support_maintenance-value").val(website.support_maintenance == -1 ? 'n/a' : website.support_maintenance);
                        $("#manual-internet_marketing-value").val(website.internet_marketing == -1 ? 'n/a' : website.internet_marketing);
                        $("#manual-yext-value").val(website.yext < 0 ? yextValue : website.yext);
                        $("#manual-gsuite-value").val(website.g_suite == -1 ? 'n/a' : website.g_suite);
                        $("#manual-ssl-value").val(website.ssl == -1 ? 'n/a' : website.ssl);
                        $("#manual-hosting-value").val(website.hosting == -1 ? 'n/a' : website.hosting);
                        $("#manual-googleAds-value").val(website.googleAds == -1 ? 'n/a' : website.googleAds);
                        $("#manual-googleManagementFee-value").val(website.googleManagementFee == -1 ? 'n/a' : website.googleManagementFee);
                        $("#manual-cmsmax_software-value").val(website.cmsmax_software == -1 ? 'n/a' : website.cmsmax_software);
                        $("#manual-cmsmax_ecommerce_software-value").val(website.cmsmax_ecommerce_software == -1 ? 'n/a' : website.cmsmax_ecommerce_software);
                        $("#manual-social_media_management-value").val(website.social_media_management == -1 ? 'n/a' : website.social_media_management);
                        $("#manual-domain-value").val(website.domain == -1 ? 'n/a' : website.domain);
                        $("#manual-dont_go-value").val(website.dont_go == -1 ? 'n/a' : website.dont_go);
                        $("#manual-order_snapp-value").val(website.order_snapp == -1 ? 'n/a' : website.order_snapp);
                        $("#manual-cms_max_plus-value").val(website.cms_max_plus == -1 ? 'n/a' : website.cms_max_plus);
                        $("#manual-cms_max_ecommerce_plus-value").val(website.cms_max_ecommerce_plus == -1 ? 'n/a' : website.cms_max_ecommerce_plus);
                        

                        //DNS
                        $("#add-website-modal .website-dns-list").val(website.dns).trigger("change");

                        //Payment Gateway
                        $('#add-website-modal .website-payment-gateway-list').val(website.payment_gateway == null ? "" : website.payment_gateway).trigger("change");

                        //Email
                        $('#add-website-modal .website-email-list').val(website.email).trigger("change");

                        //Other Attributes
                        $('#add-website-modal .website-sitemap-list').val(website.sitemap).trigger("change");
                        $('#add-website-modal .website-left-review-list').val(website.left_review).trigger("change");
                        $('#add-website-modal .website-portfolio-list').val(website.on_portfolio).trigger("change");

                        //Shipping Method
                        $('#add-website-modal .website-shipping-method-list').val(website.shipping_method).trigger("change");

                        if( website.archived ){
                            $("#add-website-modal .archive-btn").hide();
                            $("#add-website-modal .unarchive-btn").show();
                            if( website.client_archived )
                                $("#add-website-modal .unarchive-btn").attr('disabled', 'disabled');
                            else
                                $("#add-website-modal .unarchive-btn").removeAttr('disabled', 'disabled');
                        }
                        else{
                            $("#add-website-modal .archive-btn").show();
                            $("#add-website-modal .unarchive-btn").hide();
                        }

                        $("#add-website-modal .modal-title").html("Edit Website");

                        $("#add-website-modal").modal('show');
                    }
                }
            });
        }
    },

    initSaveActions: function() {
        $("#add-website-modal .confirm-btn").click(function(){
            if( !Website_Add_Edit_Modal.validateForm() )
                return;

            var disabled = $("#website-form").find(':input:disabled').removeAttr('disabled');
            formData = $("#website-form").serializeArray();
            disabled.attr('disabled','disabled');
            ajaxData = {};
            $.map(formData, function(n, i){
                ajaxData[n['name']] = n['value'];
            });
            ajaxData['_token']  = csrf_token;
            ajaxData['websiteId']   = Website_Add_Edit_Modal.selectedWebsiteId;
            if( typeof clientId != 'undefined' )
                ajaxData['client_id']   = clientId;
            ajaxData['payment_gateway'] = $('#add-website-modal .website-payment-gateway-list').val();
            ajaxData['completed_at'] = Website_Add_Edit_Modal.selectedCompletedDate;
            ajaxData['control_scan_renewal_date'] = Website_Add_Edit_Modal.selectedScanuserRenewalDate;

            $.ajax({
                type : 'POST',
                url : siteUrl + "/add-website",
                data: ajaxData,
                success: function(data){
                    if(data.status == 'success')
                        location.reload();
                }
            });
        });
    },

    initRemoveAction: function(){
        $(document).on('click', '#add-website-modal .remove-btn', function(){
            websiteId = Website_Add_Edit_Modal.selectedWebsiteId;
            $("#delete-website-modal").attr('data-website-id', websiteId);
            $("#add-website-modal").modal('hide');
            $("#delete-website-modal").modal('show');
        });

        $("#delete-website-modal .confirm-btn").click(function(){
            websiteId = $("#delete-website-modal").attr('data-website-id');
            $.ajax({
                type : 'POST',
                url : siteUrl + '/delete-website',
                data : {
                    _token : csrf_token,
                    websiteId
                },
                success: function(data){
                    if( data.status == 'success' ){
                        location.reload();
                    }
                }
            });
        });
    },

    initArchiveAction: function(){
        $('#add-website-modal .archive-btn').click(function(){
            websiteId = Website_Add_Edit_Modal.selectedWebsiteId;
            $("#archive-website-modal").attr('data-website-id', websiteId);
            $("#add-website-modal").modal('hide');
            $("#archive-website-modal").modal('show');
        });

        $("#archive-website-modal .confirm-btn").click(function(){
            websiteId = $("#archive-website-modal").attr('data-website-id');
            $.ajax({
                type : 'POST',
                url : siteUrl + '/archive-website',
                data : {
                    _token : csrf_token,
                    websiteId
                },
                success: function(data){
                    if( data.status == 'success' ){
                        location.reload();
                    }
                }
            });
        });

        $('#add-website-modal .unarchive-btn').click(function(){
            websiteId = Website_Add_Edit_Modal.selectedWebsiteId;
            $.ajax({
                type : 'POST',
                url : siteUrl + '/un-archive-website',
                data : {
                    _token : csrf_token,
                    websiteId
                },
                success: function(data){
                    if( data.status == 'success' ){
                        location.reload();
                    }
                }
            });
        });
    },

    validateForm: function() {
        if( !$("#website-form")[0].checkValidity() ){
            $("#website-form")[0].reportValidity();
            return false;
        }

        if( $('#add-website-modal .blog-industry-list').val() == "" ){
            $('#add-website-modal .blog-industry-list').select2('open');
            return false;
        }

        if( $("#blog-client-checkbox").prop("checked") && $('#add-website-modal .admins-list').val() == "" ){
            $('#add-website-modal .admins-list').select2('open');
            return false;
        }

        if( $("#blog-client-checkbox").prop("checked") && $('#add-website-modal .frequency-list').val() == "" ){
            $('#add-website-modal .frequency-list').select2('open');
            return false;
        }

        // if( $('#add-website-modal .website-payment-gateway-list').val() == "" ){
        //     $('#add-website-modal .website-payment-gateway-list').select2('open');
        //     return false;
        // }
        /*
        if( $('#add-website-modal .website-email-list').val() == "" ){
            $('#add-website-modal .website-email-list').select2('open');
            return false;
        }
        */

        //check Shipping Method
        if( $('#add-website-modal .website-type-list').val() == 'ecommerce' && $('#add-website-modal .website-shipping-method-list').val() == "" ){
            $('#add-website-modal .website-shipping-method-list').select2('open');
            return false;
        }
        return true;
    }

};

$(document).ready(function(){
    Website_Add_Edit_Modal.init();
})
