@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center">
			<div class="panel panel-default">
				<div class="panel-heading">Main Table</div>
				<br>
				<div class="input-group"> <span class="input-group-addon">Filter</span>
				    <input id="filter" type="text" class="form-control" placeholder="Type here...">
				</div>
				<table class="table table-striped table-bordered" id="sort" 
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
				            <th data-sortable="true">Id</th>
				            <th data-sortable="true">Po</th>
				            <th>Size</th>
				            <th data-sortable="true">Style</th>
				            <th>Color</th>
				            <th>Color desc</th>
				            <th data-sortable="true">Season</th>
				            <th data-sortable="true">Total Order Qty</th>
				            <th data-sortable="true">B. printed</th>
				            <th data-sortable="true">B. to print</th>
				            <th data-sortable="true">B. on stock</th>
				            <th data-sortable="true">B. in modules</th>
				            <th data-sortable="true">C. printed</th>
				            <th data-sortable="true">C. to print</th>
				            <th data-sortable="true">C. on stock</th>
				            <th data-sortable="true">C. in modules</th>
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($postable as $po)

				        <tr>
				        	<td>{{ $po->id }}</td>
				        	<td>{{ $po->po }}</td>
				        	<td>{{ $po->size }}</td>
				        	<td>{{ $po->style }}</td>
				        	<td>{{ $po->color }}</td>
				        	<td>{{ $po->color_desc }}</td>
				        	<td>{{ $po->season }}</td>
				        	<td>{{ $po->total_order_qty }}</td>

				        	<td>{{ $po->stock_b }}</td>
				        	<td>{{ $po->total_order_qty - $po->stock_b }}</td>
				        	<td>{{ $po->stock_b - $po->request_b }}</td>
				        	<td>{{ $po->request_b }}</td>
				        	
				        	<td>{{ $po->stock_c }}</td>
				        	<td>{{ $po->total_order_qty - $po->stock_c }}</td>
				        	<td>{{ $po->stock_c - $po->request_c }}</td>
				        	<td>{{ $po->request_c }}</td>
						</tr>
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection