@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-15 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading">BarcodeRequest Table</div>
				<br>
				<p>{{-- $allpo --}}</p>

				@if(Auth::check() && Auth::user()->level() <= 2)
			
				{!! $table->render() !!}
				
				@endif

				<hr>
					
			</div>
		</div>
	</div>
</div>

@endsection