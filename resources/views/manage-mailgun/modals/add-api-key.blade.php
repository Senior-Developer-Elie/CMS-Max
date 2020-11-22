<!--Add Expense Modal-->
<div class="modal fade" id="add-api-key-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Mailgun Api Key</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Domain</label>
                    <div>
                        <input type="text" class="form-control" id="domain" placeholder="website.cmsmax.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Api Key</label>
                    <div>
                        <input type="text" class="form-control" id="api-key" placeholder="Api Key..." required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary confirm-btn">OK</button>
            </div>
        </div>
    </div>
</div>
