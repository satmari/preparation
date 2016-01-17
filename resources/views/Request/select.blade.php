@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Choose Request?</div>
				<div class="panel-heading"><span>Majstorica: <b>{{$leader}}</b></span></div>		
				<div class="panel-body">
					<div class="">
						<a href="{{url('/requestcreate')}}" class="btn btn-bc center-block">Barcode and Carelabel</a>
					</div>
				</div>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/requestcreatesec')}}" class="btn btn-warning center-block">II Quality</a>
					</div>
				</div>
				
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