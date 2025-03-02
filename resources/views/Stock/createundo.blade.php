@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default" >
				<div class="panel-heading">Reduce Barcode/Carelabel from Stock</div>

				{!! Form::open(['method'=>'POST', 'url'=>'/stockstoreundo']) !!}

				<div class="panel-body">
					<p>Po/Komesa: <span style="color:red">Obavezno 7 cifara</span></p>
					{!! Form::text('po', null, ['id' => 'po_new', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
					<p>Qty/Kolicina: </p>
					{!! Form::number('qty', null, ['class' => 'form-control']) !!}
					<div class="alert alert-success">
  							Insert positive number and application will reduce form barcode stock.
					</div>
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