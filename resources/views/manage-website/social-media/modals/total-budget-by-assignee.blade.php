<div id="total-budget-by-assignee-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Spends By Assignee</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>
                            Assignee
                        </th>
                        <th>
                            Ad Spend
                        </th>
                        <th>
                            Management Fee
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($spendsAnalytics['spendsByAssignee'] as $spendByAssignee)
                        <tr>
                            <td>{{ $spendByAssignee->assignee_name }}</td>
                            <td>$ {{ prettyFloat($spendByAssignee->total_ad_spend) }}</td>
                            <td>$ {{ prettyFloat($spendByAssignee->total_management_fee) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <strong>Total</strong>
                        </td>
                        <td>
                            <strong>$ {{ prettyFloat($spendsAnalytics['totalAdSpend']) }}</strong>
                        </td>
                        <td>
                            <strong>$ {{ prettyFloat($spendsAnalytics['totalManagementFee']) }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>