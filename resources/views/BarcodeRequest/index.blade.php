@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Barcode Request</div>			
				<div class="panel-body">
					<div class="">
						<a href="{{url('/barcoderequestcreate')}}" class="btn btn-success center-block">Create Request</a>
					</div>
				</div>


			</div>
		</div>
	</div>
</div>
@endsection