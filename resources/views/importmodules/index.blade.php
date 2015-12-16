@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Import Modules and LineLeaders</div>
				<br>
				
				{!! Form::open(['method'=>'POST', 'url'=>'/importmodulesimport']) !!}
			

				<div class="panel-body">
					{!! Form::submit('Import', ['class' => 'btn btn-success center-block']) !!}
				</div>

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