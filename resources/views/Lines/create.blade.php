@extends('app')

@section('content')




<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				
				<div class="panel-heading">Create new Request (new)</div>
				<div class="panel-heading"><span>Majstorica: <b>{{$leader}}</b></span></div>

				{!! Form::open(['method'=>'POST', 'url'=>'lines_requeststore']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}">
				{!! Form::hidden('leader', $leader, ['class' => 'form-control']) !!}

				<div class="panel-body">
					<p>Po/Komesa: <span style="color:red">Obavezno <span style="font-size:20px;">7</span> cifara</span></p>
					{!! Form::number('po', null, ['id' => 'po_new', 'class' => 'form-control', 'autofocus' => 'autofocus', 'required' => 'required']) !!}
				</div>
				
				<div class="panel-body">
					<table>
						
						<div class="col-md-6">
							<b>Barcode</b>
							{!! Form::checkbox('barcode', 1 , null, ['id' => 'check', 'class' => 'form-control']); !!}
						</div>
						<div class="col-md-6">
							<b>Carelabel</b>
							{!! Form::checkbox('carelabel', 1 ,null , ['id' => 'check', 'class' => 'form-control']); !!}
						</div>
					</table>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    let barcode = document.querySelector("input[name='barcode']:checked");
    let carelabel = document.querySelector("input[name='carelabel']:checked");

    if (!barcode && !carelabel) {
        alert("You must select at least one option, Barcode OR/AND Carelabel!");
        event.preventDefault(); // Prevent form submission
    }
});
</script>

				</div>

				<div class="panel-body">
					<p>Comment: </p>
					<p><span style="color:red;">** Obavezno u komentar unesite kolicinu **</span></p>
					{!! Form::text('comment', null, ['class' => 'form-control', 'required' => 'required']) !!}
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

