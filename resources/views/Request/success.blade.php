@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Request form module</div>
				<h3 style="color:green;">Success</h3>
				<br />
				<br />
				<p>{!! $msg !!}</p>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/request')}}" class="btn btn-default center-block">Back</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection