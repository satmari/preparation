@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-8 col-md-offset-2">
            <div class="panel panel-default">
				<div class="panel-heading">Preparation Location list
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    
                    <a href="{{ url('/location_create') }}" class="btn btn-success btn-xs center-blo ck">Create location</a>
                </div>
				
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
                            <th data-sortable="true">Location</th>
                            <th data-sortable="true">Location Description</th>
                            <th data-sortable="true">Plant</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    
                    @foreach ($locations as $req)

                        <tr>
                            <!-- <td>{{-- $req->id --}}</td> -->
                            <td>{{ $req->location }}</td>
                            <td>{{ $req->location_desc }}</td>
                            <td>{{ $req->location_plant }}</td>
                            <td><a href="{{ url('/location_edit/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit</a></td>
                        </tr>
        
                    @endforeach
                    </tbody>				
			</div>
		</div>
	</div>
</div>
@endsection