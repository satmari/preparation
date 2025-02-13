@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<!-- 
			<div class="panel panel-default">
				<div class="panel-heading">Import PO from Excel file</div>
				
				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@postImportPo']]) !!}
					<div class="panel-body">
						{!! Form::file('file', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import Po', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

			
			</div>
 			-->

			<!-- <div class="panel panel-default">
				<div class="panel-heading">Update Hangtag PO from Excel file</div>
				
				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@postImportHangtag']]) !!}
					<div class="panel-body">
						{!! Form::file('file1', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import Hangtag', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

			</div> -->

			<!-- <div class="panel panel-default">
				<div class="panel-heading">Import Users</div>
				
				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@postImportUser']]) !!}
					<div class="panel-body">
						{!! Form::file('file2', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}

			</div> -->
		
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="">
						<a href="{{url('/update_po_from_posummary')}}" class="btn btn-warning btn center-block">Inset new and update PRO from POSummary</a>
					</div>
					<br>
					
					<div class="">
						<a href="{{url('/update_po_from_posummary_close')}}" class="btn btn-warning btn center-block">Close PRO from POSummary</a>
					</div>
				</div>

			</div>

			<div class="panel panel-default">
				<div class="panel-heading">Import Leftover <big>ON STOCK</big></div>
				
				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@postImportLeftoverPos']]) !!}
					<div class="panel-body">
						{!! Form::file('file3', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">Import Leftover <big>USED</big></div>
				
				{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@postImportLeftoverNeg']]) !!}
					<div class="panel-body">
						{!! Form::file('file4', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}
			</div>

			<div class="panel panel-default">
				<div class="panel-body">
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default btn-lg center-block">Back to main menu</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection