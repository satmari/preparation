@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-15 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading">BB by Marker {{ $marker }}</div>
                
                <a href="{{ url('/print_labels/'.$marker) }}" class="btn btn-default center-block">Print labels</a>
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
                            <th>Marker</th>
                            <th>BlueBox</th>
                            <th>Qty</th>
                            <th>Style</th>
                            <th>Variant</th>
                            <th>Created</th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    
                    @foreach ($inteosmarker as $line)

                        <tr>
                            <td>{{ $line->marker }}</td>
                            <td>{{ substr($line->bb,0,5) }} <b>{{ substr($line->bb,5,3) }}</b></td>
                            <td>{{ $line->qty }}</td>
                            <td>{{ $line->style }}</td>
                            <td>{{ $line->variant }}</td>
                            <td>{{ substr($line->created, 0, 19) }}</td>
                            
                            
                        </tr>
                    
                    @endforeach
                    </tbody>


				<hr>
					
			</div>
		</div>
	</div>
</div>

@endsection