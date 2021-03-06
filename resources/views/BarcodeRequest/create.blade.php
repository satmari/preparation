@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Create new Request</div>

				{!! Form::open(['method'=>'POST', 'url'=>'/barcoderequeststore']) !!}

				<div class="panel-body">
					<p>Po/Komesa: </p>
					{!! Form::text('po', null, ['id' => 'po', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
					<p>Size/Velicina: </p>
					{!! Form::select('size', array(''=>'','S'=>'S','M'=>'M','L'=>'L','XL'=>'XL','XXL'=>'XXL'), '', array('class' => 'form-control')) !!} 
				</div>
				<div class="panel-body">
					<p>Qty/Kolicina: </p>
					{!! Form::number('qty', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<p>Module: </p>
					{!! Form::text('module', null, ['id' => 'module', 'class' => 'form-control']) !!} 
				</div>
				<div class="panel-body">
					<p>Comment: </p>
					{!! Form::text('comment', null, ['class' => 'form-control']) !!}
				</div>

				<div class="panel-body">
					{!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
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

