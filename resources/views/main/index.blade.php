@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Po Table</div>
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
                            <th data-sortable="true">Flash</th>
                            <th data-sortable="true">Closed po</th>
                            <th data-sortable="true">Brand</th>
                            <th>Comment</th>
                            <th data-sortable="true">Created</th>
                            <th data-sortable="true">Updated</th>
                            <th>Edit Po</th>
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    
                    @foreach ($pos as $po)

                        <tr>
                            <td>{{ $po->id }}</td>
                            <td>{{ $po->po }}</td>
                            <td>{{ $po->size }}</td>
                            <td>{{ $po->style }}</td>
                            <td>{{ $po->color }}</td>
                            <td>{{ $po->color_desc }}</td>
                            <td>{{ $po->season }}</td>
                            <td>{{ $po->total_order_qty }}</td>
                            <td>{{ $po->flash }}</td>
                            <td>{{ $po->closed_po }}</td>
                            <td>{{ $po->brand }}</td>
                            <td>{{ $po->comment }}</td>
                            <td>{{ $po->created_at }}</td>
                            <td>{{ $po->updated_at }}</td>
                            <td><a href="{{ url('/main/edit/'.$po->id) }}" class="btn btn-info btn-xs center-block">Edit</a></td>

                        </tr>
                    
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection