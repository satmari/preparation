@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Kikinda preparacija</div>			

				<div class="panel-body">
					<div class="">
						<a href="{{url('kikinda_stock')}}" class="btn btn-info center-block">Stock table</a>
					</div>
				</div>

				<hr>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/receive_from_su_b')}}" class="btn btn-success center-block">Receive Barcodes from Subotica</a>
					</div>
				</div>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/receive_from_su_c')}}" class="btn btn-success center-block">Receive Carelabels from Subotica</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/#')}}" class="btn btn-warning center-block">Give to the line</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/#')}}" class="btn btn-danger center-block">Throw away</a>
					</div>
				</div>
				

			</div>
		</div>
	</div>
</div>
@endsection