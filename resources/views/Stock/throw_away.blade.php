@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default" >
				<div class="panel-heading">Create throw away (Skart)</div>

				{!! Form::open(['method'=>'POST', 'url'=>'/stockthow_away']) !!}

				 <div class="panel-body">
                	<p>Material/Artical: <span style="color:red;">*</span></p>
                    <select name="material" class="chosen">
                        <option value="" selected></option>
                    @foreach ($materials as $line)
                        <option value="{{ $line->material }}">
                            {{ $line->material }}
                        </option>
                    @endforeach
                    </select>
                </div>

				<div class="panel-body">
					<p>Type: <span style="color:red;">*</span></p>
					{!! Form::select('type', array(''=>'','Printing issue'=>'Printing issue','Return from line'=>'Return from line'), '', array('class' => 'form-control')) !!} 
				</div>
				<div class="panel-body">
					<p>Qty/Kolicina:  <span style="color:red;">*</span></p>
					{!! Form::number('qty', null, ['class' => 'form-control']) !!}
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