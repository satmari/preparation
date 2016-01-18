@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading h-b">Edit Barcode Request: Id:<b>{{$request_b->id}}</b> Po:<b>{{$request_b->ponum}}</b> Size:<b>{{$request_b->size}}</b> Module:<b>{{$request_b->module}}</b>
					<br>
					<br>
					{!! Form::open(['method'=>'POST', 'url'=>'/barcoderequesttable-error/'.$request_b->id]) !!}
						{!! Form::hidden('id', $request_b->id, ['class' => 'form-control']) !!}
						{!! Form::submit('Set request as error', ['class' => 'btn  btn-warning btn-xs center-block']) !!}
					{!! Form::close() !!}
				</div>
				<br>
				
				{!! Form::model($request_b , ['method' => 'PATCH', 'url' => 'barcoderequesttablep/'.$request_b->id /*, 'class' => 'form-inline'*/]) !!}

				{!! Form::hidden('id', $request_b->id, ['class' => 'form-control']) !!}
				{!! Form::hidden('status', $request_b->status, ['class' => 'form-control']) !!}

				<div class="panel-body">
					<span>Qty:</span>
					{!! Form::input('number', 'qty', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Comment:</span>
					{!! Form::input('string', 'comment', null, ['class' => 'form-control']) !!}
				</div>
				
				
				<div class="panel-body">
					{!! Form::submit('Edit Request', ['class' => 'btn btn-success center-block']) !!}
				</div>

				@include('errors.list')
				{!! Form::close() !!}

				<hr>

				{{-- 
				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/barcoderequesttable-error/'.$request_b->id)}}" class="btn btn-warning btn-sm">Set request as error</a>
					</div>
				</div>
				--}}

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