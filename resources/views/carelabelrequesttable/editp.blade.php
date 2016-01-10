@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading h-c">Edit Carelabel Request: {{$request_c->id}}</div>
				<br>
				
				{!! Form::model($request_c , ['method' => 'PATCH', 'url' => 'carelabelrequesttablep/'.$request_c->id /*, 'class' => 'form-inline'*/]) !!}

				{!! Form::hidden('id', $request_c->id, ['class' => 'form-control']) !!}
				{!! Form::hidden('status', $request_c->status, ['class' => 'form-control']) !!}

				
				<div class="panel-body">
					<span>Qty:</span>
					{!! Form::input('number', 'qty', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Comment:</span>
					{!! Form::input('string', 'comment', null, ['class' => 'form-control']) !!}
				</div>
				

				<div class="panel-body">
					{!! Form::submit('Edit Request', ['class' => 'btn btn-warning center-block']) !!}
				</div>

				@include('errors.list')

				{!! Form::close() !!}

				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/main')}}" class="btn btn-default btn-lg center-block">Back to main menu</a>
					</div>
				</div>
					
			</div>
		</div>
	</div>
</div>

@endsection