var Social_Media_Filter = {
    init: () => {
        $("#websites-status-filter").select2({
            minimumResultsForSearch: -1
        });

        $("#websites-status-filter").change(function() {
            location.href = siteUrl + "/social-media?status_filter=" + $(this).val();
        })

        $(".budget-box").click(function() {
            $("#total-budget-by-assignee-modal").modal('show');
        })
    }
};

$(document).ready(function(){
    Social_Media_Filter.init();
})