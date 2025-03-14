@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Confirm quantity</div>

				@if (isset($msge))
				    <p class="text-danger">{{ $msge }}</p>
				@endif

				{!! Form::open(['method'=>'POST', 'url'=>'receive_from_su_c_post_confirm']) !!}
				{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}

					<div class="panel-body">
						<p>Received Qty: </p>
						{!! Form::number('qty', $qty, ['class' => 'form-control']) !!}
						
					</div>

					@if (isset($location_id))
					<div class="panel-body">
	                    <p>Choosen Location:</p>
	                    {!! Form::select('location_id', $locationsArray, $location_id, ['class' => 'form-control', 'disabled'=> 'disabled']) !!}
	                </div>
	                {!! Form::hidden('location_id', $location_id, ['class' => 'form-control']) !!}
	                @else
	                <div class="panel-body">
	                    <p>Choose Location: *</p>
	                    {!! Form::select('location_id', $locationsArray, $location_id, ['class' => 'form-control']) !!}
	                </div>
	                @endif

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