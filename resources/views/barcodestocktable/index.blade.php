@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-15 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading h-b">BarcodeStock Table</div>
				
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
                            <th>Po_Id</th>
                            <th>User_ID</th>
                            <th data-sortable="true">Po</th>
                            <th>Size</th>
                            <th data-sortable="true">Qty</th>
                            <th data-sortable="true">Module</th>
                            
                            <th data-sortable="true">Type</th>
                            <th>Comment</th>
                            <th data-sortable="true">Created</th>
                            <th data-sortable="true">Updated</th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    
                    @foreach ($stock_b as $sto)

                        <tr>
                            <td>{{ $sto->id }}</td>
                            <td>{{ $sto->po_id }}</td>
                            <td>{{ $sto->user_id }}</td>
                            <td>{{ $sto->ponum }}</td>
                            <td>{{ $sto->size }}</td>
                            <td>{{ $sto->qty }}</td>
                            <td>{{ $sto->module }}</td>
                            
                            <td>{{ $sto->type }}</td>
                            <td>{{ $sto->comment }}</td>
                            <td>{{ $sto->created_at }}</td>
                            <td>{{ $sto->updated_at }}</td>
                            
                        </tr>
                    
                    @endforeach
                    </tbody>


				<hr>
					
			</div>
		</div>
	</div>
</div>

@endsection