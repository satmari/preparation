@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Returned Barcode/Carelabel from module add to Stock</div>

				{!! Form::open(['method'=>'POST', 'url'=>'/stockstorefrommodule']) !!}

				<div class="panel-body">
					<p>Po/Komesa: <span style="color:red">Obavezno 6 cifara</span></p>
					{!! Form::text('po', null, ['id' => 'po', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
					<p>Size/Velicina: </p>
					{!! Form::select('size', array(''=>'','XS'=>'XS','S'=>'S','M'=>'M','L'=>'L','XL'=>'XL','XXL'=>'XXL','M/L'=>'M/L','S/M'=>'S/M','XS/S'=>'XS/S','2'=>'2','3-4'=>'3-4','5-6'=>'5-6','7-8'=>'7-8','9-10'=>'9-10','11-12'=>'11-12','SSHO'=>'SSHO','MSHO'=>'MSHO','XSSHO'=>'XSSHO','TU'=>'TU'), '', array('class' => 'form-control')) !!} 
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

