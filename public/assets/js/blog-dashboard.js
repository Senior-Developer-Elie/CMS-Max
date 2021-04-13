var Blog_List = {

    init: function(){

        this.initTooltip();
        this.initDataTable();
        this.initBlogNameEditAction();
        this.initWriterFilter();
    },

    initTooltip: function(){
        $('[data-toggle="tooltip"]').tooltip()
        $(".client-name-tooltip").tooltip()
    },

    initDataTable: function(){
        proposalTable = $('#client-list').DataTable({
            "bInfo": false,
            "order"     : [[ 0, "asc" ]],
            'paging'    : false,
            'searching'    : false,
            columnDefs: [{targets: (isBlogManager ? [4,5] : [3,4] ), type: 'sortme'}],
            fixedHeader: true,
            "scrollX": true,
            scrollY:        ($(window).height()-320) + "px",
            scrollX:        true,
            scrollCollapse: true,
            fixedColumns:   {
                leftColumns: 4,
            },
        });
        $.fn.dataTable.ext.type.order['sortme-pre'] = function (a, b) {
            return parseInt($(a).attr('data-order-value'));
        };
    },

    //Init blog name edit action
    initBlogNameEditAction: function() {

        //Click Action
        $(document).on('click', 'td.blog-cell.empty, td.blog-cell.normal, td.blog-cell.not-available', function(e){
            if( $(this).find('.blog-name-input-wrapper').is(":visible") )  //if currently editing
                return;

            e.stopPropagation();

            $(this).find('.blog-name-input-wrapper input').val($(this).find('.blog-name').attr('data-blog-name'));
            $(this).find('.blog-name-input-wrapper').show();
            $(this).find('.blog-name').hide();

            //Auto focus
            $(this).find('.blog-name-input-wrapper input').focus();
            $(this).find('.blog-name-input-wrapper input').select();
        })

        //Confirm Action
        $("button.blog-name-confirm").click(function(e){
            e.stopPropagation();
            let websiteId = $(this).closest('tr').attr('data-website-id');
            let desiredDate = $(this).closest('td').attr('data-desired-date');

            let blogName = $(this).closest('td').find('.blog-name-input-wrapper input').val();

            if( blogName.toLowerCase().trim() == 'n/a' ) {      //if set as not avaialble
                $(this).closest('td').removeClass('empty').removeClass('normal').addClass('not-available');

                if( $(this).closest('td').find('.blog-name').attr('data-blog-name').toLowerCase().trim() != 'n/a' ){      //if it is not already n/a cell then change count
                    $("#pending-blogs-to-write-count").html(parseInt($("#pending-blogs-to-write-count").html())-1);     //decrease pending count
                }
            }
            else {
                if( $(this).closest('td').find('.blog-name').attr('data-blog-name').toLowerCase().trim() === 'n/a' ){    //if it was n/a column
                    $(this).closest('td').removeClass('empty').addClass('normal').removeClass('not-available');
                    $("#pending-blogs-to-write-count").html(parseInt($("#pending-blogs-to-write-count").html()) + 1); //increaes pending count
                }
            }

            $(this).closest('td').find('.blog-name').attr('data-blog-name', blogName);
            $(this).closest('td').find('.blog-name').html(blogName);
            $(this).closest('td').find('.blog-name-input-wrapper').hide();
            $(this).closest('td').find('.blog-name').show();

            //Send Ajax Request for Blog Name Change
            ajaxData = {
                websiteId,
                desiredDate,
                blogName,
                _token : csrf_token
            };
            $.ajax({
                type: 'POST',
                data: ajaxData,
                url: siteUrl + '/change-blog-name',
                success: function(data){
                }
            });
        });

        //Confirm Trigger By Enter
        $("td.blog-cell .blog-name-input-wrapper input").keypress(function(e){
            if( e.which == 13 ) {
                e.preventDefault();
                $(this).closest('td').find('button.blog-name-confirm').trigger('click');
            }
        });

        $("td.blog-cell .blog-name-input-wrapper input").click(function(e){
            e.stopPropagation();
        })
        //Confirm Trigger By outsid cliking
        $(document).click(function(){
            //Find all visible confirm buttons and confirm trigger
            $("button.blog-name-confirm:visible").each(function(index, button){
                $(button).trigger('click');
            })
        });
    },

    initWriterFilter: function() {
        $("#writers-filter").change(function() {
            debugger;
            location.href = "/blog-dashboard?user_id=" + $(this).val(); 
        })
    },
};

$(document).ready(function(){
    Blog_List.init();
});
