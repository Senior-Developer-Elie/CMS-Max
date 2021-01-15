<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    You have <a href="{{ url('/client-history?clientId=' . $firstClientId) }}" target="_blank">{{ $clientsMissingClientLead }}</a> clients to define client lead.
</div>
