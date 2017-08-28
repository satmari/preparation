@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Add Barcode/Careleabel to Stock</div>

				{!! Form::open(['method'=>'POST', 'url'=>'/stockstorenew']) !!}

				<div class="panel-body">
					<p>Po/Komesa: </p>
					{!! Form::text('po', null, ['id' => 'po', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
					<p>Size/Velicina: </p>
					{!! Form::select('size', array(''=>'','XS'=>'XS','S'=>'S','M'=>'M','L'=>'L','XL'=>'XL','XXL'=>'XXL','M/L'=>'M/L','S/M'=>'S/M'), '', array('class' => 'form-control')) !!} 
				</div>
				<div class="panel-body">
					<p>Qty/Kolicina: </p>
					{!! Form::number('qty', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<table>
						<!-- <th> -->
						<div class="col-md-6">
							<b>Barcode</b>
							{!! Form::checkbox('barcode', 1 , null, ['id' => 'check', 'class' => 'form-control']); !!}
						</div>
						<!-- </th> -->
						<!-- <th> -->
						<div class="col-md-6">
							<b>Carelabel</b>
							{!! Form::checkbox('carelabel', 1 ,null , ['id' => 'check', 'class' => 'form-control']); !!}
						</div>
						<!-- </th> -->
					</table>

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

			</div>
		</div>
	</div>
</div>
@endsection