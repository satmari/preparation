@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-12 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading">Import PO</div>
				<br>
				
				<div>
					{!! $reader !!}
				</div>
					
			</div>
		</div>
	</div>
</div>

@endsection