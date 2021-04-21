var Social_Media_Filter = {
    init: () => {
        $("#websites-status-filter").select2({
            minimumResultsForSearch: -1
        });

        $("#websites-status-filter").change(function() {
            location.href = siteUrl + "/social-media?status_filter=" + $(this).val();
        })
    }
};

$(document).ready(function(){
    Social_Media_Filter.init();
})