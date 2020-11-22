var InnerPage = {

    innerPageCKEditor: false,
    needPageRefresh: false,

    init: function () {

        this.initTooltip();
        this.initSortable();
        this.initFilterActions();
        this.initDeleteTaskActions();
        this.initAddTaskModalClientSelect();
        this.initCKEditorForWhatNeeded();
        this.initCompleteBlogActons();
        this.initDownloadBlogActions();
        this.initUndoBlogActions();
        this.initShowNeededTextActions();
        this.initAutoEditPopupAction();

        this.initTableSortActions();
    },

    initAutoEditPopupAction: function(){
        if( typeof editInnerBlogId !='undefined' && editInnerBlogId != -1 )
        {
            $(".inner-page-item[data-inner-page-id='" + editInnerBlogId + "']").find(".edit-inner-page-button").trigger("click");
        }
    },

    initTooltip: function(){
        $('[data-toggle="tooltip"]').tooltip()
    },

    initSortable: function() {

        if( enableDrag == 'on' ) {
            $('.task-list-table tbody').sortable({
                handle              : '.handle',
                forcePlaceholderSize: true,
                zIndex              : 999999,
                update : function(event, ui) {
                    ajaxData = {
                        '_token'    : csrf_token,
                        priorities  : InnerPage.getUpdatedOrder(ui.item.closest('table'))
                    };
                    $.ajax({
                        type: 'POST',
                        url: siteUrl + '/update-inner-page-priority',
                        data: ajaxData,
                        success: function(data){
                            if(data.status == 'success'){
                            }
                        }
                    });
                },
            });
        }
        else {
            $('.task-list-table').DataTable({
                'paging'    : true,
                'searching' : false,
                "pageLength": -1,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                columnDefs: [
                    {targets: [5, 6], orderable : false},
                ]
            });
        }
    },

    initFilterActions: function() {
        $("#blogFilter").change(function(){
            filter = $(this).val();
            InnerPage.redirectProperPage();
        });
        $("#assigneeFilter").change(function(){
            assinee = $(this).val();
            InnerPage.redirectProperPage();
        });
    },

    initCKEditorForWhatNeeded: function() {
        ClassicEditor
        .create( $("#add-task-modal .needed-text")[0], {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList', 'link', 'item', '|', 'outdent', 'indent', '|',
            ],
            indentBlock: {
                offset: 1,
                unit: 'em'
            },
            link: {
                // Automatically add target="_blank" and rel="noopener noreferrer" to all external links.
                addTargetToExternalLinks: true,

                // Let the users control the "download" attribute of each link.
                decorators: [
                    {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'download'
                        }
                    }
                ]
            }
        })
        .then( editor => {
            InnerPage.innerPageCKEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );
    },

    initDeleteTaskActions: function() {

        //Delete Button
        $(".inner-page-item .delete-inner-page-button").click(function(e){

            e.stopPropagation();
            innerPageId = $(this).closest(".inner-page-item").attr("data-inner-page-id");

            $("#delete-task-modal").attr('data-inner-page-id', innerPageId);

            $("#delete-task-modal").modal('show');
        });

        //Delete Confirm
        $("#delete-task-modal .confirm-btn").click(function(e){
            innerPageId = $(this).closest("#delete-task-modal").attr("data-inner-page-id");

            ajaxData = {
                '_token'        : csrf_token,
                'innerPageId'   : innerPageId,
            };

            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-inner-page',
                data: ajaxData,
                success: function(data){
                    if(data.status == 'success'){
                        //Replace URL with unique one
                        Add_Task_Modal.selectedInnerBlogId = -1;
                        window.history.replaceState(null, null, InnerPage.redirectProperPage(false));

                        location.reload();
                    }
                }
            });
        });
    },

    initAddTaskModalClientSelect: function(){
        $('#add-task-modal .website-list').select2();
        $('#add-task-modal .admins-list').select2();
        $("#blogFilter").select2({
            minimumResultsForSearch: -1
        });
        $("#assigneeFilter").select2();
    },

    getUpdatedOrder: function($table) {

        priority = 1;
        prioritiesArray = [];
        $table.find("tr.inner-page-item").each(function(index, pageItem){
            prioritiesArray.push({
                'innerPageId'   : $(pageItem).attr('data-inner-page-id'),
                'priority'      : priority++
            })
        });
        return prioritiesArray;
    },

    /**
     * Complete Blog Actions
     */
    initCompleteBlogActons: function(){

        //Complete Blog Button Click
        $(".inner-page-item .complete-button").click(function(e){

            e.stopPropagation();
            innerPageId = $(this).closest(".inner-page-item").attr("data-inner-page-id");
            $("#complete-task-modal").attr("data-inner-page-id", innerPageId);
            $("#complete-task-modal .url-list-wrapper .website").not(':first').remove();
            $("#complete-task-modal .website").val('');
            $("#complete-task-modal").modal('show');
        })

        //Modal Complete button
        $("#add-task-modal .complete-btn").click(function(e){

            e.stopPropagation();
            innerPageId = Add_Task_Modal.selectedInnerBlogId;
            $("#complete-task-modal").attr("data-inner-page-id", innerPageId);
            $("#complete-task-modal .url-list-wrapper .website").not(':first').remove();
            $("#complete-task-modal .website").val('');
            $("#complete-task-modal").modal('show');
        })

        //Add Url Button Click
        $("#complete-task-modal .add-url-button").click(function(){
            newUrlInput = $("#complete-task-modal .url-list-wrapper .website").first().clone();
            newUrlInput.val("");
            $("#complete-task-modal .url-list-wrapper .website").last().after(newUrlInput);
            newUrlInput.focus();
        });

        //Complete Blog Confirm Click
        $("#complete-task-modal .confirm-btn").click(function(){

            if( $("#complete-task-modal .website").val().trim() == '' ){
                $("#complete-task-modal .website").focus();
                return;
            }

            websites = [];
            $("#complete-task-modal .website").each(function(index, element) {
                if( $(element).val().trim() != "" )
                    websites.push($(element).val().trim());
            })

            ajaxData = {
                _token      : csrf_token,
                innerPageId : $("#complete-task-modal").attr("data-inner-page-id"),
                website     : websites
            };

            $.ajax({
                type: 'POST',
                url: siteUrl + '/complete-inner-page',
                data: ajaxData,
                success: function(data){
                    if(data.status == 'success'){
                        //Replace URL with unique one
                        Add_Task_Modal.selectedInnerBlogId = -1;
                        window.history.replaceState(null, null, InnerPage.redirectProperPage(false));
                        location.reload();
                    }
                }
            });
        })
    },

    /**
     * Download Blog Action
     */
    initDownloadBlogActions: function() {

        //Download All Files From Blog Button Click
        $(".inner-page-item .download-button").click(function(e){

            e.preventDefault();
            e.stopPropagation();

            let innerPageId = $(this).closest(".inner-page-item").attr("data-inner-page-id");

            $.ajax({
                type: 'GET',
                url: siteUrl + '/inner-page-download-files',
                data: {
                    'innerBlogId' : innerPageId
                },
                success: function(response){
                    if( response.status == 'success' ){
                        Download_Adapter.process(response.downloadData);
                    }
                }
            })
        })

        //Download individual Blog File Click
        $(document).on("click", ".file-item .name", function(e){
            e.preventDefault();
            e.stopPropagation();

            innerBlogFileId = $(this).closest('.file-item').attr('data-inner-blog-file-id');

            $.ajax({
                type: 'GET',
                url: siteUrl + '/inner-page-download-files',
                data: { innerBlogFileId },
                success: function(response){
                    if( response.status == 'success' ){
                        Download_Adapter.process(response.downloadData);
                    }
                }
            })
        })
    },

    /**
     * Init Blog Back Status Actions
     */
    initUndoBlogActions: function() {

        //Undo completed status
        $(".inner-page-item .undo-complete-button").click(function(){

            innerPageId = $(this).closest(".inner-page-item").attr("data-inner-page-id");
            $("#undo-complete-modal").attr('data-inner-page-id', innerPageId);
            $("#undo-complete-modal").modal('show');
        });

        $("#undo-complete-modal .confirm-btn").click(function(){

            innerPageId = $("#undo-complete-modal").attr("data-inner-page-id");
            ajaxData = {
                '_token'    : csrf_token,
                innerPageId : innerPageId
            };

            $.ajax({
                type: 'POST',
                url: siteUrl + '/inner-page-undo-complete',
                data: ajaxData,
                success: function(data){
                    if(data.status == 'success'){
                        //Replace URL with unique one
                        Add_Task_Modal.selectedInnerBlogId = -1;
                        window.history.replaceState(null, null, InnerPage.redirectProperPage(false));
                        location.reload();
                    }
                }
            });
        })
    },

    /**
     * Init Show Needed Text actions when click title
     */
    initShowNeededTextActions: function() {
        $(".inner-page-item .inner-blog-title").click(function(){
            neededTextItem = $(this).closest('.inner-page-item').next();
            let alreadyVisible = neededTextItem.is(":visible");
            //Hide all needed text item
            $(".inner-page-item-description:visible").hide();
            if( !alreadyVisible )
                neededTextItem.show();
        });
    },

    /**
     * Init Sort Actions for table
     */
    initTableSortActions: function() {
        $(".task-list-table th.sortable-column").click(function(){

            let newSortColumn = $(this).attr('data-sort-column');
            if( sortColumn == newSortColumn ) {
                sortOrder = (sortOrder == 'asc' ? 'desc' : 'asc');
            }
            else
                sortOrder = 'asc';
            sortColumn = newSortColumn;

            InnerPage.redirectProperPage();
        });
    },

    /**
     * Redirect Page with proper parameters
     */
    redirectProperPage : function(redirect = true) {
        let url = siteUrl + '/jobs?filter=' + filter + "&assignee=" + assinee + "&sortColumn=" + sortColumn + "&sortOrder=" + sortOrder;
        if( Add_Task_Modal.selectedInnerBlogId != -1 )
            url += "&editInnerBlogId=" + Add_Task_Modal.selectedInnerBlogId;
        if( redirect == true )
            location.href = url;
        else
            return url;
    }


};

var Add_Task_Modal = {
    selectedInnerBlogId : -1,
    selectedDueDate : null,

    init: function(){
        this.initDatePicker();
        this.initTaskFilesAction();
        this.initAddTaskActions();
        this.initCustomLinkInContent();
    },

    initCustomLinkInContent: function(){
        $(".inner-page-item .text a").click(function(e){
            e.stopPropagation();
            window.open($(this).attr("href"), "_blank");
        });
    },
    initDatePicker: function() {
        $('#add-task-modal .due-date').datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: true,
            onSelect: function(dateText) {
                Add_Task_Modal.selectedDueDate = $("#add-task-modal .due-date").data('datepicker').getFormattedDate('yyyy-mm-dd');
            }
        });
        $('#add-task-modal .due-date').change(function(){
            Add_Task_Modal.selectedDueDate = $("#add-task-modal .due-date").data('datepicker').getFormattedDate('yyyy-mm-dd');
        });
    },

    initAddTaskActions: function() {

        //Add Task
        $("#add-task-button").click(function(){

            //Reset
            Add_Task_Modal.selectedInnerBlogId = -1;
            $('#add-task-modal .modal-title').html('Add Task');
            $('#add-task-modal .job-title').val('');
            $('#add-task-modal .website-list').val("0").trigger("change");
            $('#add-task-modal .admins-list').val("0").trigger("change");
            $('#add-task-modal .due-date').val("");
            $('#add-task-modal .job-to-do-radio').prop('checked', true);
            InnerPage.innerPageCKEditor.setData("");
            Add_Task_Modal.selectedDueDate = null;

            $('#add-task-modal .confirm-btn').html('OK');

            Add_Task_Modal.setFileList([]);
            $(".files-wrapper .no-file-text").show();

            $('#add-task-modal .complete-btn').hide();

            $("#add-task-modal").modal('show');
            InnerPage.needPageRefresh = false;
        });

        //Edit Task
        $(".edit-inner-page-button").click(function(){
            Add_Task_Modal.selectedInnerBlogId = $(this).closest('.inner-page-item').attr("data-inner-page-id");

            $.ajax({
                type: 'GET',
                url: siteUrl + '/get-inner-page-data',
                data: {
                    'innerPageId' : Add_Task_Modal.selectedInnerBlogId
                },
                success: function(data){
                    if(data.status == 'success'){
                        if( data.innerPageData.needed_text == null )
                            data.innerPageData.needed_text = "";
                        InnerPage.innerPageCKEditor.setData(data.innerPageData.needed_text);
                        $('#add-task-modal .job-title').val(data.innerPageData.title);
                        $('#add-task-modal .website-list').val(data.innerPageData.website_id.toString()).trigger("change");
                        $('#add-task-modal .admins-list').val(data.innerPageData.assignee_id == null ? "0" : data.innerPageData.assignee_id.toString()).trigger("change");
                        if( data.innerPageData.due_date == null )
                            $('#add-task-modal .due-date').val("");
                        else
                            $('#add-task-modal .due-date').datepicker("update", new Date(data.innerPageData.due_date));
                        Add_Task_Modal.selectedDueDate = data.innerPageData.due_date;

                        $('#add-task-modal .modal-title').html('Edit Task');
                        $("#add-task-modal .files-wrapper").show();

                        if( data.innerPageData.to_do )
                            $('#add-task-modal .job-to-do-radio').prop('checked', true);
                        else
                            $('#add-task-modal .job-on-hold-radio').prop('checked', true);

                        Add_Task_Modal.setFileList(data.innerPageFiles);
                        if( data.innerPageFiles.length == 0 )
                            $(".files-wrapper .no-file-text").show();
                        else
                            $(".files-wrapper .no-file-text").hide();

                        $('#add-task-modal .confirm-btn').html('Update');

                        if( data.innerPageData.marked )
                            $('#add-task-modal .complete-btn').hide();
                        else
                            $('#add-task-modal .complete-btn').show();

                        $("#add-task-modal").modal('show');
                        InnerPage.needPageRefresh = false;

                        //Replace URL with unique one
                        window.history.replaceState(null, null, InnerPage.redirectProperPage(false));
                    }
                }
            });
        });

        //Confirm Add/Edit Task
        $("#add-task-modal .confirm-btn").click(function(){

            if( $(this).hasClass('disabled') )
                return;

            if( $('#add-task-modal .job-title').val() == "" )
            {
                $('#add-task-modal .job-title').focus();
                return;
            }
            if( $("#add-task-modal .website-list").val() == "0" )
            {
                $("#add-task-modal .website-list").select2('open');
                return;
            }
            if( $("#add-task-modal .admins-list").val() == "0" )
            {
                $("#add-task-modal .admins-list").select2('open');
                return;
            }
            /*
            if( InnerPage.innerPageCKEditor.getData() == "" )
            {
                $("#add-task-modal .needed-text").focus();
                return;
            }
            */

            ajaxData = new FormData();
            ajaxData.append('_token', csrf_token);
            ajaxData.append('inner_page_id', Add_Task_Modal.selectedInnerBlogId);
            ajaxData.append('title', $('#add-task-modal .job-title').val());
            ajaxData.append('website_id', $("#add-task-modal .website-list").val());
            ajaxData.append('assignee_id', $("#add-task-modal .admins-list").val());
            ajaxData.append('due_date', Add_Task_Modal.selectedDueDate);
            ajaxData.append('needed_text', InnerPage.innerPageCKEditor.getData());
            ajaxData.append('to_do', $('#add-task-modal .job-to-do-radio').prop('checked') ? true : false);

            if( Add_Task_Modal.selectedInnerBlogId == -1 ) {
                //Get list of inner blog files
                let innerBlogFileIds = [];
                $(".files-wrapper .file-item").each(function(index, element){
                    innerBlogFileIds.push($(element).attr('data-inner-blog-file-id'));
                })
                ajaxData.append('innerBlogFileIds', innerBlogFileIds);
            }

            $(this).addClass('disabled');
            $.ajax({
                type: 'POST',
                url: '/add-inner-page',
                processData: false,
                contentType: false,
                data: ajaxData,
                success: function(data){
                    if(data.status == 'success'){
                        //Replace URL with unique one
                        Add_Task_Modal.selectedInnerBlogId = -1;
                        window.history.replaceState(null, null, InnerPage.redirectProperPage(false));
                        location.reload();
                    }
                }
            });
        });

        $(".inner-page-item .inner-blog-status-label").click(function(e){
            e.stopPropagation();
        })

        //Edit Task Modal Hide Event
        $('#add-task-modal').on('hidden.bs.modal', function () {
            //Replace URL with unique one
            Add_Task_Modal.selectedInnerBlogId = -1;
            window.history.replaceState(null, null, InnerPage.redirectProperPage(false));
        });
    },

    /**
     * Files Action on edit task modal
     */
    initTaskFilesAction: function() {

        //Upload Button Click
        $("#add-task-modal .upload-file").click(function(){
            $("#uploadFile").trigger('click');
        });

        //File Change Action
        $("#uploadFile").change(function(event){
            if( event.target.files.length > 0){

                //Loading Spinner
                $('body').waitMe({
                    effect : 'bounce',
                    text : 'Please wait while uploading files...',
                    bg : 'rgba(255,255,255,0.7)',
                    color : '#000'
                });

                let ajaxData = new FormData();
                ajaxData.append('_token', csrf_token);
                ajaxData.append('innerPageId', Add_Task_Modal.selectedInnerBlogId);

                for(let i = 0; i < event.target.files.length; i++)
                    ajaxData.append('files[]', event.target.files[i]);

                //Send Form Data
                $.ajax({
                    type: 'POST',
                    url: siteUrl + '/inner-page-upload-files',
                    processData: false,
                    contentType: false,
                    data: ajaxData,
                    success: function(response){
                        if( response.status == 'success' ) {
                            response.files.forEach(function(file){
                                Add_Task_Modal.addFileItem(file);
                            })
                            InnerPage.needPageRefresh = true;
                            $(".files-wrapper .no-file-text").hide();
                        }
                    },
                    complete: function(){
                        $('body').waitMe('hide');
                    }
                });
            }
        });

        //Remove Button Click
        $(document).on('click', '.file-item .remove-button', function (event) {

            innerBlogFileId = $(this).closest('.file-item').attr('data-inner-blog-file-id');
            ajaxData = {
                '_token'    : csrf_token,
                'innerBlogFileId'   : innerBlogFileId,
            };

            //Loading Spinner
            $('body').waitMe({
                effect : 'bounce',
                text : 'Remove uploaded file...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000'
            });

            $.ajax({
                'type'  : 'POST',
                'url'   : siteUrl + '/inner-page-clear-file-upload',
                data: ajaxData,
                success: function(data){
                    if( data.status == 'success' ) {
                        Add_Task_Modal.removeFileItem(data.innerBlogFileId);
                        InnerPage.needPageRefresh = true;
                    }
                },
                complete: function(){
                    $('body').waitMe('hide');
                }
            })
        });

        //Complete Button Click
        $(document).on('click', '.file-item .complete-button', function (event) {

            innerBlogFileId = $(this).closest('.file-item').attr('data-inner-blog-file-id');
            ajaxData = {
                '_token'    : csrf_token,
                'innerBlogFileId'   : innerBlogFileId,
            };

            $.ajax({
                'type'  : 'POST',
                'url'   : siteUrl + '/inner-page-complete-file',
                data: ajaxData,
                success: function(data){
                    if( data.status == 'success' ) {
                        Add_Task_Modal.completeFileItem(data.innerBlogFileId);
                        InnerPage.needPageRefresh = true;
                    }
                },
                complete: function(){
                }
            })
        });

        //Revert Button Click
        $(document).on('click', '.file-item .revert-button', function (event) {

            innerBlogFileId = $(this).closest('.file-item').attr('data-inner-blog-file-id');
            ajaxData = {
                '_token'    : csrf_token,
                'innerBlogFileId'   : innerBlogFileId,
            };

            $.ajax({
                'type'  : 'POST',
                'url'   : siteUrl + '/inner-page-revert-file',
                data: ajaxData,
                success: function(data){
                    if( data.status == 'success' ) {
                        Add_Task_Modal.revertFileItem(data.innerBlogFileId);
                        InnerPage.needPageRefresh = true;
                    }
                },
                complete: function(){
                }
            })
        });

        /**Add task Modal Hide Event */
        $('#add-task-modal').on('hidden.bs.modal', function () {
            if( InnerPage.needPageRefresh == true )
            {
                //Replace URL with unique one
                Add_Task_Modal.selectedInnerBlogId = -1;
                window.history.replaceState(null, null, InnerPage.redirectProperPage(false));

                location.reload();
            }

        });
    },

    /**
     * Refresh Files List
     */
    setFileList: function(files)
    {
        $("#add-task-modal .files-wrapper ul").empty();
        files.forEach(function(file){
            Add_Task_Modal.addFileItem(file);
        });
    },

    /**
     * Add File Item To the list
     */
    addFileItem: function(file)
    {
        newFileItem = $("#file-item-for-clone").clone();

        //Remove id, id is only for selection
        newFileItem.removeAttr('id');
        newFileItem.show();
        newFileItem.attr('data-inner-blog-file-id', file.id);

        if( file.status == 'final' )
            newFileItem.addClass('marked');
        newFileItem.find('.name').html(file.origin_name);

        newFileItem.find('[data-toggle="tooltip"]').tooltip()
        $("#add-task-modal .files-wrapper ul").append(newFileItem);
    },

    /**
     * Remove File Item From List
     */
    removeFileItem: function(innerBlogFileId)
    {
        $("#add-task-modal .files-wrapper ul li[data-inner-blog-file-id=" + innerBlogFileId + "]").remove();
    },

    /**
     * Complete File Item From List
     */
    completeFileItem: function(innerBlogFileId)
    {
        $("#add-task-modal .files-wrapper ul li[data-inner-blog-file-id=" + innerBlogFileId + "]").addClass('marked');
    },

    /**
     * Revert File Item From List
     */
    revertFileItem: function(innerBlogFileId)
    {
        $("#add-task-modal .files-wrapper ul li[data-inner-blog-file-id=" + innerBlogFileId + "]").removeClass('marked');
    },
};

$(document).ready(function(){
    Add_Task_Modal.init();
    InnerPage.init();
})
