@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Log tables</div>

				<div class="panel-body">
					<div class="panel-heading">Stock tables</div>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/barcodestocktable')}}" class="btn btn-success center-block">Barcode Stock Log</a>
						</div>
					</div>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/carelabelstocktable')}}" class="btn btn-info center-block">Carelabel Stock Log</a>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="panel-heading">Request tables</div>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/barcoderequesttablelog')}}" class="btn btn-success center-block">Barcode Request Log</a>
						</div>
					</div>
					<div class="panel-body">
						<div class="">
							<a href="{{url('/carelabelrequesttablelog')}}" class="btn btn-info center-block">Carelabel Request Log</a>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection