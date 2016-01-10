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
	<link href="{{ asset('/css/bootstrap-table.css') }}" rel='stylesheet' type='text/css'>
	<!-- <link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel='stylesheet' type='text/css'> -->
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
				<a class="navbar-brand" href="{{ url('/') }}">Preparation Application</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/maintable') }}">Main Table</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/main') }}">Po Table</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/barcodestock') }}">Barcode Stock</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/barcodestocktable') }}">Barcode Stock Log</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/carelabelstock') }}">Carelabel Stock</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/carelabelstocktable') }}">Carelabel Stock Log</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/request') }}">Request from Modul</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/barcoderequesttable') }}">Barcode Requests</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/carelabelrequesttable') }}">Carelabel Requests</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/import') }}">Import</a></li>
				</ul>
				<!-- <ul class="nav navbar-nav">
					<li><a href="{{ url('/importmodules') }}">Import Modules form Inteos</a></li>
				</ul> -->
				
				

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
    <script src="{{ asset('/js/bootstrap-table.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/jquery-ui.min.js') }}" type="text/javascript" ></script>
	<!-- <script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jquery.tablesorter.min.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/custom.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/tableExport.js') }}" type="text/javascript" ></script>
	<!--<script src="{{ asset('/js/jspdf.plugin.autotable.js') }}" type="text/javascript" ></script>-->
	<!--<script src="{{ asset('/js/jspdf.min.js') }}" type="text/javascript" ></script>-->
	<script src="{{ asset('/js/FileSaver.min.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('/js/bootstrap-table-export.js') }}" type="text/javascript" ></script>
    
<script type="text/javascript">
$(function() {
    	
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
	$('#filter').keyup(function () {

        var rex = new RegExp($(this).val(), 'i');
        $('.searchable tr').hide();
        $('.searchable tr').filter(function () {
            return rex.test($(this).text());
        }).show();
	});

	$('#sort').bootstrapTable({
    
	});
	// function starsSorter(a, b) {
	//     if (a < b) return 1;
	//     if (a > b) return -1;
	//     return 0;
	// }


});
</script>

</body>
</html>

