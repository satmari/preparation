@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					You are logged in!

					{{$msg}}

					
					@role('admin')
    					user is admin
					@endrole
					
					{{-- 
					@if(Auth::check() && Auth::user()->level() >= 3)
						user has level 3 or higher
					@endif

					error if user has no level
					--}}

				</div>
			</div>
		</div>
	</div>
</div>
@endsection