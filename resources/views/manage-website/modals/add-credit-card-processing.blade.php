<!--Add Website Modal-->
<div class="modal fade" id="add-credit-card-processing-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Manual Entry</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="website-form">
                    <div class="form-group">
                        <label for="company-name">Company Name</label>
                        <div class="">
                            <input type="text" class="form-control" id="company-name" name="company_name" placeholder="Enter company name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="blog-industry">Payment Gateway</label>
                        <div class="">
                            <select class="form-control website-payment-gateway-list" name="payment_gateway" multiple="multiple" data-placeholder="Select Payment Gateways" style="width: 100%;">
                                @foreach ($allPaymentGateways as $index=>$name)
                                    <option value="{{ $index }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary pull-right confirm-btn">Save</button>
            </div>
        </div>
    </div>
</div>
