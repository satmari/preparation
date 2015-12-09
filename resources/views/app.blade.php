<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Preparation Application</title>

	
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	
	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

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

	<!-- Scripts 
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	-->
	<!--<script type="text/javascript" src="//code.jquery.com/jquery.js"></script>-->
	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>

	<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<!--
	<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
	-->
	
	<!--<script stype="text/javascript" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>-->
	<script stype="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
    <!-- App scripts 
    <script src="{{ asset('/js/file.js') }}"></script>-->

    <script type="text/javascript">
    	$(function() {
    		$('#users-table').dataTable({
    			createdRow: function( row, data, dataIndex ) {
         		   	if ( data['total_order_qty'] >= 5000 ) {
                		$('td', row).eq(6).addClass('highlight');
                		//$(row).addClass( 'important2' );
            		}
            		if (data['size'] == "S" ) {
      					$(row).addClass( 'important' );
    				}
        		},
        		
    	    	processing: true,
    	    	serverSide: false,
    	    	ajax: "{!! route('datatables.data') !!}",
    	    	columns: [
    	        	// { data: 'id'},
    	        	// { data: 'po_size'},
    	        	// { data: 'order_code'},
    	        	{ data: 'po'},
    	        	{ data: 'size'},
    	        	{ data: 'style'},
    	        	{ data: 'color', orderable: false},
    	        	{ data: 'color_desc', orderable: false},
    	        	{ data: 'season'},
    	        	{ data: 'total_order_qty' },
    	        	{ data: 'flash' , searchable: false},
    	        	{ data: 'closed_po', searchable: false},
    	        	// { data: 'created_at'},
            		// { data: 'updated_at'}
    	        ],
    	        aLengthMenu: [
        			[25, 50, 100, 200, -1],
        			[25, 50, 100, 200, "All"]
    			],
    			//iDisplayLength: 50,
    			iDisplayLength: -1,

    			scrollY:        '600px',
        		scrollCollapse: true,
        		paging:         false,
        		
        		initComplete: function () {
            		this.api().columns().every(function () {
                	var column = this;
                	var input = document.createElement("input");
                		$(input).appendTo($(column.footer()).empty())
                			.on('change', function () {
                    			column.search($(this).val(), false, false, true).draw();
                		});
            		});
        		}

    		});
		});
	</script>
</body>
</html>
