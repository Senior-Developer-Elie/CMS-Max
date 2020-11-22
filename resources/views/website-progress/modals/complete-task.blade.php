<!--Delete Task Modal-->
<div class="modal fade" id="complete-task-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Website Completed</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong class="task-name">Ultra Keen</strong></p>
                <p>Are you sure you want to mark this website as completed?</p>
                <div class="form-group row mt-3">
                    <label for="inputEmail3" class="col-sm-3 col-form-label text-right">Complete Date</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control complete-date" name="completed_at">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger confirm-btn">Confirm</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
