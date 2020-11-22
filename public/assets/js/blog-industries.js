var Blog_Industries = {
    init: function(){

        this.initAddEditActions();
        this.initDeleteActions();
        this.initTooltip();
    },

    initTooltip: function(){
        $('[data-toggle="tooltip"]').tooltip()
    },

    /**Add Blog Industries Action */
    initAddEditActions: function()
    {
        //Add blog industry
        $("#add-blog-industry-button").click(function(){

            $("#add-blog-industry-modal").attr("data-blog-industry-id", "-1");
            $("#add-blog-industry-modal .blog-industry-name").val("");

            $("#add-blog-industry-modal").modal('show');
        })

        //Edit Blog Industry
        $(".edit-blog-industry-button").click(function(){

            blogIndustryId = $(this).closest('tr').attr("data-blog-industry-id");

            $("#add-blog-industry-modal").attr("data-blog-industry-id", blogIndustryId);
            $("#add-blog-industry-modal .blog-industry-name").val($(this).closest('tr').attr("data-blog-industry-name"));

            $("#add-blog-industry-modal").modal('show');
        });

        //Confirm Blog Add Action
        $("#add-blog-industry-modal .confirm-btn").click(function(){

            let blogIndustryId = $("#add-blog-industry-modal").attr("data-blog-industry-id");

            //validate
            if( $("#add-blog-industry-modal .blog-industry-name").val().trim() == "" ) {
                $("#add-blog-industry-modal .blog-industry-name").focus();
                return;
            }

            ajaxData = {
                '_token'            : csrf_token,
                'blogIndustryId'    : blogIndustryId,
                'name'              : $("#add-blog-industry-modal .blog-industry-name").val(),
            };

            $.ajax({
                type: 'POST',
                url: siteUrl + '/add-blog-industry',
                data: ajaxData,
                success: function(data){
                    if(data.status == 'success'){
                        location.reload();
                    }
                }
            });
        });
    },

    /**Delete Blog Industry Actons */
    initDeleteActions: function() {

        $(".delete-blog-industry-button").click(function(){
            let blogIndustryId = $(this).closest('tr').attr("data-blog-industry-id");
            $("#delete-blog-industry-modal").attr("data-blog-industry-id", blogIndustryId);
            $("#delete-blog-industry-modal").modal('show');
        });

        $("#delete-blog-industry-modal .confirm-btn").click(function(){
            let blogIndustryId = $("#delete-blog-industry-modal").attr("data-blog-industry-id");
            ajaxData = {
                '_token'            : csrf_token,
                'blogIndustryId'    : blogIndustryId,
            };

            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-blog-industry',
                data: ajaxData,
                success: function(data){
                    if(data.status == 'success'){
                        location.reload();
                    }
                }
            });
        });
    }
};

$(document).ready(function(){
    Blog_Industries.init();
})
