var Website_List = {
    init: function(){

        //init datatable
        Website_List.initDataTable();

        //init tooltip
        $('[data-toggle="tooltip"]').tooltip();

        this.initInlieEditActions();

        //init website add edit action
        this.initWebsiteAddEditAction();
        this.initExportWebsitesBudget();
    },

    initDataTable: function(){
        //init Datatable
        clientListTable = $('#website-list-table').DataTable({
            "order"     : [[ 0, "asc" ]],
            'paging'    : true,
            'searching' : true,
            "pageLength": -1,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columnDefs: [
                {targets: [2], type: 'sortme'},
                {targets: [3, 4, 5, 6, 7, 8, 9, 10, 11], type: 'sorttypevalue'},
            ],
            fixedHeader: true,
            dom: 'Blfrti',
            buttons: [
                 'colvis'
            ]
        });
        $.fn.dataTable.ext.type.order['sortme-pre'] = function (a, b) {
            if( a.trim().startsWith("<a") )
                return parseFloat($(a).attr("data-value"));
            else if(a.trim().startsWith("<span") && $(a).hasClass('hours-value'))
            {
                if( $(a).html().trim().toLowerCase() == 'n/a' )
                    return -1;
                return parseFloat($(a).html().trim());
            }
            else{
                if( a.trim().startsWith("$") )
                    return parseFloat(a.substr(1, a.length-1));
                else
                    return -1;
            }
        };
        $.fn.dataTable.ext.type.order['sorttypevalue-pre'] = function (a, b) {
            if( a.trim().startsWith("<a") )
                return $(a).attr("data-value");

            return "";
        };
    },

    initInlieEditActions: function(){

        //X Editable Options
        $.fn.editable.defaults.send = "always";
        $.fn.editable.defaults.ajaxOptions = {
            type : 'POST'
        };
        $.fn.editable.defaults.url = siteUrl+"/update-website-attribute";
        $.fn.editable.defaults.mode = 'popup';
        $.fn.editable.defaults.params = function(params) {
            params._token = csrf_token;
            return params;
        };

        //DNS
        let prettyDnsSource = allDNSTypes;//Website_List.makePrettySource(allDNSTypes);
        $("a.website-dns").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                pk          : websiteId,
                source      : prettyDnsSource,
                showbuttons : false,
                name        : 'dns',
            });
        })

        //Yext
        let prettyYext = Website_List.makePrettySource(allYextTypes);
        $("a.website-yext").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                pk          : websiteId,
                source      : prettyYext,
                showbuttons : false,
                name        : 'listings_management',
                url         : siteUrl + '/update-website-product-value',
                display     : function( value, sourceData ){
                    if( value == "0" )
                    {
                        $(this).html("$" + value);
                    }
                    else if( $.fn.editableutils.itemsByValue(value, sourceData).length > 0 ) {
                        $(this).html($.fn.editableutils.itemsByValue(value, sourceData)[0].text);
                    }
                    else if( parseFloat(value) >= 0 )
                        $(this).html("$" + value);
                    else
                        $(this).empty();
                }
            });
        })

        //Payment Gateway
        let prettyPaymentGatewaySource = Website_List.makePrettySource(allPaymentGateways);
        $("a.website-payment-gateway").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'checklist',
                pk          : websiteId,
                source      : prettyPaymentGatewaySource,
                name        : 'payment_gateway',
            });
        })

        //Email
        let prettyEmailSource = Website_List.makePrettySource(allEmailTypes);
        $("a.website-email").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            gSuiteString = $(element).attr('data-price');
            $(element).editable({
                type        : 'select',
                showbuttons : false,
                pk          : websiteId,
                source      : prettyEmailSource,
                name        : 'email',
                display     : function( value, sourceData ){
                    if( $.fn.editableutils.itemsByValue(value, sourceData).length > 0 ) {
                        if( value == "g-suite" ){
                            $(this).html($.fn.editableutils.itemsByValue(value, sourceData)[0].text + " - " + gSuiteString);
                        }
                        else
                            $(this).html($.fn.editableutils.itemsByValue(value, sourceData)[0].text);
                    }
                    else
                        $(this).empty();
                }
            });
        })

        //Left Review
        let prettyLeftReviewSource = Website_List.makePrettySource(allLeftReviewTypes);
        $("a.website-left-review").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                showbuttons : false,
                pk          : websiteId,
                source      : prettyLeftReviewSource,
                name        : 'left_review',
            });
        })

        //On Portfolio
        let prettyOnPortfolioSource = Website_List.makePrettySource(allPortfolioTypes);
        $("a.website-on-portfolio").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                showbuttons : false,
                pk          : websiteId,
                source      : prettyOnPortfolioSource,
                name        : 'on_portfolio',
            });
        })

        //Type
        let prettyWebsiteTypeSource = Website_List.makePrettySource(allWebsiteTypes);
        $("a.website-type").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                showbuttons : false,
                pk          : websiteId,
                source      : prettyWebsiteTypeSource,
                name        : 'type',
                display     : function( value, sourceData ){
                    if( $.fn.editableutils.itemsByValue(value, sourceData).length > 0 ) {
                        let htmlContent = $.fn.editableutils.itemsByValue(value, sourceData)[0].text;
                        if( value == 'ecommerce' )
                            htmlContent += "<br>" + allShippingMethodTypes[$(this).attr('data-shipping-method')];
                        $(this).html(htmlContent);
                    }
                    else{
                        $(this).empty();
                    }
                }
            });
        })

        //Affiliate
        let prettyAffiliateSource = Website_List.makePrettySource(allAffiliateTypes);
        $("a.website-affiliate").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                showbuttons : false,
                pk          : websiteId,
                source      : prettyAffiliateSource,
                name        : 'affiliate',
            });
        })

        //Target Area
        $("a.website-target-area").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'text',
                pk          : websiteId,
                name        : 'target_area',
            });
        })

        //Blog Industry
        $("a.website-industry").each(function(index, element){
            websiteId = $(element).closest('tr').attr('data-website-id');
            $(element).editable({
                type        : 'select',
                showbuttons : false,
                pk          : websiteId,
                source      : allIndustries,
                name        : 'blog_industry_id',
            });
        })
    },

    initWebsiteAddEditAction: function(){
        //Add Website Button
        $("#add-website-button").click(function(){
            Website_Add_Edit_Modal.showModal(-1);
        });

        //Edit Website Action
        $(document).on('click', '.website-url', function(e){
            e.preventDefault();
            websiteId = $(this).closest('tr').attr('data-website-id');
            Website_Add_Edit_Modal.showModal(websiteId);
        });
    },

    makePrettySource: function(object){
        let array = [];
        Object.keys(object).forEach(key => {
            array.push({
                value: key,
                text : object[key]
            })
        });

        array.sort( (a, b) => {
            return a.text.localeCompare(b.text);
        } );

        return array;
    },

    initExportWebsitesBudget: function() {
        $("#export-websites-budget-button").click(function() {
            $("#websites-budget-export-form").submit();
        })
    }
};

$(document).ready(function(){
    Website_List.init();
})
