@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading h-b">Returned Barcode from module add to Barcode Stock</div>

				{!! Form::open(['method'=>'POST', 'url'=>'/barcodestockstorefrommodule']) !!}

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
					<div class="alert alert-success">
  							Insert positive number and application will reduce form barcode stock.
					</div>
				</div>
				<div class="panel-body">
					<p>Module: </p>
					{!! Form::text('module', null, ['id' => 'module', 'class' => 'form-control']) !!} 
					{{-- {!! Form::select('size', array(''=>'','S'=>'S','M'=>'M','L'=>'L','XL'=>'XL','XXL'=>'XXL'), '', array('class' => 'form-control')) !!} --}}
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

