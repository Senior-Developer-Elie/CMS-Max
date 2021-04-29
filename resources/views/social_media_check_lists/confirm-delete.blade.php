@extends('layouts.theme')

@section('content-header')
    <h3>Delete Social Media Check List</h3>
@endsection

@section('content')
    @include('partials.form-errors')

	<div class="row">
		<div class="col-md-6">
			<div class="card card-primary card-outline">
				<div class="card-body clearfix">
					<form action="{{ route("social_media_check_lists.destroy", $socialMediaCheckList) }}" method="POST">
						@csrf
						@method('DELETE')

						<p>Are you sure you want to delete this check list?</p>
						<p>
							<strong>{{ $socialMediaCheckList->text }}</strong>
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