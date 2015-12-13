@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Barcode Stock</div>
				<h3 style="color:red;">Error!</h3>

				<p style="color:red;">{{ $msg }}</p>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/barcodestock')}}" class="btn btn-default center-block">Back to Barcode stock</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection