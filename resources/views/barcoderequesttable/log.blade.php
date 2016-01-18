@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center">
            <div class="panel panel-default">
				<div class="panel-heading h-b">Barcode Request Log Table</div>
				
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
                            <th data-sortable="true">Leader</th>
                            <th data-sortable="true">Status</th>
                            <th>Type</th>
                            <th>Comment</th>
                            <th data-sortable="true">Created</th>
                            <th data-sortable="true">Updated</th>
                
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    
                    @foreach ($request_b as $req)

                        <tr>
                            <td>{{ $req->id }}</td>
                            <td>{{ $req->po_id }}</td>
                            <td>{{ $req->user_id }}</td>
                            <td>{{ $req->ponum }}</td>
                            <td>{{ $req->size }}</td>
                            <td>{{ $req->qty }}</td>
                            <td>{{ $req->module }}</td>
                            <td>{{ $req->leader }}</td>
                            <td>{{ $req->status }}</td>
                            <td>{{ $req->type }}</td>
                            <td>{{ $req->comment }}</td>
                            <td>{{ $req->created_at }}</td>
                            <td>{{ $req->updated_at }}</td>
                            
                        </tr>
                    
                    @endforeach
                    </tbody>
			</div>
		</div>
	</div>
</div>
@endsection