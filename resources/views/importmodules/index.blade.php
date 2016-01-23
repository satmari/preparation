@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Import Modules, Leaders and Leader PinCode</div>
				<br>
				<div class="panel-body">
				{!! Form::open(['method'=>'POST', 'url'=>'/importmodulesimport']) !!}
				{!! Form::submit('Import from Inteos database', ['class' => 'btn btn-success center-block']) !!}
				</div>

				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default btn-lg center-block">Back to main menu</a>
					</div>
				</div>

				<hr>
				<div class="panel-body">
					{!! $table->render() !!}
				</div>

			</div>
		</div>
	</div>
</div>

@endsection