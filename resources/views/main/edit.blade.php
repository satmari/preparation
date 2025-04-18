@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Po: <big><b>{{$po->po}}</b></big> Size: <big><b>{{$po->size}}</b></big></div>
				<br>
				

				{!! Form::model($po , ['method' => 'PATCH', 'url' => 'main/'.$po->id /*, 'class' => 'form-inline'*/]) !!}

				@if(Auth::check() && Auth::user()->level() == 1)
				<div class="panel-body">
					<span>Po Id:</span>
					{!! Form::input('number', 'id', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Po:</span>
					{!! Form::input('string', 'po', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Size:</span>
					{!! Form::input('string', 'size', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Style:</span>
					{!! Form::input('string', 'style', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Color:</span>
					{!! Form::input('string', 'color', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Color Desc:</span>
					{!! Form::input('string', 'color_desc', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Season:</span>
					{!! Form::input('string', 'season', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Total Order Qty:</span>
					{!! Form::input('number', 'total_order_qty', null, ['class' => 'form-control']) !!}
				</div>
				@endif
				
				<div class="panel-body">
					<span>Flash:</span>
					{!! Form::input('boolean', 'flash', null, ['class' => 'form-control']) !!}
				</div>
				
				<div class="panel-body">
					<span>Closed Po:</span>
					{{-- {!! Form::input('boolean', 'closed_po', null, ['class' => 'form-control']) !!} --}}
					{!! Form::select('closed_po', array('Open'=>'Open','Closed'=>'Closed'), 'closed_po', array('class' => 'form-control')) !!} 
				</div>
				@if(Auth::check() && Auth::user()->level() == 1)
				<div class="panel-body">
					<span>Brand:</span>
					{!! Form::input('string', 'brand', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Status:</span>
					{!! Form::input('string', 'status', null, ['class' => 'form-control']) !!}
				</div>
				<div class="panel-body">
					<span>Type:</span>
					{!! Form::input('string', 'type', null, ['class' => 'form-control']) !!}
				</div>
				@endif
				<div class="panel-body">
					<span>Comment:</span>
					{!! Form::input('text', 'comment', null, ['class' => 'form-control']) !!}
				</div>
				@if(Auth::check() && Auth::user()->level() == 1)
				<div class="panel-body">
					<span>Delivery date:</span>
					{!! Form::input('string', 'delivery_date', null, ['class' => 'form-control']) !!}
				</div>
				@endif
				<div class="panel-body">
					<span>Hangtag:</span>
					{!! Form::input('text', 'hangtag', null, ['class' => 'form-control']) !!}
				</div>
				

				<div class="panel-body">
					{!! Form::submit('Edit Po', ['class' => 'btn btn-warning center-block']) !!}
				</div>

				@include('errors.list')

				{!! Form::close() !!}

				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/main')}}" class="btn btn-default btn-lg center-block">Back to main menu</a>
					</div>
				</div>
					
			</div>
		</div>
	</div>
</div>

@endsection