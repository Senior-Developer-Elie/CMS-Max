var Website_Progress = {
    init: function(){
        this.initTooltip();
        this.initWebsiteSortable();
        //this.initStageSortable();
        this.initToggleActions();
        this.initAssigneeEditAction();
        this.initAssigneeCompletedDateAction();

        //Task Add Actions
        this.initTaskAddEditActions();

        //init unique task link action
        this.initActiveTaskLinkAction();

        //init Filter Actions
        this.initFilterActions();
    },

    initFilterActions: function(){
        $("#tasks-type-filter").select2({
            minimumResultsForSearch: -1
        });
        $("#tasks-type-filter").change(function(){
            taskTypeFilter = $(this).val();
            location.href = siteUrl + "/website-progress?isUniqueView=false&taskTypeFilter=" + taskTypeFilter;
        });
    },

    initTooltip: function(){
        $('[data-toggle="tooltip"]').tooltip()
    },

    initWebsiteSortable: function(){
         $(".TaskList").sortable({
            items          : ".TaskRow",
            connectWith    : ".TaskList",
            handle         : '.TaskRow-DragIcon',
            stop            : function(event, ui){
                Website_Progress.checkTaskListVisible();
            },
            update : function(event, ui) {
                if (this === ui.item.parent()[0]) {
                    Website_Progress.updateTasksOrder($(this).closest('.TaskGroup '));
                }
            }
         });
    },

    initStageSortable: function(){
        $("#TaskGroupWrapper").sortable({
            items           : ".TaskGroup",
            handle          : ".TaskGroupHeader-dragMiniIcon",
            stop            : function(event, ui){
                Website_Progress.checkTaskListVisible();
            },
            update : function(event, ui) {
                Website_Progress.updateStagesOrder();
            }
        });

        //Collapse content when drag start
        $(".TaskGroup .TaskGroupHeader-dragMiniIcon").mousedown(function(){
            //Hide All Task List
            $(".TaskList").hide();
        });
        $(".TaskGroup .TaskGroupHeader-dragMiniIcon").mouseup(function(){
            //Check task list visible after drag
            Website_Progress.checkTaskListVisible();
        });
    },

    checkTaskListVisible: function() {
        $('.TaskGroup').each(function(index, element) {
            if( $(element).hasClass('open') )
                $(element).find(".TaskList").show();
            else
                $(element).find(".TaskList").hide();
        });
    },

    initToggleActions: function(){
        $(".TaskGroupHeader .TaskGroupHeader-toggleButton").click(function(){
            if( $(this).closest('.TaskGroup').hasClass('open') ) {
                $(this).closest('.TaskGroup').find(".TaskList").hide();
                $(this).closest('.TaskGroup').removeClass('open')
            }
            else {
                $(this).closest('.TaskGroup').find(".TaskList").show();
                $(this).closest('.TaskGroup').addClass('open')
            }
        })
    },

    initAssigneeEditAction: function() {
        $("a.assignee-value").each(function(index, element){
            Website_Progress.setXEditableForAssignee(element);
        })
    },

    initAssigneeCompletedDateAction: function() {
        $("a.completed-at-value").each(function(index, element){
            Website_Progress.setXEditableForCompletedAt(element);
        })
    },

    setXEditableForAssignee: function(element){
        websiteId = $(element).closest('.TaskRow').attr('data-task-id');
        $(element).editable({
            type        : 'select',
            pk          : websiteId,
            source      : allUsers,
            showbuttons : false,
            name        : 'assignee_id',
            send        : 'always',
            ajaxOptions : {
                type : 'POST'
            },
            url         : siteUrl+"/update-task-attribute",
            mode        : "popup",
            params      : function(params) {
                params._token = csrf_token;
                return params;
            },
            display     : function( value, sourceData ){
                let prettyUser = $.fn.editableutils.itemsByValue(value, sourceData).length > 0 ? $.fn.editableutils.itemsByValue(value, sourceData)[0] : false;
                if( prettyUser === false ){
                    backgroundStyle = "background-image: url('assets/images/default-avatar.jpg');";
                    $(this).html('<div class="DomainUserAvatar"><div class="AvatarPhoto" style="' + backgroundStyle + '">' +
                                '</div></div><div class="AssigneeWithName-name">No Assignee</div>');
                }
                else {
                    if( prettyUser.avatar == "" || prettyUser.avatar == null ) {
                        $(this).html('<div class="DomainUserAvatar"><div class="AvatarPhoto">' +
                                        prettyUser.initials + '</div></div><div class="AssigneeWithName-name">' +
                                        prettyUser.name + '</div>');
                    }
                    else {
                        backgroundStyle = "background-image: url('" + prettyUser.public_avatar_link + "');";
                        $(this).html('<div class="DomainUserAvatar"><div class="AvatarPhoto" style="' + backgroundStyle + '">' +
                                    '</div></div><div class="AssigneeWithName-name">' + prettyUser.name + '</div>');
                    }
                }
            }
        });
    },

    setXEditableForCompletedAt: function(element){
        websiteId = $(element).closest('.TaskRow').attr('data-task-id');
        $(element).editable({
            type        : 'date',
            pk          : websiteId,
            showbuttons : true,
            name        : 'completed_at',
            send        : 'always',
            ajaxOptions : {
                type : 'POST'
            },
            url         : siteUrl+"/update-task-attribute",
            mode        : "popup",
            params      : function(params) {
                params._token = csrf_token;
                return params;
            },
            display: function(value, sourceData) {
                if( value != undefined && value != null ){
                    let date = new Date(value);
                    $(this).html(date.toLocaleDateString("en-US"));
                }
                else
                    $(this).html('---');
            }
        });
    },

    setXEditableForTaskName: function(element){
        websiteId = $(element).closest('.TaskRow').attr('data-task-id');
        $(element).editable({
            type        : 'text',
            pk          : websiteId,
            name        : 'name',
            send        : 'always',
            showbuttons : false,
            onblur      : 'submit',
            ajaxOptions : {
                type : 'POST'
            },
            url         : siteUrl+"/update-task-attribute",
            mode        : "inline",
            params      : function(params) {
                params._token = csrf_token;
                return params;
            }
        });
    },

    getStagesOrder: function() {
        priority = 1;
        prioritiesArray = [];
        $(".TaskGroup").each(function(index, stageItem){
            prioritiesArray.push({
                'stageId'   : $(stageItem).attr('data-stage-id'),
                'priority'  : priority++
            })
        });
        return prioritiesArray;
    },

    updateStagesOrder: function() {
        ajaxData = {
            '_token'    : csrf_token,
            priorities  : Website_Progress.getStagesOrder()
        };
        $.ajax({
            type: 'POST',
            url: siteUrl + '/update-stage-priorities',
            data: ajaxData,
            success: function(data){
                if(data.status == 'success'){
                }
            }
        });
    },

    getTasksOrder: function(stageItem){
        prioritiesArray = [];
        let priority = stageItem.find(".TaskRow").length;
        stageItem.find(".TaskRow").each(function(index, websiteItem){

            prioritiesArray.push({
                'taskId' : $(websiteItem).attr('data-task-id'),
                'stageId'   : stageItem.attr('data-stage-id'),
                'priority'  : priority--
            })
        });
        return prioritiesArray;
    },

    updateTasksOrder: function(stageItem) {
        ajaxData = {
            '_token'    : csrf_token,
            priorities  : Website_Progress.getTasksOrder(stageItem)
        };
        $.ajax({
            type: 'POST',
            url: siteUrl + '/update-task-priorities',
            data: ajaxData,
            success: function(data){
                if(data.status == 'success'){
                }
            }
        });
    },

    /**
     * Task Add/Rename Actions
     */
    initTaskAddEditActions: function(){

        //Set X Editable for task name
        $(".TaskRow .task-name").each(function(index, element){
            Website_Progress.setXEditableForTaskName(element);
        });

        //Task add button on stage row
        $(document).on("click", ".TaskHeaderAddTaskIcon", function(e){
            e.preventDefault();
            let stageId = $(this).closest(".TaskGroup").attr("data-stage-id");

            ajaxData = {
                _token : csrf_token,
                stageId
            }
            $.ajax({
                type: 'POST',
                url: siteUrl + '/add-task',
                data: ajaxData,
                success: function(data){
                    if(data.status == 'success'){
                        let taskId = data.taskId;

                        //Add new Task row
                        var newTaskRow = $("#sample-task-row").clone();
                        newTaskRow.removeAttr("id");
                        newTaskRow.attr("data-task-id", taskId);
                        Website_Progress.setXEditableForAssignee(newTaskRow.find(".assignee-value")[0]);
                        newTaskRow.show();
                        newTaskRow.prependTo($(".TaskGroup[data-stage-id='" + stageId + "']").find(".TaskList"));

                        //Trigger Name Edit Action
                        Website_Progress.setXEditableForTaskName(newTaskRow.find(".task-name")[0]);
                        newTaskRow.find(".task-name").trigger("click");

                        Website_Progress.initWebsiteSortable();
                    }
                }
            });
        })


        //Task Details Click Button
        $(document).on("click", ".TaskRow .TaskNameCell", function(e){
            taskId = $(this).closest(".TaskRow").attr("data-task-id");
            Website_Progress.showTask(taskId);

            //Replace Url without refreshing page
            window.history.replaceState(null, null, "website-progress?isUniqueView=false&activeTaskId=" + taskId + "&taskTypeFilter=" + taskTypeFilter);
        });

        //Prevent inline editing button
        $(document).on("click", ".editable-container.editable-inline, .TaskRow .task-name", function(e){
            e.preventDefault();
            e.stopPropagation();
        });
    },

    showTask: function(taskId){

        $("#task-details-wrapper").show();
        if( isUniqueView == false ){
            $("#task-details-wrapper").animate({left: '60%'}, 350, function(){
                $("#tasks-list-wrapper").css("width", "60%");
            });
        }
        else{
            $("#task-details-wrapper").css("left", "30%");
        }

        //Load spinner to task detils div
        $('#task-details-wrapper').waitMe({
            effect : 'bounce',
            text : '',
            bg : 'rgba(255,255,255,0.7)',
            color : '#000'
        });

        //Get Task Details
        $.ajax({
            type: "GET",
            url: siteUrl + "/get-task-details",
            data: {
                taskId
            },
            success: function(data){
                if( data.status == "success" ) {
                    Task_Details_Widget.setTask(data.data);
                }
            }
        })
    },

    /**
     * Update Task Attribute Through Ajax Call
     */
    updateTaskAttribute: function(taskId, name, value){
        ajaxData = {
            _token  : csrf_token,
            pk      : taskId,
            name,
            value
        }
        $.ajax({
            type: 'POST',
            url: siteUrl + '/update-task-attribute',
            data: ajaxData,
            success: function(data){

            }
        });
    },

    initActiveTaskLinkAction: function(){
        if( activeTaskId != -1 ){
            Website_Progress.showTask(activeTaskId);
            if( $(".TaskRow[data-task-id='" + activeTaskId + "']").length > 0 ){
                $('#TaskGroupWrapper .scroll-box').animate({
                    scrollTop: $(".TaskRow[data-task-id='" + activeTaskId + "']").offset().top - 200
                }, 600);
            }
        }
        if( isUniqueView ){
            $("#task-details-wrapper .TaskNameWrapper .hide-button").hide();
            $("#task-details-wrapper .TaskNameWrapper .delete-button").hide();
        }
    }
};

var Task_Details_Widget = {

    task: false,
    descriptionEditor: false,
    clickedInsideDescriptionEditor: false,
    commentCKEditor: false,
    lastCommentId: -1,
    currentEditingCommentId: -1,
    uploadingImagePreOption: false,

    init: function(){

        this.initComponents();
        this.initDescriptionActions();
        this.initActions();
        this.initAttachmentActions();
        this.initPrePostActions();

        this.initCommentActions();

        this.initCommentFileUploadActions();
    },

    initComponents: function(){

        //Task Description CKEditor
        ClassicEditor
        .create( $("#task-details-wrapper .task-description-edit-textarea")[0], {
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'link'
            ],
            indentBlock: {
                offset: 1,
                unit: 'em'
            },
            link: {
                addTargetToExternalLinks: true,
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
            Task_Details_Widget.descriptionEditor = editor;
            Task_Details_Widget.descriptionEditor.ui.view.element.style.display="none";

            $(Task_Details_Widget.descriptionEditor.ui.view.element).mousedown(function(e){
                Task_Details_Widget.clickedInsideDescriptionEditor = true;
            });
            $(Task_Details_Widget.descriptionEditor.ui.view.element).mouseup(function(e){
                e.stopPropagation();
                Task_Details_Widget.clickedInsideDescriptionEditor = false;
            });
        } )
        .catch( error => {
            console.error( error );
        } );

        $("#complete-task-modal .complete-date").datepicker({
            format: 'mm/dd/yyyy',
            todayHighlight: true
        });
    },

    setTask: function(task){
        //Remove Selected
        if( Task_Details_Widget.task != false ){
            $(".TaskRow[data-task-id='" + Task_Details_Widget.task.id + "']").removeClass("selected");
        }
        Task_Details_Widget.task = task;

        //Add selected
        $(".TaskRow[data-task-id='" + Task_Details_Widget.task.id + "']").addClass("selected");

        Task_Details_Widget.setAllEditFieldsConfirmed();

        //Refresh Attachments
        $("#task-details-wrapper .task-attachment-list .attachment-item").remove();
        let files = Task_Details_Widget.task.files;
        files.forEach(file => {
            Task_Details_Widget.addAttachment(file);
        });
        Task_Details_Widget.refreshAttachments();

        //Set Description
        $("#task-details-wrapper .task-description-value").html(task.description);
        Task_Details_Widget.descriptionEditor.setData(task.description == null ? "" : task.description);

        //Set Task Name
        $("#task-details-wrapper .task-name-value").html(task.name);

        //Set Attributes
        //$("#task-details-wrapper .due-date-value").attr("data-value", task.due_date == null ? '' : task.due_date);
        $("#task-details-wrapper .client-name-value").attr("data-value", task.client_id);
        $("#task-details-wrapper .client-drive-value").attr("data-value", task.client_drive);

        if( task.client_id == "" || task.client_id == null ){//if client is not selected
            $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-client").hide();
        }
        else{//if client is selected
            $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-client").show();
        }

        if( task.client_drive == "" || task.client_drive == null )
            $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-drive").hide();
        else
            $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-drive").show();
        if( task.dev_url == "" || task.dev_url == null )
            $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-dev-url").hide();
        else
            $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-dev-url").show();
        $("#task-details-wrapper .dev-url-value").attr("data-value", task.dev_url);
        if( task.client_id == "" || task.client_id == null ){
            $("#task-details-wrapper .live-url-value").attr("data-value", task.live_url);
            $("#task-details-wrapper .live-url-value").attr("data-href", task.live_url);
            if( task.live_url == "" || task.live_url == null )
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-live-url").hide();
            else
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-live-url").show();
        }
        else{
            $("#task-details-wrapper .live-url-value").attr("data-value", task.website_id);
            $("#task-details-wrapper .live-url-value").attr("data-href", task.website_url);
            /*
            if( !isSuperAdmin ){
                $("#task-details-wrapper .live-url-value").html(task.website_url);
            }*/
            if( task.website_url == "" )
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-live-url").hide();
            else
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-live-url").show();
        }

        $("#task-details-wrapper .mail-host-value").attr("data-value", task.mail_host);

        $("#task-details-wrapper .site-map-value").attr("data-value", task.sitemap);
        $("#task-details-wrapper .home-page-copy-value").attr("data-value", task.home_page_copy);

        //Set Pre Post Attributes
        $("#task-details-wrapper .pre-live-options .pre-post-option").each(function(index, element){

            let option = $(element).attr("data-option-value");

            //Set Blank as default
            $(element).closest('.checkbox').find('.completed_by').hide();
            $(element).closest('.checkbox').find('.uploaded_by').hide();
            $(element).closest('.checkbox').find('.upload-btn').hide();
            $(element).closest('.checkbox').find('.download-btn').hide();
            $(element).prop("checked", false);

            if( ["social-media-image"].includes(option) ){   //disable checkbox
                $(element).attr('disabled', 'disabled');
            }

            if( ["favicon", "social-media-image"].includes(option) ){
                $(element).closest('.checkbox').find('.upload-btn').show();
            }
            if( task.pre_live != null && typeof task.pre_live[option] != 'undefined') {

                if( ["favicon", "social-media-image"].includes(option) && typeof task.pre_live[option].uploaded_by != 'undefined' ){//enable checkbox
                    $(element).removeAttr('disabled');
                    $(element).closest('.checkbox').find('.upload-btn').show();
                    $(element).closest('.checkbox').find('.download-btn').show();
                }

                if( typeof task.pre_live[option].uploaded_by != 'undefined' ){
                    $(element).closest('.checkbox').find('.uploaded_by').show();
                    $(element).closest('.checkbox').find('.uploaded_by .name').html(task.pre_live[option].uploaded_by);
                    $(element).closest('.checkbox').find('.uploaded_by .date').html(task.pre_live[option].uploaded_at);
                }

                if( typeof task.pre_live[option].checked_by != 'undefined' ){    //if checked
                    $(element).removeAttr('disabled');
                    $(element).prop("checked", true);
                    $(element).closest('.checkbox').find('.uploaded_by').hide();
                    $(element).closest('.checkbox').find('.completed_by').show();
                    $(element).closest('.checkbox').find('.completed_by .name').html(task.pre_live[option].checked_by);
                    $(element).closest('.checkbox').find('.completed_by .date').html(task.pre_live[option].checked_at);
                    $(element).closest('.checkbox').find('.upload-btn').hide();
                }
            }
        });

        Task_Details_Widget.setXEditableForAttributes();

        //Sync Comments
        $("#task-details-wrapper .comments-box-wrapper").html('');  //remove original comments
        Task_Details_Widget.lastCommentId = -1;
        Task_Details_Widget.syncCommentsContent(false, function(){
            $('#task-details-wrapper').waitMe('hide');
        });

        if( task.stage_id == 10 )
            $("#task-details-wrapper .TaskNameWrapper .complete-button").hide();
        else
            $("#task-details-wrapper .TaskNameWrapper .complete-button").show();
    },

    initDescriptionActions: function(){

        //click description text
        $(".description-content .task-description-value").click(function(e){
            e.stopPropagation();
            $(".description-content .task-description-value").hide();
            Task_Details_Widget.descriptionEditor.ui.view.element.style.display="block";
            Task_Details_Widget.descriptionEditor.ui.view.editable._editableElement.focus();
        });

        //Confirm Click ACtion
        $("#task-description-confirm-btn").click(function(){

            Task_Details_Widget.task.description = Task_Details_Widget.descriptionEditor.getData();
            $(".description-content .task-description-value").html(Task_Details_Widget.task.description).show();
            Task_Details_Widget.descriptionEditor.ui.view.element.style.display="none";

            Website_Progress.updateTaskAttribute(Task_Details_Widget.task.id, 'description', Task_Details_Widget.task.description);
        });
    },

    initActions: function() {

        //Click outside input fields trigger confirm action
        $(".content-wrapper").mouseup(function(){
            if( Task_Details_Widget.clickedInsideDescriptionEditor === true ) {
                Task_Details_Widget.clickedInsideDescriptionEditor = false;
                return;
            }
            Task_Details_Widget.setAllEditFieldsConfirmed();
        })

        //Widget hide button action
        $("#task-details-wrapper .TaskNameWrapper .hide-button").click(function(){
            $(".TaskRow").removeClass("selected");
            $("#task-details-wrapper").animate({left:'100%'}, 350, function(){
                $("#task-details-wrapper").hide();
                $("#tasks-list-wrapper").css("width", "100%");
            });

            //Replace Url without refreshing page
            window.history.replaceState(null, null, "website-progress?isUniqueView=false" + "&taskTypeFilter=" + taskTypeFilter);
            Task_Details_Widget.task = false;
        })

        //Delete Task Button Action
        $("#task-details-wrapper .TaskNameWrapper .delete-button").click(function(){
            $("#delete-task-modal .task-name").html(Task_Details_Widget.task.name);
            $("#delete-task-modal").modal('show');
        });
        $("#delete-task-modal .confirm-btn").click(function(){
            let deletingTaskId = Task_Details_Widget.task.id;
            $.ajax({
                type: 'POST',
                url: siteUrl + '/delete-task',
                data: {
                    _token : csrf_token,
                    taskId : Task_Details_Widget.task.id
                },
                success: function(response){
                    if( response.status == 'success' ) {
                        $.notify('The task is removed successfully.', { type: 'success', delay: 5000 });

                        let countElement = $(".TaskRow[data-task-id='" + deletingTaskId + "']").closest('.TaskGroup').find(".stage-task-count");
                        countElement.html(parseInt(countElement.html()) - 1);

                        $("#total-task-count").html(parseInt($("#total-task-count").html())-1)
                        //Remove Task Row
                        $(".TaskRow[data-task-id='" + deletingTaskId + "']").remove();

                        $("#delete-task-modal").modal('hide');

                        //Hide details widget
                        $("#task-details-wrapper .TaskNameWrapper .hide-button").trigger("click");
                    }
                }
            });
        })

        //Complete Task Button Action
        $("#task-details-wrapper .TaskNameWrapper .complete-button").click(function(){
            $("#complete-task-modal .task-name").html(Task_Details_Widget.task.name);
            $("#complete-task-modal .complete-date").val("");
            $("#complete-task-modal").modal('show');
        });
        $("#complete-task-modal .confirm-btn").click(function(){

            if( $("#complete-task-modal .complete-date").val() == "" ){
                $("#complete-task-modal .complete-date").focus();
                $.notify('Please select completed date.', { type: 'success', delay: 5000 });
                return;
            }
            let deletingTaskId = Task_Details_Widget.task.id;
            $.ajax({
                type: 'POST',
                url: siteUrl + '/complete-task',
                data: {
                    _token : csrf_token,
                    taskId : Task_Details_Widget.task.id,
                    completedAt : $("#complete-task-modal .complete-date").data('datepicker').getFormattedDate('yyyy-mm-dd')
                },
                success: function(response){
                    if( response.status == 'success' ) {
                        $.notify('The task is completed successfully.', { type: 'success', delay: 5000 });

                        let countElement = $(".TaskRow[data-task-id='" + deletingTaskId + "']").closest('.TaskGroup').find(".stage-task-count");
                        countElement.html(parseInt(countElement.html()) - 1);

                        $("#total-task-count").html(parseInt($("#total-task-count").html())-1)
                        //Remove Task Row
                        $(".TaskRow[data-task-id='" + deletingTaskId + "']").remove();

                        $("#complete-task-modal").modal('hide');

                        //Hide details widget
                        $("#task-details-wrapper .TaskNameWrapper .hide-button").trigger("click");
                    }
                }
            });
        })

        //Copy Link button action
        $("#task-details-wrapper .TaskNameWrapper .copy-link-button").click(function(){
            copyStringToClipboard(siteUrl + "/website-progress?isUniqueView=true&activeTaskId=" + Task_Details_Widget.task.id + "&taskTypeFilter=" + taskTypeFilter);
            $.notify('The task link was copied to your clipboard.', { type: 'success' });
        })

        //Go to Drive Button Action
        $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-drive").click(function(e){
            let driveUrl = $(".client-drive-value").attr('data-value').trim();
            if( driveUrl == "" || driveUrl == 'Empty' ){
                e.preventDefault();
            }
            else {
                if( !driveUrl.startsWith('https://') && !driveUrl.startsWith('http://') ){
                    driveUrl = "//" + driveUrl;
                }
                $(this).attr('href', driveUrl);
            }
        });
        $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-live-url").click(function(e){
            let liveUrl = $(".live-url-value").attr('data-href').trim();
            if( liveUrl == "" || liveUrl == null ){
                e.preventDefault();
            }
            else {
                if( !liveUrl.startsWith('https://') && !liveUrl.startsWith('http://') ){
                    liveUrl = "//" + liveUrl;
                }
                $(this).attr('href', liveUrl);
            }
        });
        $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-dev-url").click(function(e){
            let devUrl = $(".dev-url-value").attr('data-value').trim();
            if( devUrl == "" || devUrl == 'Empty' ){
                e.preventDefault();
            }
            else {
                if( !devUrl.startsWith('https://') && !devUrl.startsWith('http://') ){
                    devUrl = "//" + devUrl;
                }
                $(this).attr('href', devUrl);
            }
        });
        $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-site-map").click(function(e){
            let devUrl = $(".site-map-value").attr('data-value').trim();
            if( devUrl == "" || devUrl == 'Empty' ){
                e.preventDefault();
            }
            else {
                if( !devUrl.startsWith('https://') && !devUrl.startsWith('http://') ){
                    devUrl = "//" + devUrl;
                }
                $(this).attr('href', devUrl);
            }
        });
        $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-home-page-copy").click(function(e){
            let devUrl = $(".home-page-copy-value").attr('data-value').trim();
            if( devUrl == "" || devUrl == 'Empty' ){
                e.preventDefault();
            }
            else {
                if( !devUrl.startsWith('https://') && !devUrl.startsWith('http://') ){
                    devUrl = "//" + devUrl;
                }
                $(this).attr('href', devUrl);
            }
        });
        $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-client").click(function(e){
            let clientId = $(".client-name-value").attr('data-value').trim();
            if( clientId == "" || clientId == 'Empty' ){
                e.preventDefault();
            }
            else {
                $(this).attr('href', siteUrl + "/client-history?clientId=" + clientId);
            }
        });

        //Select Client Change Event
        $("#task-details-wrapper .client-name-value").on('save', function(e, params) {
            Website_Progress.showTask(Task_Details_Widget.task.id);
        });
        
        //Client Drive Change, then show/hide icon
        $("#task-details-wrapper .client-drive-value").on('save', function(e, params) {
            let newDriveValue = params.newValue;
            $("#task-details-wrapper .client-drive-value").attr('data-value', params.newValue);
            if( newDriveValue == '' || newDriveValue == 'Empty' ){
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-drive").hide();
            }
            else{
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-drive").show();
            }
        });
        //Live Url Value Change event
        $("#task-details-wrapper .live-url-value").on('save', function(e, params) {
            let newDriveValue = params.newValue;
            $("#task-details-wrapper .live-url-value").attr('data-value', params.newValue);
            if( newDriveValue == '' || newDriveValue == 'Empty' ){
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-live-url").hide();
            }
            else{
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-live-url").show();
            }
        });
        //Select Live URL Event
        $("#task-details-wrapper .live-url-value").on('save', function(e, params) {
            Website_Progress.showTask(Task_Details_Widget.task.id);
        });

        $("#task-details-wrapper .dev-url-value").on('save', function(e, params) {
            let newDriveValue = params.newValue;
            $("#task-details-wrapper .dev-url-value").attr('data-value', params.newValue);
            if( newDriveValue == '' || newDriveValue == 'Empty' ){
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-dev-url").hide();
            }
            else{
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-dev-url").show();
            }
        });
        $("#task-details-wrapper .site-map-value").on('save', function(e, params) {
            let newDriveValue = params.newValue;
            $("#task-details-wrapper .site-map-value").attr('data-value', params.newValue);
            if( newDriveValue == '' || newDriveValue == 'Empty' ){
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-site-map").hide();
            }
            else{
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-site-map").show();
            }
        });
        $("#task-details-wrapper .home-page-copy-value").on('save', function(e, params) {
            let newDriveValue = params.newValue;
            $("#task-details-wrapper .home-page-copy-value").attr('data-value', params.newValue);
            if( newDriveValue == '' || newDriveValue == 'Empty' ){
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-home-page-copy").hide();
            }
            else{
                $("#task-details-wrapper .Attributes-wrapper .attribute-row .show-home-page-copy").show();
            }
        });
    },

    setAllEditFieldsConfirmed: function(){
        //Confirm task description
        if( typeof Task_Details_Widget.descriptionEditor.ui != 'undefined' && Task_Details_Widget.descriptionEditor.ui.view.element.style.display == "block" )
        {
            $("#task-description-confirm-btn").trigger("click");
        }
    },

    setXEditableForAttributes: function(){

        //if( isSuperAdmin ){
            //client name
            $("#task-details-wrapper .client-name-value").editable("destroy");
            $("#task-details-wrapper .client-name-value").editable({
                type        : 'select',
                source      : allClients,
                pk          : Task_Details_Widget.task.id,
                name        : 'client_id',
                send        : 'always',
                inputclass  : 'attribute-edit-input',
                showbuttons : false,
                onblur      : 'submit',
                ajaxOptions : {
                    type : 'POST'
                },
                url         : siteUrl+"/update-task-attribute",
                mode        : "inline",
                params      : function(params) {
                    params._token = csrf_token;
                    return params;
                }
            });
            $('#task-details-wrapper .client-name-value').editable('setValue', $("#task-details-wrapper .client-name-value").attr('data-value'));

            //Client Drive
            $("#task-details-wrapper .client-drive-value").editable("destroy");
            $("#task-details-wrapper .client-drive-value").editable({
                type        : 'text',
                pk          : Task_Details_Widget.task.website_id,
                name        : 'drive',
                send        : 'always',
                inputclass  : 'attribute-edit-input',
                showbuttons : false,
                onblur      : 'submit',
                ajaxOptions : {
                    type : 'POST'
                },
                url         : siteUrl+"/update-website-attribute",
                mode        : "inline",
                params      : function(params) {
                    params._token = csrf_token;
                    return params;
                },
                display: function(value){
                    if( value != undefined && value != null ){
                        if( value.length > 50 )
                            $(this).html(value.substring(0, 50) + "...");
                        else
                            $(this).html(value);
                    }
                    else
                        $(this).html('---');
                }
            });
            $('#task-details-wrapper .client-drive-value').editable('setValue', $("#task-details-wrapper .client-drive-value").attr('data-value'));
        //}

        //dev url
        $("#task-details-wrapper .dev-url-value").editable("destroy");
        $("#task-details-wrapper .dev-url-value").editable({
            type        : 'text',
            pk          : Task_Details_Widget.task.id,
            name        : 'dev_url',
            send        : 'always',
            inputclass  : 'attribute-edit-input',
            showbuttons : false,
            onblur      : 'submit',
            ajaxOptions : {
                type : 'POST'
            },
            url         : siteUrl+"/update-task-attribute",
            mode        : "inline",
            params      : function(params) {
                params._token = csrf_token;
                return params;
            },
            display: function(value){
                if( value != undefined && value != null ){
                    if( value.length > 50 )
                        $(this).html(value.substring(0, 50) + "...");
                    else
                        $(this).html(value);
                }
                else
                    $(this).html('---');
            }
        });
        $('#task-details-wrapper .dev-url-value').editable('setValue', $("#task-details-wrapper .dev-url-value").attr('data-value'));

        //sitemap
        $("#task-details-wrapper .site-map-value").editable("destroy");
        $("#task-details-wrapper .site-map-value").editable({
            type        : 'text',
            pk          : Task_Details_Widget.task.id,
            name        : 'sitemap',
            send        : 'always',
            inputclass  : 'attribute-edit-input',
            showbuttons : false,
            onblur      : 'submit',
            ajaxOptions : {
                type : 'POST'
            },
            url         : siteUrl+"/update-task-attribute",
            mode        : "inline",
            params      : function(params) {
                params._token = csrf_token;
                return params;
            },
            display: function(value){
                if( value != undefined && value != null ){
                    if( value.length > 50 )
                        $(this).html(value.substring(0, 50) + "...");
                    else
                        $(this).html(value);
                }
                else
                    $(this).html('---');
            }
        });
        $('#task-details-wrapper .site-map-value').editable('setValue', $("#task-details-wrapper .site-map-value").attr('data-value'));

        //home page copy
        $("#task-details-wrapper .home-page-copy-value").editable("destroy");
        $("#task-details-wrapper .home-page-copy-value").editable({
            type        : 'text',
            pk          : Task_Details_Widget.task.id,
            name        : 'home_page_copy',
            send        : 'always',
            inputclass  : 'attribute-edit-input',
            showbuttons : false,
            onblur      : 'submit',
            ajaxOptions : {
                type : 'POST'
            },
            url         : siteUrl+"/update-task-attribute",
            mode        : "inline",
            params      : function(params) {
                params._token = csrf_token;
                return params;
            },
            display: function(value){
                if( value != undefined && value != null ){
                    if( value.length > 50 )
                        $(this).html(value.substring(0, 50) + "...");
                    else
                        $(this).html(value);
                }
                else
                    $(this).html('---');
            }
        });
        $('#task-details-wrapper .home-page-copy-value').editable('setValue', $("#task-details-wrapper .home-page-copy-value").attr('data-value'));

        //live url
        if(Task_Details_Widget.task.client_id != null && Task_Details_Widget.task.client_id != ""){
            //if( isSuperAdmin ){
                $("#task-details-wrapper .live-url-value").editable("destroy");
                $("#task-details-wrapper .live-url-value").editable({
                    type        : 'select',
                    source      : Task_Details_Widget._getPrettyWebsitesSource(),
                    pk          : Task_Details_Widget.task.id,
                    name        : 'website_id',
                    send        : 'always',
                    showbuttons : false,
                    onblur      : 'submit',
                    ajaxOptions : {
                        type : 'POST'
                    },
                    url         : siteUrl+"/update-task-attribute",
                    mode        : "inline",
                    params      : function(params) {
                        params._token = csrf_token;
                        return params;
                    },
                    display     : function( value, sourceData ){
                        let item = $.fn.editableutils.itemsByValue(value, sourceData).length > 0 ? $.fn.editableutils.itemsByValue(value, sourceData)[0] : false;
                        if( item === false ){
                            $(this).html("No website selected");
                        }
                        else {
                            $(this).html(item.text);
                        }
                    }
                });
                $('#task-details-wrapper .live-url-value').editable('setValue', $("#task-details-wrapper .live-url-value").attr('data-value'));
            //}
        }
        else{
            $("#task-details-wrapper .live-url-value").editable("destroy");
            $("#task-details-wrapper .live-url-value").editable({
                type        : 'text',
                pk          : Task_Details_Widget.task.id,
                name        : 'live_url',
                send        : 'always',
                inputclass  : 'attribute-edit-input',
                showbuttons : false,
                onblur      : 'submit',
                ajaxOptions : {
                    type : 'POST'
                },
                url         : siteUrl+"/update-task-attribute",
                mode        : "inline",
                params      : function(params) {
                    params._token = csrf_token;
                    return params;
                },
                display: function(value){
                    if( value != undefined && value != null ){
                        if( value.length > 50 )
                            $(this).html(value.substring(0, 50) + "...");
                        else
                            $(this).html(value);
                    }
                    else
                        $(this).html('---');
                }
            });
            $('#task-details-wrapper .live-url-value').editable('setValue', $("#task-details-wrapper .live-url-value").attr('data-value'));
        }

        //mail host
        $("#task-details-wrapper .mail-host-value").editable("destroy");
        $("#task-details-wrapper .mail-host-value").editable({
            type        : 'select',
            source      : allMailHosts,
            pk          : Task_Details_Widget.task.id,
            name        : 'mail_host',
            send        : 'always',
            ajaxOptions : {
                type : 'POST'
            },
            url         : siteUrl+"/update-task-attribute",
            mode        : "inline",
            showbuttons : false,
            params      : function(params) {
                params._token = csrf_token;
                return params;
            },
            display     : function( value, sourceData ){
                let item = $.fn.editableutils.itemsByValue(value, sourceData).length > 0 ? $.fn.editableutils.itemsByValue(value, sourceData)[0] : false;
                if( item === false ){
                    $(this).html("---");
                }
                else {
                    $(this).html(item.text);
                }
            }
        });
        $('#task-details-wrapper .mail-host-value').editable('setValue', $("#task-details-wrapper .mail-host-value").attr('data-value'));
    },

    _getPrettyWebsitesSource: function(){
        if( Task_Details_Widget.task.client_id == null || Task_Details_Widget.task.client_id == ""
            || !Array.isArray(Task_Details_Widget.task.websites) ){
                return [];
        }
        let prettyWebsites = [];
        Task_Details_Widget.task.websites.forEach((website)=>{
            prettyWebsites.push({
                value : website.id,
                text : website.website
            });
        });
        return prettyWebsites;
    },

    initAttachmentActions: function(){

        //Add File action
        $("#task-details-wrapper .add-file-button").click(function(){
            $("#task-file-input").trigger("click");
        })

        //File change action, upload files
        $("#task-file-input").change(function(event){
            let files = event.target.files;
            if( files.length > 0){

                let ajaxData = new FormData();
                ajaxData.append('_token', csrf_token);
                ajaxData.append('taskId', Task_Details_Widget.task.id);

                for( let i = 0; i < files.length; i++ ) {
                    ajaxData.append('taskFiles[]', files[i]);
                }

                //Load spinner to task detils div
                $('#task-details-wrapper').waitMe({
                    effect : 'bounce',
                    text : 'Pleae wait while uploading attachment',
                    bg : 'rgba(255,255,255,0.7)',
                    color : '#000'
                });

                //Send Form Data
                $.ajax({
                    type: 'POST',
                    url: siteUrl + '/task-upload-file',
                    processData: false,
                    contentType: false,
                    data: ajaxData,
                    success: function(response){
                        if( response.status == 'success' ) {
                            response.files.forEach(file => {
                                Task_Details_Widget.addAttachment(file);
                            });
                        }
                    },
                    complete: function(){
                        $('#task-details-wrapper').waitMe('hide');
                    }
                });
            }
        });

        //Remove File Action
        $(document).on("click", "#task-details-wrapper .attachment-item .delete-button", function(){
            let taskFileId = $(this).closest(".attachment-item").attr("data-task-file-id");
            $.ajax({
                type: 'POST',
                url: siteUrl + '/remove-task-file',
                data: {
                    _token: csrf_token,
                    taskFileId
                },
                success: function(data){
                    if(data.status == 'success'){
                        $("#task-details-wrapper .attachment-item[data-task-file-id='" + taskFileId + "']").remove();
                        Task_Details_Widget.refreshAttachments();
                    }
                }
            });
        });

        //Download File Action
        $(document).on("click", "#task-details-wrapper .attachment-item .file-name", function(e){
            e.preventDefault();
            let taskFileId = $(this).closest(".attachment-item").attr("data-task-file-id");

            $.ajax({
                type: 'GET',
                url: siteUrl + '/task-download-file',
                data: { taskFileId },
                success: function(response){
                    if( response.status == 'success' ){
                        Download_Adapter.process(response.downloadData);
                    }
                }
            })
        });
    },

    /**
     * Add attachment to the list
     */
    addAttachment(file){
        newFileItem = $("#sample-task-file-row").clone();
        newFileItem.removeAttr("id");
        newFileItem.attr("data-task-file-id", file.id);
        newFileItem.find(".file-name").html(file.origin_name);
        newFileItem.show();
        $("#task-details-wrapper .task-attachment-list").append(newFileItem);
        Task_Details_Widget.refreshAttachments();
    },

    /**
     * Refresh Attachments Wrapper visibility and count
     */
    refreshAttachments(){
        let fileCount = $("#task-details-wrapper .task-attachment-list .attachment-item").length;
        if( fileCount == 0 ){
            $("#task-details-wrapper .task-attachment-list").closest(".SingleTaskPane").hide();
            $(".TaskRow[data-task-id='" + Task_Details_Widget.task.id + "'] .task-status-wrapper .attachment-count").hide();
        }
        else{
            $("#task-details-wrapper .task-attachment-list").closest(".SingleTaskPane").show();
            $(".TaskRow[data-task-id='" + Task_Details_Widget.task.id + "'] .task-status-wrapper .attachment-count").show();
            $(".TaskRow[data-task-id='" + Task_Details_Widget.task.id + "'] .task-status-wrapper .attachment-count .value").html(fileCount);
        }
    },

    initPrePostActions : function(){
        $("#task-details-wrapper .pre-post-option").click(function(){

            let option = $(this).attr("data-option-value");
            $.ajax({
                type: "POST",
                url: siteUrl + "/task-update-pre-post-options",
                data: {
                    _token: csrf_token,
                    taskId: Task_Details_Widget.task.id,
                    option: $(this).attr("data-option-value"),
                    value: $(this).prop('checked') ? 'on' : 'off'
                },
                success: (data) => {
                    if( data.status == 'success' ) {
                        if( $(this).prop('checked') ){
                            $(this).closest('.checkbox').find('.completed_by').show();
                            $(this).closest('.checkbox').find('.completed_by .name').html(data.checked_by);
                            $(this).closest('.checkbox').find('.completed_by .date').html(data.checked_at);
                            if( ["favicon", "social-media-image"].includes(option) ){
                                $(this).closest('.checkbox').find('.uploaded_by').hide();
                                $(this).closest('.checkbox').find('.upload-btn').hide();
                                $(this).closest('.checkbox').find('.download-btn').show();
                            }
                        }
                        else {
                            $(this).closest('.checkbox').find('.completed_by').hide();
                            if( ["favicon", "social-media-image"].includes(option) ){
                                $(this).closest('.checkbox').find('.uploaded_by').show();
                                $(this).closest('.checkbox').find('.upload-btn').show();
                                $(this).closest('.checkbox').find('.download-btn').show();
                            }
                        }
                        Task_Details_Widget.updateProgressCountInTaskRow();
                    }
                }
            })
        });

        //upload button click for pre option
        $("#task-details-wrapper .pre-live-options .upload-btn").click(function(){
            $("#task-pre-live-image-file").val("");
            $("#task-pre-live-image-file").trigger("click");

            Task_Details_Widget.uploadingImagePreOption = $(this).closest('div.checkbox').find('input.pre-post-option').attr('data-option-value');
        });

        $("#task-pre-live-image-file").change(function(e){
            if( e.target.files.length == 0 )
                return;

            let file = e.target.files[0];

            //Loading Spinner
            $('body').waitMe({
                effect : 'bounce',
                text : 'Please wait while uploading image...',
                bg : 'rgba(255,255,255,0.7)',
                color : '#000'
            });

            let ajaxData = new FormData();
            ajaxData.append('_token', csrf_token);
            ajaxData.append('taskId', Task_Details_Widget.task.id);
            ajaxData.append('file', file);
            ajaxData.append('option', Task_Details_Widget.uploadingImagePreOption);

            //Send Form Data
            $.ajax({
                type: 'POST',
                url: siteUrl + '/task-pre-upload-image',
                processData: false,
                contentType: false,
                data: ajaxData,
                success: function(response){
                    if( response.status == 'success' ){
                        let $targetOption = $("#task-details-wrapper .pre-live-options .pre-post-option[data-option-value='" + Task_Details_Widget.uploadingImagePreOption + "']");

                        $targetOption.closest('.checkbox').find('.completed_by').hide();
                        $targetOption.closest('.checkbox').find('.uploaded_by').show();
                        $targetOption.closest('.checkbox').find('.uploaded_by').find('.name').html(response.uploaded_by);
                        $targetOption.closest('.checkbox').find('.uploaded_by').find('.date').html(response.uploaded_at);
                        $targetOption.closest('.checkbox').find('.upload-btn').show();
                        $targetOption.closest('.checkbox').find('.download-btn').show();
                        $targetOption.removeAttr('disabled');
                    }
                    else {
                        $.notify('Something went wrong while uploading image!', { type: 'error' });
                    }
                },
                complete: function(){
                    $('body').waitMe('hide');
                    $("#submit").removeClass('disabled');
                }
            });
        })

        //download button click for pre option
        $("#task-details-wrapper .pre-live-options .download-btn").click(function(){

            let option = $(this).closest('div.checkbox').find('input.pre-post-option').attr('data-option-value');
            ajaxData = {
                taskId : Task_Details_Widget.task.id,
                option
            };
            $.ajax({
                type: 'GET',
                url: siteUrl + '/task-download-pre-image',
                data: ajaxData,
                success: function(response){
                    if( response.status == 'success' ){
                        Download_Adapter.process(response.downloadData);
                    }
                }
            })
        });
    },

    /**
     * Comment ACtions
     */
    initCommentActions: function(){

        this.initPusherActions();
        this.initCKEditorForComment();
        this.initCommentDownloadActions();

        //Comment Add button click
        $(document).on("click", ".sing-task-pane-footer .add-comment-btn", function(){
            if( Task_Details_Widget.commentCKEditor.getData().trim() == "" )
                return;
            let ajaxData = {
                _token: csrf_token,
                content: Task_Details_Widget.commentCKEditor.getData(),
                taskId: Task_Details_Widget.task.id,
                commentId: Task_Details_Widget.currentEditingCommentId
            };

            $.ajax({
                type: 'POST',
                url: siteUrl + "/task-create-comment",
                data: ajaxData,
                success: function(data){
                    if( data.status == 'success' ) {
                        //$("#add-comment-modal").modal('hide');
                        Task_Details_Widget.commentCKEditor.setData(""),
                        Task_Details_Widget.currentEditingCommentId = -1;
                    }
                }
            });
        });

        //Comment Add Keyboard shortcut
        /*
        $(document).keydown(function (e) {

            if ( e.ctrlKey && e.keyCode == 13 && Task_Details_Widget.task != false ) {
                $("#task-details-wrapper .TaskNameWrapper .add-comment-button").trigger('click');
            }
          });
        */
        //Focus CKEditor once modal is shown
        $('#add-comment-modal').on('shown.bs.modal', function() {
            Task_Details_Widget.commentCKEditor.ui.view.editable._editableElement.focus();
        });

        //Comment Edit Button
        $(document).on('click', '.TaskStoryFeed-blockStory .dropdown-menu .edit-menu-item', function(){
            let commentId = $(this).closest('.TaskStoryFeed-blockStory').attr('data-comment-id');
            $.ajax({
                type: "GET",
                url: "/task-get-comment",
                data: { commentId },
                success: function(data){
                    if( data.status == 'success' ) {
                        Task_Details_Widget.currentEditingCommentId = data.comment.id;
                        Task_Details_Widget.commentCKEditor.setData(data.comment.content);
                    }
                }
            })
        });

        //Comment Remove Button
        $(document).on('click', '.TaskStoryFeed-blockStory .dropdown-menu .remove-menu-item', function(){
            let commentId = $(this).closest('.TaskStoryFeed-blockStory').attr('data-comment-id');
            $("#delete-comment-modal").attr('data-comment-id', commentId);
            $("#delete-comment-modal").modal('show');
        });

        //Remove Comment Confirm
        $("#delete-comment-modal .confirm-btn").click(function(){
            let commentId = $("#delete-comment-modal").attr('data-comment-id');
            $.ajax({
                type: "POST",
                url: "/task-remove-comment",
                data: {
                    _token: csrf_token,
                    commentId
                },
                success: function(data){
                    $("#delete-comment-modal").modal('hide');
                }
            });
        });

        //Comment Pin Button
        $(document).on('click', '.TaskStoryFeed-blockStory .dropdown-menu .pin-menu-item', function(){
            let commentId = $(this).closest('.TaskStoryFeed-blockStory').attr('data-comment-id');
            $.ajax({
                type: "POST",
                url: "/task-pin-comment",
                data: {
                    _token: csrf_token,
                    commentId,
                    status: 'on'
                },
                success: function(data){

                }
            });
        });

        //Comment UnPin Button
        $(document).on('click', '.TaskStoryFeed-blockStory .dropdown-menu .unpin-menu-item', function(){
            let commentId = $(this).closest('.TaskStoryFeed-blockStory').attr('data-comment-id');
            $.ajax({
                type: "POST",
                url: "/task-pin-comment",
                data: {
                    _token: csrf_token,
                    commentId,
                    status: 'off'
                },
                success: function(data){

                }
            });
        });
    },

    /**
     * Comment Downlaod Actions
     */
    initCommentDownloadActions: function(){

        $(document).on("click", ".TaskStoryFeed-blockStory .attachment-download-link", function(e){
            e.preventDefault();
            let commentId = $(this).closest('.TaskStoryFeed-blockStory').attr('data-comment-id');

            $.ajax({
                type: 'GET',
                url: siteUrl + '/task-download-comment-file',
                data: { commentId },
                success: function(response){
                    if( response.status == 'success' ){
                        Download_Adapter.process(response.downloadData);
                    }
                }
            })
        });
    },

    /**
     * Load Pusher and bind actions
     */
    initPusherActions: function(){
        Pusher.logToConsole = true;
        var pusher = new Pusher('35129b0fb0ff5e33fa94', {
            cluster: 'us2',
            forceTLS: true
        });

        var channel = pusher.subscribe('task-comment');
        channel.bind('comment-created', function (data) {

            //Update Comments Count on Task Row
            Task_Details_Widget.updateCommentsCountInTaskRow(data.task_id);

            //Update Comments Content if task is opened
            if( Task_Details_Widget.task != false && data.task_id == Task_Details_Widget.task.id ) {
                if( data.is_update ) {  //update comment
                    Task_Details_Widget.updateComment(data.comment_id);
                }
                else { //sync new comments
                    Task_Details_Widget.syncCommentsContent(true);
                }
            }
        });
        channel.bind('comment-removed', function (data) {

            //Update Comments Count on Task Row
            Task_Details_Widget.updateCommentsCountInTaskRow(data.task_id);

            if( Task_Details_Widget.task != false && data.task_id == Task_Details_Widget.task.id ) {
                Task_Details_Widget.removeComment(data.comment_id);
            }
        });
        channel.bind('comment-pin', function (data) {

            //Update Comment Pin Status
            if( Task_Details_Widget.task != false && data.task_id == Task_Details_Widget.task.id ) {
                Task_Details_Widget.updateCommentPinStatus(data.comment_id, data.status);
            }
        });
    },

    /**
     * Create CKEditor instance for Comment in dialog
     */
    initCKEditorForComment: function(){
        ClassicEditor
        .create( $("#comment-composer-box")[0], {
            viewportTopOffset : 50,
            toolbar: [
                'bold', 'italic', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'link', '|', 'emoji'
            ],
            emoji: [
                { name: 'smile', text: '😀' },
                { name: 'wink', text: '😉' },
                { name: 'cool', text: '😎' },
                { name: 'surprise', text: '😮' },
                { name: 'confusion', text: '😕' },
                { name: 'crying', text: '😢' }
            ],
            indentBlock: {
                offset: 1,
                unit: 'em'
            },
            link: {
                addTargetToExternalLinks: true,
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
            Task_Details_Widget.commentCKEditor = editor;

            $addCommentButton = $("<button type='button' class='btn btn-primary btn-xs add-comment-btn'>Comment</button>");
            $(".sing-task-pane-footer .ck-toolbar__items").append($addCommentButton);
            Task_Details_Widget.commentCKEditor.editing.view.document.on( 'keydown', ( evt, data ) => {
                /*
                if ( data.ctrlKey && String.fromCharCode(data.keyCode) == 's' ) {
                    data.preventDefault();
                    data.stopPropagation();
                    evt.stop();

                    $("#add-comment-modal .confirm-btn").trigger('click');
                }*/


            });
        } )
        .catch( error => {
            console.error( error );
        } );
    },

    /**
     * Sync Comments for current active task
     */
    syncCommentsContent: function(needScroll = false, callback = null){

        let ajaxData = {
            taskId : Task_Details_Widget.task.id,
            lastCommentId : Task_Details_Widget.lastCommentId
        };

        $.ajax({
            type: "GET",
            url : '/sync-task-comments',
            data: ajaxData,
            success: function(data){
                data.comments.forEach(comment => {

                    if( $(".comments-box-wrapper .TaskStoryFeed-blockStory[data-comment-id='" + comment.id + "']").length > 0 ){ //if comment already exist
                        return;
                    }

                    let $newCommentBlock = $("#comment-block-for-clone").clone().removeAttr('id');
                    $newCommentBlock.attr('data-comment-id', comment.id);
                    $newCommentBlock.find('.author-name').html(comment.author_name);
                    $newCommentBlock.find('.created-at-date').html(comment.pretty_created_at);
                    if( comment.author_avatar == false ) {
                        $newCommentBlock.find('.AvatarPhoto').css('background-image', 'unset');
                        $newCommentBlock.find('.AvatarPhoto').html(comment.author_initials);
                        $newCommentBlock.find('.AvatarPhoto').css('background-color', '#4186e0');
                    }
                    else {
                        $newCommentBlock.find('.AvatarPhoto').css('background-image', "url('" + comment.author_avatar + "')");
                        $newCommentBlock.find('.AvatarPhoto').html('');
                        $newCommentBlock.find('.AvatarPhoto').css('background-color', '#fff');
                    }

                    if( comment.type == 'text' ) {  //if text comment
                        $newCommentBlock.find('.comment-content').show();
                        $newCommentBlock.find('.comment-content').html(comment.content);
                        $newCommentBlock.find('.AddedAttachmentStory-content').hide();
                        $newCommentBlock.find('.PreviewableAttachment').hide();
                    }
                    else if( comment.type == 'file' ) {  //if file comment
                        $newCommentBlock.find('.comment-content').hide();
                        $newCommentBlock.find('.AddedAttachmentStory-content').show();
                        $newCommentBlock.find('.PreviewableAttachment').show();

                        //Set File Name
                        if( comment.file_type == 'image' ){     //if image file
                            $newCommentBlock.find('.PreviewableAttachment .image-file-name').html(comment.file_origin_name);
                            $newCommentBlock.find('.AttachmentImage img.Thumbnail-image').attr('src', comment.image_public_link);
                            $newCommentBlock.find('.AttachmentImage .AttachmentImage-preview').attr('href', comment.image_public_link);
                            //$newCommentBlock.find('.AttachmentImage .attachment-download-link').attr('href', siteUrl + "/task-download-comment-file?commentId=" + comment.id);
                            $newCommentBlock.find('.AttachmentCard').hide();
                            $newCommentBlock.find('.AttachmentImage').show();
                        }
                        else{
                            $newCommentBlock.find('.PreviewableAttachment .attachment-file-name').html(comment.file_origin_name);
                            $newCommentBlock.find('.AttachmentCard').show();
                            $newCommentBlock.find('.AttachmentImage').hide();
                            //$newCommentBlock.find('.AttachmentCard .attachment-download-link').attr('href', siteUrl + "/task-download-comment-file?commentId=" + comment.id);
                        }
                    }

                    /*Hide or show dropdown items*/
                    //Edit Button
                    if( (!isSuperAdmin && comment.author_id != userId) || comment.type != 'text' ) {
                        $newCommentBlock.find('.dropdown-menu').find('.edit-menu-item').hide();
                    }
                    else{
                        $newCommentBlock.find('.dropdown-menu').find('.edit-menu-item').show();
                    }

                    //Remove Button
                    if( !isSuperAdmin && comment.author_id != userId ) {
                        $newCommentBlock.find('.dropdown-menu').find('.remove-menu-item').hide();
                    }
                    else {
                        $newCommentBlock.find('.dropdown-menu').find('.remove-menu-item').show();
                    }

                    //Pin Button
                    if( comment.pin ) {
                        $newCommentBlock.find('.dropdown-menu').find('.pin-menu-item').hide();
                        $newCommentBlock.find('.dropdown-menu').find('.unpin-menu-item').show();
                    }
                    else{
                        $newCommentBlock.find('.dropdown-menu').find('.pin-menu-item').show();
                        $newCommentBlock.find('.dropdown-menu').find('.unpin-menu-item').hide();
                    }

                    //Pin or not Status
                    if( comment.pin )
                        $newCommentBlock.addClass('pinned');
                    else
                        $newCommentBlock.removeClass('pinned');

                    $newCommentBlock.show();
                    $("#task-details-wrapper .comments-box-wrapper").append($newCommentBlock);
                    Task_Details_Widget.lastCommentId = comment.id;
                });

                if( needScroll == true )
                    $("#task-details-wrapper .scroll-box").animate({ scrollTop: $('#task-details-wrapper .scroll-box').prop("scrollHeight")}, 400);

                if( callback != null )
                    callback();
            }
        })
    },

    /**
     * Update Comment
     */
    updateComment: function(commentId) {

        $.ajax({
            type: "GET",
            url: "/task-get-comment",
            data: { commentId },
            success: function(data){
                if( data.status == 'success' ) {
                    let $commentBlock = $(".comments-box-wrapper .TaskStoryFeed-blockStory[data-comment-id='" + data.comment.id + "']");
                    $commentBlock.find('.comment-content').html(data.comment.content);
                }
            }
        })
    },

    /**
     * Remove Comment
     */
    removeComment: function(commentId) {
        let $commentBlock = $(".comments-box-wrapper .TaskStoryFeed-blockStory[data-comment-id='" + commentId + "']");
        if( $commentBlock.length > 0 )
            $commentBlock.remove();
    },

    /**
     * Update Comments Count on Task Row
     */
    updateCommentsCountInTaskRow(taskId) {
        $.ajax({
            type: "GET",
            url: "/task-get-comments-count",
            data: { taskId },
            success: function(data){
                if( data.status == 'success' ) {
                    let $taskRow = $(".TaskRow[data-task-id='" + taskId + "']");

                    if( data.commentsCount == 0 ){
                        $taskRow.find('.task-status-wrapper .comment-count').hide();
                    }
                    else {
                        $taskRow.find('.task-status-wrapper .comment-count').show();
                        $taskRow.find('.task-status-wrapper .comment-count .value').html(data.commentsCount);
                    }
                }
            }
        })
    },

    /**
     * Update Comment Pin Status
     */
    updateCommentPinStatus(commentId, status) {
        let $commentBlock = $(".comments-box-wrapper .TaskStoryFeed-blockStory[data-comment-id='" + commentId + "']");
        if( status == true ) {
            $commentBlock.addClass('pinned');
            $commentBlock.find('.dropdown-menu').find('.pin-menu-item').hide();
            $commentBlock.find('.dropdown-menu').find('.unpin-menu-item').show();
        }
        else{
            $commentBlock.removeClass('pinned');
            $commentBlock.find('.dropdown-menu').find('.pin-menu-item').show();
            $commentBlock.find('.dropdown-menu').find('.unpin-menu-item').hide();
        }
    },

    /**
     * Update Pre Live Check box checked count on task row
     */
    updateProgressCountInTaskRow() {
        $(".TaskRow[data-task-id='" + Task_Details_Widget.task.id + "']").find(".pre-live-check-count").html($("#task-details-wrapper .pre-live-options .pre-post-option:checked").length + "/17");
    },

    /**
     * init comment file upload actions
     */
    initCommentFileUploadActions() {

        $('#task-details-wrapper .scroll-bar-wrap').on(
            'dragover',
            function(e) {
                e.preventDefault();
                e.stopPropagation();
            }
        )
        $('#task-details-wrapper .scroll-bar-wrap').on(
            'dragleave',
            function(e) {
                e.preventDefault();
                e.stopPropagation();

                var rect = this.getBoundingClientRect();

                // Check the mouseEvent coordinates are outside of the rectangle
                if(e.clientX > rect.left + rect.width || e.clientX < rect.left
                || e.clientY > rect.top + rect.height || e.clientY < rect.top) {
                    $("#task-details-wrapper .DropTargetAttachment-target").removeClass('DropTargetAttachment--dragging');
                }
            }
        )
        $('#task-details-wrapper .scroll-bar-wrap').on(
            'dragenter',
            function(e) {
                e.preventDefault();
                e.stopPropagation();

                $("#task-details-wrapper .DropTargetAttachment-target").addClass('DropTargetAttachment--dragging');
            }
        )
        $('#task-details-wrapper .scroll-bar-wrap').on(
            'drop',
            function(e){
                if(e.originalEvent.dataTransfer){
                    $("#task-details-wrapper .DropTargetAttachment-target").removeClass('DropTargetAttachment--dragging');
                    let files = e.originalEvent.dataTransfer.files;
                    if(files.length) {
                        e.preventDefault();
                        e.stopPropagation();

                        /*UPLOAD FILES HERE*/

                        ajaxData = new FormData();
                        ajaxData.append('_token', csrf_token);
                        ajaxData.append('taskId', Task_Details_Widget.task.id);

                        for( let i = 0; i < files.length; i++ ) {
                            ajaxData.append('files[]', files[i]);
                        }

                        $('#task-details-wrapper').waitMe({
                            effect : 'bounce',
                            text : 'Please wait while uploading files...',
                            bg : 'rgba(255,255,255,0.7)',
                            color : '#000'
                        });

                        //Upload File
                        $.ajax({
                            type: 'POST',
                            url: siteUrl + "/task-upload-comment-files",
                            processData: false,
                            contentType: false,
                            data: ajaxData,
                            success: function(data){
                                if( data.status == 'success' ) {
                                }
                            },
                            complete: function(){
                                $('#task-details-wrapper').waitMe('hide');
                            }
                        });
                    }
                }
            }
        );
    },

    findPrettyHostName: function(mailHostId){
        for( let i = 0; i < allMailHosts.length; i++ )
            if( allMailHosts[i].value == mailHostId )
                return allMailHosts[i].text;
        return '---';
    }
};

function displayTrimmedString(value){
    if( value != undefined && value != null ){
        if( value.length > 50 )
            $(this).html(value.substring(0, 50) + "...");
        else
            $(this).html(value);
    }
    else
        $(this).html('---');
}

$(document).ready(function(){

    Task_Details_Widget.init();
    Website_Progress.init();
})
