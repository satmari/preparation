@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-12 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading">Import PO</div>
				<br>
				
				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@postImportPo']]) !!}
					<div class="panel-body">
						{!! Form::file('file', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import', ['class' => 'btn btn-warning btn-lg center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

				<hr>
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