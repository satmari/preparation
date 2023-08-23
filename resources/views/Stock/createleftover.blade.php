@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default" >
				<div class="panel-heading">Create Leftover</div>

				{!! Form::open(['method'=>'POST', 'url'=>'/stockstoreleftover']) !!}

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
                	<p>SKU: <span style="color:red;">*</span></p>
                    <select name="sku" class="chosen">
                        <option value="" selected></option>
                    @foreach ($skus as $line)
                        <option value="{{ $line->sku }}">
                            {{ $line->sku }}
                        </option>
                    @endforeach
                    </select>
                </div>

                <div class="panel-body">
					<p>Price: </p>
					{!! Form::number('price', null, ['class' => 'form-control','step' => '0.01']) !!}
				</div>


                <div class="panel-body">
					<p>Location: </p>
					{!! Form::select('location', array(''=>'', 'Subotica'=>'Subotica', 'Kikinda'=>'Kikinda'), '', array('class' => 'form-control')) !!} 
				</div>

				<div class="panel-body">
					<p>Place <b>(KIKINDA LOCATION ONLY)</b>: </p>
					{!! Form::select('place', array(''=>'', 
					'PREP 1-1'=>'PREP 1-1',
					'PREP 1-2'=>'PREP 1-2',
					'PREP 1-3'=>'PREP 1-3',
					'PREP 1-4'=>'PREP 1-4',
					'PREP 2-1'=>'PREP 2-1',
					'PREP 2-2'=>'PREP 2-2',
					'PREP 2-3'=>'PREP 2-3',
					'PREP 2-4'=>'PREP 2-4',
					'PREP 3-1'=>'PREP 3-1',
					'PREP 3-2'=>'PREP 3-2',
					'PREP 3-3'=>'PREP 3-3',
					'PREP 3-4'=>'PREP 3-4'), '', array('class' => 'form-control')) !!} 
				</div>

				<div class="panel-body">
					<p>Kolicina: </p>
					{!! Form::number('qty', null, ['class' => 'form-control']) !!}
				</div>

				<div class="panel-body">
					<p><big><b><span style="color:red">Status: </span></b></big></p>
					{!! Form::select('status', array('ON STOCK'=>'ON STOCK','USED'=>'USED'), '', array('class' => 'form-control')) !!} 
				</div>
				<hr>

				<div class="panel-body">
					{!! Form::submit('Save', ['class' => 'btn btn-success center-block']) !!}
				</div>

				@include('errors.list')
				{!! Form::close() !!}
		
			</div>
		</div>
	</div>
</div>
@endsection