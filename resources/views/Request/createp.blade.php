@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				
				<div class="panel-heading">Create new Request - Preparation</div>
				
				{!! Form::open(['method'=>'GET', 'url'=>'requeststorep']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}">
				<div class="panel-body">
					<p>Po/Komesa: <span style="color:red">*</span><span style="color:red">Obavezno <span style="font-size:20px">7</span> cifara</span></p>
					{!! Form::number('po', null, ['id' => 'po_new', 'class' => 'form-control', 'autofocus' => 'autofocus', 'required' => 'required']) !!}
				</div>
				
				<div class="panel-body">
					<p>Qty/Kolicina: </p>
					<p><i>Empty => order pending | Qty => order completed</i></p>
					{!! Form::number('qty', null, ['class' => 'form-control', 'required' => 'required' ]) !!}
				</div>
				<div class="panel-body">
					<p>Module/line: </p>
					
					<select name="module" class="select form-control select-form chosen" style="width:328px !important">
                        <option value="" selected></option>
                        @foreach ($lines as $m)
                        <option value="{{ $m->location }}">
                            {{ $m->location }}
                        </option>
                        @endforeach
                    </select>
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

				
				
			</div>
		</div>
	</div>
</div>
@endsection

