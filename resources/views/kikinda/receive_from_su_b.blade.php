@extends('app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center ">
        <div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				
				
				<div class="input-group"> <span class="input-group-addon">Filter</span>
				    <input id="filter" type="text" class="form-control" placeholder="Type here...">
				</div>


				

				<table class="table table-striped table-bordered" id="sort" 
       			data-show-export="true"
       			data-export-types="['excel']"
       			>
				<!--
				data-show-export="true"
       			data-export-types="['excel']"
				data-search="true"
		       	data-show-refresh="true"
		       	data-show-toggle="true"
		       	data-query-params="queryParams" 
		       	data-pagination="true"
		       	data-height="300"
		       	data-show-columns="true" 
		       	data-export-options='{
				         "fileName": "preparation_app", 
				         "worksheetName": "test1",         
				         "jspdf": {                  
				           "autotable": {
				             "styles": { "rowHeight": 20, "fontSize": 10 },
				             "headerStyles": { "fillColor": 255, "textColor": 0 },
				             "alternateRowStyles": { "fillColor": [60, 69, 79], "textColor": 255 }
				           }
				         }
				       }'
		       	-->
				    <thead>
				        <tr>
				            <!-- <th data-sortable="true">Id</th> -->
				            <th data-sortable="true" class="po">Po</th>
				            <th class="size">Size</th>
				            <th data-sortable="true" class="style">Style</th>
				            <th class="color">Color</th>
				            <th data-sortable="true" class="">Qty to receive</th>
				            
				            <th></th>
				            
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $req)
					    <tr>
					       
					        <td>{{ $req->po_new }}</td>
					        <td>{{ $req->size }}</td>
					        <td>{{ $req->style }}</td>
					        <td>{{ $req->color }}</td>
					        <td>{{ $req->qty }}</td>
					        <td>
					        	<a href="{{ url('/receive_from_su_b_post/'.$req->id.'/'.$req->qty) }}" class="btn btn-success btn-xs center-block">Receive</a>
					        </td>
					        
					    </tr>
					@endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection