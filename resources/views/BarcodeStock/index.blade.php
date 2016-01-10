@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading h-b">Barcode Stock</div>			
				<div class="panel-body">
					<div class="">
						<a href="{{url('/barcodestockcreatenew')}}" class="btn btn-b1 center-block">Add to Barcode Stock</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/barcodestockcreatefrommodule')}}" class="btn btn-b2 center-block">Back from module</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/barcodestockcreateundo')}}" class="btn btn-b3 center-block">Reduce form Barcode Stock</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection