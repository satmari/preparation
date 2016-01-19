@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center">
            <div class="panel panel-default">
				<div class="panel-heading h-c">Carelabel Request Table</div>
				
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
                            <!-- <th data-sortable="true">Id</th> -->
                            <!-- <th>Po_Id</th> -->
                            <!-- <th>User_ID</th> -->
                            <th>Edit Request</th>
                            <th data-sortable="true">Po</th>
                            <th data-sortable="true">Size</th>
                            <th class="qty">Qty</th>
                            <th data-sortable="true">Style</th>
                            <th data-sortable="true">Color</th>
                            <th data-sortable="true">Module</th>
                            <th data-sortable="true">Leader</th>
                            <th data-sortable="true" class="status">Status</th>
                            <th class="h-ct to-print">C. to print</th>
                            <th class="h-ct on-stock">C. on stock</th>
                            <!-- <th>Type</th> -->
                            <th>Comment</th>
                            <th data-sortable="true">Created</th>
                            <!-- <th data-sortable="true">Updated</th> -->
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    
                    @foreach ($request_c as $req)

                        <tr>
                            <!-- <td>{{-- $req->id --}}</td> -->
                            <!-- <td>{{-- $req->po_id --}}</td> -->
                            <!-- <td>{{-- $req->user_id --}}</td> -->

                            @if(Auth::check() && Auth::user()->level() == 1)
                            <td><a href="{{ url('/carelabelrequesttable/edit/'.$req->id) }}" class="btn btn-info btn-xs center-block">Edit</a></td>
                            @endif

                            @if(Auth::check() && Auth::user()->level() == 2)
                            <td><a href="{{ url('/carelabelrequesttablep/edit/'.$req->id) }}" class="btn btn-info btn-xs center-block">Edit</a></td>
                            @endif
                            
                            <td>{{ $req->ponum }}</td>
                            <td>{{ $req->size }}</td>
                            <td>{{ $req->qty }}</td>
                            <td>{{ $req->style }}</td>
                            <td>{{ $req->color }}</td>
                            <td>{{ $req->module }}</td>
                            <td>{{ $req->leader }}</td>
                            <td>{{ $req->status }}</td>
                            
                            <td>{{ $req->total_order_qty-$req->stock_c }}</td>
                            <td>{{ $req->stock_c-$req->request_c }}</td>
                            <!-- <td>{{-- $req->type --}}</td> -->
                            <td>{{ $req->comment }}</td>
                            <td>{{ $req->created_at }}</td>
                            <!-- <td>{{-- $req->updated_at --}}</td> -->
                            
                        </tr>
                    
                    @endforeach
                    </tbody>	
			</div>
		</div>
	</div>
</div>
@endsection