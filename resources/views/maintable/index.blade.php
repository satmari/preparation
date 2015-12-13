@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-15 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading">Main Table</div>
				<br>
				Potest (hasMany):
				<br>
				{!!  $potest !!}
				<hr>
				Foreach as:
				@foreach ($potest as $barcode)
					<h3>{{$barcode->ponum}}-{{$barcode->size}}</h3>
					<p>{{$barcode->qty}}</p>
				@endforeach
				<hr>
				With foreach in controller: {!! $test !!}
				<hr>
				Sum in query:
				<h2>{!! $sum !!}</h2>

				<hr>
				Bar (belongs to): 
				<h2>{!! $bartest !!}</h2>
				<h2>{!! $bartest1 !!}</h2>
				

				@if(Auth::check() && Auth::user()->level() <= 2)
			
				{!! $table->render() !!}
				
				@endif

				<hr>
					
			</div>
		</div>
	</div>
</div>

@endsection