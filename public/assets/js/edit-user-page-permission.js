var Edit_Page_Permissions = {

    init: function(){

        //Page Click Action
        $(".page-item").click(function(){
            $(this).toggleClass('selected');
            if( !$(this).hasClass('parent-page-item') ) {
                Edit_Page_Permissions.refreshParentSelected();
            }
            else{
                if( $(this).hasClass("selected") ){
                    $(this).closest(".page-item-container").find(".page-item").addClass("selected");
                }
                else
                    $(this).closest(".page-item-container").find(".page-item").removeClass("selected");
            }
        });

        //Page Permission Update Btn
        $("#page-permission-update-btn").click(function(){

            let ajaxData = {
                _token: csrf_token,
                userId,
                page_permissions: Edit_Page_Permissions.getActivePagePermission()
            };

            $.ajax({
                type : "POST",
                url : siteUrl + "/update-user-page-permissions",
                data : ajaxData,
                success: function(data){
                    if( data.status == "success" ) {
                        $.notify('Page permissions updated successfully!', { type: 'success' });
                    }
                }
            })
        });

        Edit_Page_Permissions.refreshParentSelected();
    },

    refreshParentSelected: function(){
        $(".page-item.parent-page-item").each(function(index, element){
            let sugPagesWrapper = $(element).closest(".page-item-container").find(".sub-page-items-wrapper");
            if( sugPagesWrapper.find(".page-item").length == sugPagesWrapper.find(".page-item.selected").length ){
                $(element).addClass("selected");
            }
            else {
                $(element).removeClass("selected");
            }
        });
    },

    getActivePagePermission: function(){
        let permissions = [];
        $(".page-item").each(function(index, element){
            if( !$(element).hasClass("parent-page-item") && $(element).hasClass('selected') ) {
                permissions.push($(element).attr('data-page-name'));
            }
        });

        return permissions;
    }
};

$(document).ready(function(){
    Edit_Page_Permissions.init();
});
