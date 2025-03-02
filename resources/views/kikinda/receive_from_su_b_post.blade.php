@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Confirm quantity</div>

				

				{!! Form::open(['method'=>'POST', 'url'=>'receive_from_su_b_post_confirm']) !!}
				{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}

					<div class="panel-body">
						<p>Received Qty: </p>
						{!! Form::number('qty', $qty, ['class' => 'form-control']) !!}
						<!-- <div class="alert alert-success">
	  							Insert positive number and application will reduce form barcode stock.
						</div> -->
					</div>

					<div class="panel-body">
						{!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
					</div>

				@include('errors.list')
				{!! Form::close() !!}
				
				<br>
			</div>
		</div>
	</div>
</div>

@endsection