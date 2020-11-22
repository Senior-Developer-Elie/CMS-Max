<!--Add Task Modal-->
<div class="modal fade" id="add-task-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Task</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Job Title:</label>
                    <input type = "text" class="form-control job-title">
                </div>
                <div class="form-group">
                    <label>Website:</label>
                    <select class="form-control website-list" name="website_id" style="width: 100%;">
                        <option value="0">- Please Select a Client -</option>
                        @foreach ($websites as $website)
                            <option value="{{ $website->id }}">{{ $website->name . " - " . $website->website }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="admin-list">Assign an admin</label>
                    <select class="form-control admins-list" name="assignee_id" style="width: 100%;">
                        <option value="0">- Please Select an Admin -</option>
                        @foreach ($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Due Date:</label>
                    <input type = "text" class="form-control due-date">
                </div>

                <div class="form-group">
                    <div class="radio">
                        <label>
                            <input type="radio" class = "job-to-do-radio" name="status" value="to-do" checked>
                            Job To do
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" class = "job-on-hold-radio" name="status" value="on-hold">
                            Job on hold
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>What is needed?</label>
                    <textarea class="form-control needed-text" rows="4" placeholder="what is needed..." name="needed_text"></textarea>
                </div>
                <div class="form-group files-wrapper">
                    <label>Files currently added</label>
                    <button type="button" class="btn btn-info btn-xs pull-right upload-file">
                        <i class="fa fa-upload"></i> Upload
                    </button>
                    <ul class="nav nav-stacked">
                    </ul>
                    <p class="no-file-text">
                        There is no files uploaded.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success pull-left complete-btn" data-dismiss="modal">Complete</button>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary confirm-btn">OK</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--Hidden Input Fields For Upload -->
<input type="file" id="uploadFile" style="display:none" multiple>

<!-- Hidden File Item -->
<li id = "file-item-for-clone" class="file-item" style="display:none;">
    <div class="file-item-wrapper dropdown">
        <i class="fa fa-file"></i>
        <span class="name" data-toggle="tooltip" data-placement="top" title="Download file"></span>
        <div class="tools">
            {{--<i class="fa fa-arrow-left revert-button" data-toggle="tooltip" data-placement="top" title="Mark as pending file"></i>--}}
            {{--<i class="fa fa-check-square-o complete-button" data-toggle="tooltip" data-placement="top" title="Mark as final file"></i>--}}
            <i class="fas fa-times remove-button" data-toggle="tooltip" data-placement="top" title="Remove file"></i>
        </div>
    </div>
</li>
