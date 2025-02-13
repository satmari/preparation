@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-15 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading">BB by Marker {{ $marker }}</div>
                
                <a href="{{ url('/print_labels/'.$marker) }}" class="btn btn-default center-block">Print all labels</a>
                <a href="{{ url('/print_labels_no/'.$marker) }}" class="btn btn-default center-block">Print only not printed labels</a>
				<div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <!--
                <table class="table table-striped table-bordered" id="sort" 
                >
                
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
                

                <table class="table">
                        <tr>
                            <td>
                                Total boxes:
                            </td>
                            <td>
                            <big><b></b></big>
                            </td>
                        </tr>
                    </table>
                    <table class="table">
                        <thead>
                            <td>Cartonbox</td>
                            <td>Po</td>
                            <td>Style</td>
                            <td>Size</td>
                            <td>Color</td>
                            <td>Qty</td>
                        </thead>
                    @if(isset($bb))
                    <thead>
                        <tr>
                            <th>Marker</th>
                            <th>BlueBox</th>
                            <th>Qty</th>
                            <th>Style</th>
                            <th>Variant</th>
                            <th>Created</th>
                            <th>Printed</th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                -->
              
                    <table class="table">
                        <thead>
                            <td>marker</td>
                            <td>bb</td>
                            <td>qty</td>
                            <td>style</td>
                            <td>variant</td>
                            <td>created at</td>
                            <td>printed</td>
                        </thead>
                    @foreach ($bb as $line)

                        <tr>
                            <td>
                                @foreach($line as $key => $value)
                                    @if($key == 'marker')
                                        {{ $value }}
                                    @endif
                                @endforeach
                            </td>
                        
                            <td>
                                @foreach($line as $key => $value)
                                    @if($key == 'bb')
                                        {{ $value }}
                                    @endif
                                @endforeach
                            </td>
                       
                            <td>
                                @foreach($line as $key => $value)
                                    @if($key == 'qty')
                                        {{ $value }}
                                    @endif
                                @endforeach
                            </td>
                        
                        
                            <td>
                                @foreach($line as $key => $value)
                                    @if($key == 'style')
                                        {{ $value }}
                                    @endif
                                @endforeach
                            </td>
                        
                            <td>
                                @foreach($line as $key => $value)
                                    @if($key == 'variant')
                                        {{ $value }}
                                    @endif
                                @endforeach
                            </td>

                            <td>
                                @foreach($line as $key => $value)
                                    @if($key == 'created_at')
                                        {{ substr($value, 0, 19) }}
                                    @endif
                                @endforeach
                            </td>
                        
                            <td>
                                @foreach($line as $key => $value)
                                    @if($key == 'printed')
                                        @if($value == 0)
                                        <b>NO</b>
                                        @else
                                        <b>YES</b>
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                         

                    @endforeach

                    </tbody>
                    @endif


				<hr>
					
			</div>
		</div>
	</div>
</div>

@endsection