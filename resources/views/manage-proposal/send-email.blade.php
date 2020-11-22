@extends('layouts.theme')

@section('content')
    <div class="row">
        <div class="col-6">
            <form role = "form" method="POST" action="{{ url('send-email') }}">
                @csrf
                <input type = "hidden" name = "proposalId" value="{{ $proposal->id }}">
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title">Send proposal email</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="clientEmail">Email address</label>
                            <input type="email" class="form-control" id="clientEmail" name = "clientEmail" placeholder="Enter email" required>
                        </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary pull-right">Send</button>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </div>
@endsection
