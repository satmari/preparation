@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Request from module</div>
				<h3 style="color:green;">Success</h3>
				<br />
				<br />
				<p>{!! $msg !!}</p>
				<hr>
				<p style="font-size: x-large;">{!! $del !!}</p>
				</p>
				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/request')}}" class="btn btn-warning btn-lg center-block">OBAVEZNO!!! => Back</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection