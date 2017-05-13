@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-15 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading h-c">cartigio Table</div>
				
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
                            
                            <td>id</td>
                            <th data-sortable="true"></th>
                            <th data-sortable="true"></th>
                            <th data-sortable="true"></th>
                            <th data-sortable="true"></th>
                            
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    
                    @foreach ($data as $d)

                        <tr>
                            <td>{{ $d->id }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            
                        </tr>
                    
                    @endforeach
                    </tbody>


				<hr>
					
			</div>
		</div>
	</div>
</div>

@endsection