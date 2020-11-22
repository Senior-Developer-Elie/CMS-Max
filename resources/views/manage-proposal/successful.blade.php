<link rel="stylesheet" href="assets/css/lib/bootstrap.min.css?v=1.0">

<div class="container mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-7">
            <div class="alert alert-success">
                @if($type == 'proposalConfirmed')
                    <h4>Your proposal is created successfully!</h4>
                    <div class="d-flex justify-content-center mt-3">
                        <a href = {{ url("/proposal-list") }}><button class="btn btn-info mr-2 pull-right">Admin Dashboard</button></a>
                        <a href = {{ url("/") }}><button class="btn btn-primary pull-right">Create New Proposal</button></a>
                    </div>
                @elseif($type == 'clientSinged')
                    <h4>Your signature has been submitted successfully!</h4>
                @elseif($type == 'proposalEdited')
                    <h4>{{ $message }}</h4>
                    <div class="d-flex justify-content-center mt-3">
                        <a href = {{ url("/proposal-list") }}><button class="btn btn-info mr-2 pull-right">Admin Dashboard</button></a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
