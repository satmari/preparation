@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Stock</div>
				<h3 style="color:green;">Success</h3>

				<p style="color:green;">Succesfuly added to the stock</p>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/stock')}}" class="btn btn-default center-block">Back to Stock</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection