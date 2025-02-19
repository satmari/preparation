@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Stock</div>			
				<div class="panel-body">
					<div class="">
						<a href="{{url('/stockcreatenew')}}" class="btn btn-default center-block">Add to Stock</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/stockcreatefrommodule')}}" class="btn btn-default center-block">Back from module</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/stockcreateundo')}}" class="btn btn-default center-block">Reduce from Stock</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/throw_away')}}" class="btn btn-default center-block">Throw away</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/stockcreateleftover')}}" class="btn btn-default center-block">Leftover</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/stockcreattransfer_ki')}}" class="btn btn-default center-block">Transfer to Kikinda (test)</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/stockcreattransfer_se')}}" class="btn btn-default center-block">Transfer to Senta (test)</a>
					</div>
				</div>
				

			</div>
		</div>
	</div>
</div>
@endsection