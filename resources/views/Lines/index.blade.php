@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Request for barcode and carelabel (new)</div>	
				@if (isset($msge))
					<div class="alert alert-danger" role="alert">
				        {{ $msge }}
				    </div>
				@endif 
				@if (isset($msgs))
				    <div class="alert alert-success" role="alert">
				        {{ $msgs }}
				    </div>
				@endif
	
				<br>				
				{!! Form::open(['method'=>'POST', 'url'=>'leadercheck']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}">
				{!! Form::hidden('module', $module, ['class' => 'form-control']) !!}

				<div class="panel-body">
					<p>LineLeader PIN code (Inteos)</p>
					{!! Form::number('pin', null, ['id' => 'pin', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				
				<div class="panel-body">
					{!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
				</div>

				@include('errors.list')
				{!! Form::close() !!}

		
			</div>
		</div>

		
		
	</div>
</div>
@endsection