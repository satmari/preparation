@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">

			<div class="panel panel-default">
				<div class="panel-heading">Import PO form Excel file</div>
				
				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@postImportPo']]) !!}
					<div class="panel-body">
						{!! Form::file('file', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import Po', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

				<!-- <hr> -->
			</div>
				

			<div class="panel panel-default">
				<div class="panel-heading">Import users form Excel file</div>

				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@postImportUser']]) !!}
					<div class="panel-body">
						{!! Form::file('file2', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import User', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

				<!-- <hr> -->
			</div>	

			<div class="panel panel-default">
				<div class="panel-heading">Import user Rolles form Excel file</div>

				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@postImportRoll']]) !!}
					<div class="panel-body">
						{!! Form::file('file3', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import Rolles', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

				<!-- <hr> -->
			</div>

			<div class="panel panel-default">
				<div class="panel-body">
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default btn-lg center-block">Back to main menu</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection