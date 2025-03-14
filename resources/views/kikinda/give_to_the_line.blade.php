@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Give to the line</div>
				
				@if (isset($msge))
					<div class="alert alert-danger" role="alert">
				        {{ $msge }}
				    </div>
				@endif 
				@if (isset($msgs))
				    <div class="alert alert-success" role="alert">
				        {{ $msgs }}
				    </div>
				@endif
				
				{!! Form::open(['method'=>'POST', 'url'=>'give_to_the_line_post']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}">
				
				<div class="panel-body">
                    <p>Po/Komesa: <span style="color:red">*</span><span style="color:red">Obavezno <span style="font-size:20px">7</span> cifara</span></p>
                    {!! Form::select('po', $posArray, null, ['name'=>'po','class' => 'form-con trol', 'class'=>'chosen','required'=>'requ ired']) !!}
                </div>

				<div class="panel-body">
					<p>Qty/Kolicina: </p>
					{!! Form::number('qty', null, ['class' => 'form-control', 'required' => 'required' ]) !!}
				</div>

				<div class="panel-body">
                    <p>Line/Module:</p>
                    {!! Form::select('line', $locationsArray, null, ['name'=>'location','class' => 'form-cont rol','class'=>'chosen','required'=>'requ ired']) !!}
                </div>

				<div class="panel-body">
					<div class="col-md-6">
						<span><b>Barcode</b></span>
						{!! Form::checkbox('barcode', 1, null, ['id' => 'check', 'class' => 'form-control']); !!}
					</div>
					<div class="col-md-6">
						<span><b>Carelabel</b></span>
						{!! Form::checkbox('carelabel', 1, null , ['id' => 'check', 'class' => 'form-control']); !!}
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

			</div>
		</div>
	</div>
</div>
@endsection

