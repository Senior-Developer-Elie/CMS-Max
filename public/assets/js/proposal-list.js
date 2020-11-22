$(document).ready(function(){
    proposalTable = $('#proposal-list').DataTable({
        "order": [[ 0, "asc" ]],
        'paging'      : false,
        'searching'   : false
    });
})

$("#proposal-filter").change(function(){
    location.href = siteUrl + "/proposal-list?type=" + $(this).val();
});
