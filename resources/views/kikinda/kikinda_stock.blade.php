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
				            <!-- <th class="colordesc">Color desc</th> -->
				            
				            <th data-sortable="true" calss="flash">Flash</th>
				            <th data-sortable="true" class="brand">Brand</th>
				            <th data-sortable="true" class="">Order Qty</th>
				            
				            <th data-sortable="true" class="">Skeda</th>
				            <th data-sortable="true" class="">Stock Location</th>
				            <th data-sortable="true" class="">PO location</th>
				            

				            <th data-sortable="true" class="h-bt to-print">B Su-To print</th>
				            <th data-sortable="true" class="h-bt on-stock">B Su-On stock</th>
				            <th data-sortable="true" class="h-bt">B Kik-To receive</th>
				            <th data-sortable="true" class="h-bt">B Kik-Stock</th>
				            <th data-sortable="true" class="h-bt">B Kik-Given</th>


							<th data-sortable="true" class="h-ct to-print">C Su-To print</th>
				            <th data-sortable="true" class="h-ct on-stock">C Su-On stock</th>
				            <th data-sortable="true" class="h-ct">C Kik-To receive</th>
				            <th data-sortable="true" class="h-ct">C Kik-Stock</th>
				            <th data-sortable="true" class="h-ct">B Kik-Given</th>
				            
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $po)

				        <tr>
				        	{{--<td>{{ $po->id }}</td>--}}
				        	<td>58{{ $po->po_new }}</td>
				        	<td>{{ $po->size }}</td>
				        	<td>{{ $po->style }}</td>
				        	<td>{{ $po->color }}</td>
				        	<!-- <td>{{ $po->color_desc }}</td> -->
				        	
				        	<td>{{ $po->flash }}</td>
				        	<td>{{ $po->brand }}</td>
				        	<td>{{ $po->total_order_qty }}</td>
				        	
				        	<td>{{ $po->skeda }}</td>
				        	<td>{{ $po->location }}</td>
				        	<td>{{ $po->location_all }}</td>

				        	<td>{{ $po->total_order_qty - $po->stock_b }}</td>
				        	<td>{{ $po->stock_b - $po->request_b }}</td>
				        	<td>{{ $po->to_receive_ki_b }}</td>
				        	<td>{{ $po->stock_ki_b }}</td>
				        	<td>{{ $po->given_ki_b }}</td>

				        	<td>{{ $po->total_order_qty - $po->stock_c }}</td>
				        	<td>{{ $po->stock_c - $po->request_c }}</td>
				        	<td>{{ $po->to_receive_ki_c }}</td>
				        	<td>{{ $po->stock_ki_c }}</td>
				        	<td>{{ $po->given_ki_c }}</td>
				        	
						</tr>
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection