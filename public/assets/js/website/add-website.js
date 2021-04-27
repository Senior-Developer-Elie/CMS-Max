var AddWebsite = {
    init: () => {
        AddWebsite.initComponents();
        AddWebsite.initBlogAction();
        AddWebsite.initArchiveAction();
    },

    initComponents: () => {
        $("select[name='payment_gateway[]'").select2({ width: '100%'});
        $("select[name='client_id'").select2({ width: '100%'});
        

        $('input[name=completed_at]').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: true
        });

        $('input[name=start_date]').datepicker({
            autoclose: true,
            format: "mm/yyyy",
            startView: "months",
            minViewMode: "months"
        })
        
        $('input[name=control_scan_renewal_date]').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: true,
        });
    },

    initBlogAction: () => {
        $("input[name=is_blog_client]").change(function() {

            if ($(this).prop('checked')) {
                $("#client-detail-info-wrapper").show();
            } else {
                $("#client-detail-info-wrapper").hide();
            }
        }).trigger('change');
    },

    initArchiveAction: () => {
        $("#archive-button").click(function() {
            $("#archive-website-form").submit();
        })

        $("#restore-button").click(function() {
            $("#restore-website-form").submit();
        })
    },
};

$(document).ready(function(){
    AddWebsite.init();
})