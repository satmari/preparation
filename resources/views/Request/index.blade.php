@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Request for barcode and carelabel</div>			
				<br>				
				{!! Form::open(['method'=>'POST', 'url'=>'/requestcheck']) !!}

				<div class="panel-body">
					<p>LineLeader PIN code (Inteos)</p>
					{!! Form::number('pin', null, ['id' => 'pin', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				
				<div class="panel-body">
					{!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
				</div>

				@include('errors.list')
				{!! Form::close() !!}

				{{--
				<hr>
				<div class="panel-body">
					<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
				</div>
				--}}
		
			</div>
		</div>

		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				
				<table class="table" style="font-size: large">
				<tr>
					<td>ORDER MADE <span style="color:red">TILL  8:30</span></td>	
					<td>=====></td>
					<td>DELIVERY <span style="color:red">at 9:00</span></td>
				</tr>
				<tr>
					<td>ORDER MADE <span style="color:red">TILL 11:30</span></td>
					<td>=====></td>
					<td>DELIVERY <span style="color:red">at 12:00</span></td>
				</tr>
				<tr>
					<td>ORDER MADE <span style="color:red">AFTER 11:30</span></td>
					<td>=====></td>
					<td>DELIVERY TOMORROW <span style="color:red">at 7:00</span></td>
				</tr>
				
				</table>
							
			</div>
		</div>
	</div>
</div>
@endsection