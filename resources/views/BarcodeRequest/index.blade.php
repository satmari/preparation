@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Request for barcodes and carelabels</div>			
				
				{!! Form::open(['method'=>'POST', 'url'=>'/barcoderequestcreate']) !!}

				<div class="panel-body">
					<p>PIN kod majtorice</p>
					{!! Form::text('pin', null, ['id' => 'pin', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				
				<div class="panel-body">
					{!! Form::submit('Potvrdi', ['class' => 'btn btn-success center-block']) !!}
				</div>

				@include('errors.list')
				{!! Form::close() !!}

				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection