@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Edit location: {{ $location }}</div>
				

				{!! Form::open(['method'=>'POST', 'url'=>'location_edit_post']) !!}
				{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
				

					<div class="panel-body">
						<p>Location: </p>
						{!! Form::input('string', 'location', $location, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
						<p>Location Description: </p>
						{!! Form::input('string', 'location_desc', $location_desc, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
						<p>Location Plant: </p>
						{!! Form::select('location_plant', array(''=>'','Subotica'=>'Subotica','Kikinda'=>'Kikinda','Senta'=>'Senta'), $location_plant, array('class' => 'form-control')) !!} 
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