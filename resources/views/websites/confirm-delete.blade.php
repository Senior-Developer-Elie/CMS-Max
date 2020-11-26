@extends('layouts.theme')

@section('content-header')
    <h3>Add Website</h3>
@endsection

@section('content')
    @include('partials.form-errors')

	<div class="row">
		<div class="col-md-6">
			<div class="card card-primary card-outline">
				<div class="card-header with-border">
					<h3 class="card-title">Delete Website: {{ $website->name }}</h3>
				</div>
				<div class="card-body clearfix">
					<form action="{{ route("websites.destroy", $website) }}" method="POST">
						@csrf
						@method('DELETE')

						<p>Are you sure you want to delete this website?</p>
						<p>
							<strong>{{ $website->website }}</strong>
						</p>

						<div class="form-group">
							<button type="submit" class="btn btn-danger pull-right">Delete</button>
						</div>
					</form>
				</div>
			</div>	
		</div>
	</div>
@endsection