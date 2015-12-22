@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Carelabel Stock</div>			
				<div class="panel-body">
					<div class="">
						<a href="{{url('/carelabelstockcreatenew')}}" class="btn btn-success center-block">Add to Carelabel Stock</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/carelabelstockcreatefrommodule')}}" class="btn btn-info center-block">Back from module</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/carelabelstockcreateundo')}}" class="btn btn-warning center-block">Reduce form Carelabel Stock</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection