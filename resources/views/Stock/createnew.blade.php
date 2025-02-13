@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Add Barcode/Careleabel to Stock</div>

				{!! Form::open(['method'=>'POST', 'url'=>'/stockstorenew']) !!}

				<div class="panel-body">
					<p>Po/Komesa: <span style="color:red">Obavezno 6 cifara</span></p>
					{!! Form::text('po', null, ['id' => 'po', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
					<p>Size/Velicina: </p>
					{!! Form::select('size', array(''=>'','XS'=>'XS','S'=>'S','M'=>'M','L'=>'L','XL'=>'XL','XXL'=>'XXL','M/L'=>'M/L','S/M'=>'S/M','XS/S'=>'XS/S','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','3-4'=>'3-4','5-6'=>'5-6','7-8'=>'7-8','9-10'=>'9-10','11-12'=>'11-12','LSHO'=>'LSHO','SSHO'=>'SSHO','MSHO'=>'MSHO','XSSHO'=>'XSSHO','XLSHO'=>'XLSHO','TU'=>'TU','1/2'=>'1/2','3/4'=>'3/4'), '', array('class' => 'form-control')) !!} 
				</div>
				<div class="panel-body">
					<p>Qty/Kolicina: </p>
					{!! Form::number('qty', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<table>
						<td>
							<tr>
								<div class="col-md-6">
									<b>Barcode</b>
									{!! Form::checkbox('barcode', 1 , null, ['id' => 'check', 'class' => 'form-control']); !!}
								</div>
							</tr>
							<tr>
								<div class="col-md-6">
									<b>Carelabel</b>
									{!! Form::checkbox('carelabel', 1 ,null , ['id' => 'check', 'class' => 'form-control']); !!}
								</div>
							</tr>
						</td>
						<td>
							<tr>
								<div class="col-md-6">
									<br>
									<input type="radio" name="machine" value="AUTOTEX"> AUTOTEX <br>
  									<input type="radio" name="machine" value="SGF"> SGF &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>
  									<input type="radio" name="machine" value="NOVEXX"> NOVEXX &nbsp;<br>
  									<input type="radio" name="machine" value="ZEBRA 600"> ZEBRA 600 <br>
								</div>
							</tr>
							<tr>
								<div class="col-md-6">
									<br>
									<input type="radio" name="machine_c" value="REGULAR" > REGULAR <br>
  									<input type="radio" name="machine_c" value="ON ROLL"> ON ROLL &nbsp; <br>
								</div>
							</tr>
						</td>
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