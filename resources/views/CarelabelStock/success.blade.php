@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading h-c">Carelabel Stock</div>
				<h3 style="color:green;">Success</h3>

				<p style="color:green;">Succesfuly added to Carelabel stock</p>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/carelabelstock')}}" class="btn btn-default center-block">Back to Carelabel stock</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection