@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading h-s">Stock</div>			
				<div class="panel-body">
					<div class="">
						<a href="{{url('/stockcreatenew')}}" class="btn btn-s1 btn-default center-block">Add to Stock</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/stockcreatefrommodule')}}" class="btn btn-s2 btn-default center-block">Back from module</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/stockcreateundo')}}" class="btn btn-s3 btn-default center-block">Reduce from Stock</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection