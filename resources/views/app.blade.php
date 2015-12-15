<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Preparation Application</title>

	
	<!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel='stylesheet' type='text/css' > -->
    <!-- <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel='stylesheet' type='text/css'> -->
    <!-- <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css' > -->

	
	<link href="{{ asset('/css/app.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/font.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/bootstrap.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/jquery-ui.min.css') }}" rel='stylesheet' type='text/css'>
	<link href="{{ asset('/css/custom.css') }}" rel='stylesheet' type='text/css'>
		
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Preparation Application</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Home</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/datatables') }}">Main Table</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/barcodestock') }}">Barcode Stock</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- App scripts -->

    <script src="{{ asset('/js/jquery.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/jquery-ui.min.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script>
	<!--<script src="{{ asset('/js/custom.js') }}" type="text/javascript" ></script>-->
    
<script type="text/javascript">
$(function() {
    $('#users-table').dataTable({
    	processing: true,
    	serverSide: false,
    	ajax: "{!! route('datatables.data') !!}",
    	columns: [
    		// {data: 'id'},
    	    // { data: 'po_key'},
    	    // { data: 'order_code'},
    	    { data: 'po', name: 'po'},
    	    { data: 'size', name: 'size'},
    	    { data: 'style', name: 'style'},
    	    { data: 'color', name: 'color', orderable: false},
    	    { data: 'color_desc', name: 'color_desc', orderable: false},
    	    { data: 'season', name: 'season'},
    	    { data: 'total_order_qty', name: 'total_order_qty', searchable: false},
    	    
    	    { data: 'stock_qty', name: 'stock_qty', searchable: false},
    	    { data: 'b_stock', name: 'b_stock', searchable: false},

    	    { data: 'b_request', name: 'b_request', searchable: false},
			{ data: 'request_qty', name: 'request_qty', searchable: false},

    	    //{ data: 'updated_at', name: 'updated_at'},
			

    	    //{ data: 'flash', name: 'flash', searchable: false},
    	    //{ data: 'closed_po', name: 'closed_po', searchable: false},
    	    //{ data: 'brand', name: 'brand', searchable: false},
    	    //{ data: 'status', name: 'status', searchable: false},
    	    //{ data: 'type', name: 'type', searchable: false},
    	    //{ data: 'comment', name: 'comment', searchable: false},
    	    
            
    	],
    	aLengthMenu: [
        	[25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
    	],
    	//iDisplayLength: 50,
    	iDisplayLength: -1,
		scrollY:        '380px',
   		scrollCollapse: true,
   		paging:         false,

       	createdRow: function( row, data, dataIndex ) {
           	//if ( data['total_order_qty'] < 1000 ) {
           	if ( data['b_request'] == 0 ) {
       	    	$('td', row).eq(9).addClass('highlightred');
           		//$(row).addClass( 'important2' );
       		}
       		if ( data['b_request'] > 100 ) {
       	    	$('td', row).eq(9).addClass('highlightgreen');
           		//$(row).addClass( 'important2' );
       		}
       		if (data['size'] == "S" ) {
    			$(row).addClass('important');
    		}
    	},
    	/*columnDefs: [{
            "targets": [ 11 ],
            "visible": false,
            "searchable": false
        }],*/
        /*
        "columnDefs": [{
            "visible": false,
            //"targets": -1
        }],
		*/
   		/*initComplete: function () {
      		this.api().columns().every(function () {
              	var column = this;
               	var input = document.createElement("input");
               		$(input).appendTo($(column.footer()).empty())
               			.on('change', function () {
                   			column.search($(this).val(), false, false, true).draw();
               		});
           		});
    	},*/

    	
        
   	});
	$('input:text').bind ({
	});
	$('#po').autocomplete({
		minLength: 3,
		autoFocus: true,
		source: '{{ URL('getpodata')}}'
	});
	$('#module').autocomplete({
		minLength: 1,
		autoFocus: true,
		source: '{{ URL('getmoduledata')}}'
	});
	$('#size').val();
});
</script>

</body>
</html>

