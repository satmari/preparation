@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center">
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
				            <th class="colordesc">Color desc</th>
				            <th data-sortable="true" class="season">Season</th>
				            <th data-sortable="true" calss="flash">Flash</th>
				            <th data-sortable="true" class="brand">Brand</th>
				            <th data-sortable="true" class="">Order Qty</th>
				            <th data-sortable="true" class="">95%</th>
				            <th data-sortable="true" class="">Hangtag</th>
				            <th data-sortable="true" class="h-bt">B. printed</th>
				            <th data-sortable="true" class="h-bt to-print">B. to print</th>
				            <th data-sortable="true" class="h-bt on-stock">B. on stock</th>
				            <th data-sortable="true" class="h-bt">B. in modules</th>
				            <th data-sortable="true" class="h-ct">C. printed</th>
				            <th data-sortable="true" class="h-ct to-print">C. to print</th>
				            <th data-sortable="true" class="h-ct on-stock">C. on stock</th>
				            <th data-sortable="true" class="h-ct">C. in modules</th>
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($postable as $po)

				        <tr>
				        	{{--<td>{{ $po->id }}</td>--}}
				        	<td>{{ $po->po }}</td>
				        	<td>{{ $po->size }}</td>
				        	<td>{{ $po->style }}</td>
				        	<td>{{ $po->color }}</td>
				        	<td>{{ $po->color_desc }}</td>
				        	<td>{{ $po->season }}</td>
				        	<td>{{ $po->flash }}</td>
				        	<td>{{ $po->brand }}</td>
				        	<td>{{ $po->total_order_qty }}</td>
				        	<td>{{ round($po->total_order_qty*0.95) }}</td>
				        	<td>{{ $po->hangtag }}</td>

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