@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading h-c">Carelabel Stock</div>			
				<div class="panel-body">
					<div class="">
						<a href="{{url('/carelabelstockcreatenew')}}" class="btn btn-c1 center-block">Add to Carelabel Stock</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/carelabelstockcreatefrommodule')}}" class="btn btn-c2 center-block">Back from module</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/carelabelstockcreateundo')}}" class="btn btn-c3 center-block">Reduce from Carelabel Stock</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection