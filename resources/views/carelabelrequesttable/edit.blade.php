@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading h-c">Edit Carelabel Request: {{$request_c->id}}
					<br>
					<br>
					{!! Form::open(['method'=>'POST', 'url'=>'/carelabelrequesttable-error/'.$request_c->id]) !!}
						{!! Form::hidden('id', $request_c->id, ['class' => 'form-control']) !!}
						{!! Form::submit('Set request as error', ['class' => 'btn  btn-warning btn-xs center-block']) !!}
					{!! Form::close() !!}
				</div>

				<br>
				

				{!! Form::model($request_c , ['method' => 'PATCH', 'url' => 'carelabelrequesttable/'.$request_c->id /*, 'class' => 'form-inline'*/]) !!}

				<div class="panel-body">
					<span>Id:</span>
					{!! Form::input('number', 'id', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Po Id:</span>
					{!! Form::input('number', 'po_id', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>User Id:</span>
					{!! Form::input('number', 'user_id', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Po:</span>
					{!! Form::input('string', 'ponum', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Size:</span>
					{!! Form::input('string', 'size', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Qty:</span>
					{!! Form::input('number', 'qty', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Module:</span>
					{!! Form::input('string', 'module', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Leader:</span>
					{!! Form::input('string', 'leader', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Status:</span>
					{{-- {!! Form::input('string', 'status', null, ['class' => 'form-control']) !!} --}}
					{!! Form::select('status', array('pending'=>'Pending','confirmed'=>'Confirmed','error'=>'Error'), null, array('class' => 'form-control')); !!} 
				</div>
				<div class="panel-body">
					<span>Type:</span>
					{!! Form::input('string', 'type', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Comment:</span>
					{!! Form::input('string', 'comment', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>created_at:</span>
					{!! Form::input('date', 'created_at', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>updated_at:</span>
					{!! Form::input('date', 'updated_at', null, ['class' => 'form-control']) !!}
				</div>
				

				<div class="panel-body">
					{!! Form::submit('Edit Request', ['class' => 'btn btn-success center-block']) !!}
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