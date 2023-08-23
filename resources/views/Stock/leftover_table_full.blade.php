@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading "><big>Leftover FULL Table</big> &nbsp; &nbsp; &nbsp;
                    <a href="{{url('/leftover')}}" class="btn btn-success btn-xs">SUM table</a>
                    <a href="{{url('/import')}}" class="btn btn-info btn-xs">Import</a>
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
                            
                            <th data-sortable="true">Material</th>
                            <th data-sortable="true">SKU</th>
                            <th data-sortable="true">Price</th>
                            <th data-sortable="true">Location</th>
                            <th data-sortable="true">Place</th>
                            <th data-sortable="true">Qty</th>
                            <th data-sortable="true">Status</th>
                            <th data-sortable="true">Updated</th>

                        </tr>
                    </thead>
                    <tbody class="searchable">
                    
                    @foreach ($leftovers as $po)

                        <tr>
                            
                            <td><pre>{{ $po->material }}</pre></td>
                            <td><pre>{{ $po->sku }}</pre></td>
                            <td><pre>{{ round($po->price,2) }}</pre></td>
                            <td>{{ $po->location }}</td>
                            <td>{{ $po->place }}</td>
                            <td>{{ $po->qty }}</td>
                            <td>{{ $po->status }}</td>
                            <td>{{ $po->updated_at }}</td>
                            
                        </tr>
                    
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection