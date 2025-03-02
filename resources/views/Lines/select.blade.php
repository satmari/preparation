@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Choose Request? (new) </div>
				<div class="panel-heading"><span>Majstorica: <b>{{$leader}}</b></span></div>		
				<div class="panel-body">
					<div class="">
						<a href="{{url('lines_requestcreate/'.$leader) }}" class="btn btn-bc center-block">Barcode and Carelabel</a>
					</div>
				</div>
				<!-- /'.$req->id.'/'.$req->qty -->


				@if ($leader == 'Sanela Mihaljević J.')
					<div class="panel-body">
						<div class="">
							<a href="{{url('lines_requestcreatesec') }}" class="btn btn-warning center-block">II Quality</a>
						</div>
					</div>
				@else
					
				@endif
				

			</div>
		</div>
		
	</div>
</div>
@endsection