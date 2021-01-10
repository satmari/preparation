@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				{{--<div class="panel-heading">Create new Request<span class="pull-right">Majstorica: <b>{{$leader->leader}}</b></span></div>--}}
				<div class="panel-heading">Create new Request - Preparation</div>
				{{--<div class="panel-heading"><span>Majstorica: <b>Preparation</b></span></div>--}}	

				{!! Form::open(['method'=>'GET', 'url'=>'/requeststorep']) !!}

				{{-- {!! Form::hidden('leader', $leader, ['class' => 'form-control']) !!} --}}

				<div class="panel-body">
					<p>Po/Komesa: <span style="color:red">*</span><span style="color:red">Obavezno 6 cifara</span></p>
					{!! Form::number('po', null, ['id' => 'po', 'class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>
				<div class="panel-body">
					<p>Size/Velicina: <span style="color:red">*</span></p>
					{!! Form::select('size', array(''=>'','XS'=>'XS','S'=>'S','M'=>'M','L'=>'L','XL'=>'XL','XXL'=>'XXL','M/L'=>'M/L','S/M'=>'S/M','XS/S'=>'XS/S','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','3-4'=>'3-4','5-6'=>'5-6','7-8'=>'7-8','9-10'=>'9-10','11-12'=>'11-12','LSHO'=>'LSHO','SSHO'=>'SSHO','MSHO'=>'MSHO','XSSHO'=>'XSSHO','TU'=>'TU','1/2'=>'1/2','3/4'=>'3/4'), '', array('class' => 'form-control')) !!} 
				</div>
				<div class="panel-body">
					<p>Qty/Kolicina: </p>
					<p><i>Empty => order pending | Qty => order completed</i></p>
					{!! Form::number('qty', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<p>Module: </p>
					{!! Form::text('module', null, ['id' => 'module', 'class' => 'form-control']) !!} 
				</div>
				<div class="panel-body">
					<p>Leader: </p>
					{!! Form::text('leader', null, ['class' => 'form-control']) !!} 
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<span><b>Barcode</b></span>
						{!! Form::checkbox('barcode', 1 , null, ['id' => 'check', 'class' => 'form-control']); !!}
					</div>
					<div class="col-md-6">
						<span><b>Carelabel</b></span>
						{!! Form::checkbox('carelabel', 1 ,null , ['id' => 'check', 'class' => 'form-control']); !!}
					</div>
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

				
				{{--
				<hr>
				<div class="panel-body">
					<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
				</div>
				--}}
				
			</div>
		</div>
	</div>
</div>
@endsection

