<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Preparation</title>

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
	<link href="{{ asset('/css/choosen.css') }}" rel='stylesheet' type='text/css'>
	<!-- <link rel="manifest" href="{{ asset('/css/manifest.json') }}"> -->
		
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
				<a class="navbar-brand" href="http://172.27.161.171/preparation"><b>Preparation</b></a>
				<a class="navbar-brand" href="#">|</a>
				@if(Auth::check() && (Auth::user()->level() == 2 OR Auth::user()->level() == 7 OR Auth::user()->level() == 8)) 
				
				@else
					<a class="navbar-brand" href="http://172.27.161.171/trebovanje"><b>Trebovanje</b></a>
					<a class="navbar-brand" href="#">|</a>
					<!-- <a class="navbar-brand" href="http://172.27.161.171/downtime"><b>Downtime</b></a>
					<a class="navbar-brand" href="#">|</a> -->
					<a class="navbar-brand" href="http://172.27.161.171/cutting"><b>Cutting</b></a>
					<a class="navbar-brand" href="#">|</a>
				@endif


				@if(Auth::check() && Auth::user()->level() == 4)
					<a class="navbar-brand" href="http://172.27.161.172/pdm"><span style="color:red;"><b>PDM</b></span></a></li>
					<a class="navbar-brand" href="">|</a>
					
					<!-- <a class="navbar-brand" href="http://172.27.161.212"><span style="color:green;"><b>IntApp</b></span></a></li>
					<a class="navbar-brand" href="">|</a> -->
				@endif

				@if(Auth::check() && Auth::user()->level() == 7)
					
					<a class="navbar-brand" href=""></a>
					
					<ul class="nav navbar-nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								Functions<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('kikinda_stock') }}">Stock table</a></li>
								<li><a href="{{ url('receive_from_su_b') }}">Receive Barcodes from Subotica</a></li>
								<li><a href="{{ url('receive_from_su_c') }}">Receive Carelabels from Subotica</a></li>
								<li><a href="{{ url('give_to_the_line') }}">Give to the line</a></li>
								<li><a href="{{ url('#') }}">Return to Subotica</a></li>
								<li><a href="{{ url('#') }}">Trow away</a></li>
								
								
							</ul>
						</li>
					</ul>
					<!-- <a class="navbar-brand" href="">|</a> -->
					<ul class="nav navbar-nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								Locations<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('prep_locations') }}">Location list</a></li>
								<li><a href="{{ url('location_assign') }}">Add PO to location</a></li>
							</ul>
						</li>
					</ul>
					<!-- <a class="navbar-brand" href="http://172.27.161.212"><span style="color:green;"><b>IntApp</b></span></a></li> -->
					
				@endif

				@if(Auth::check() && Auth::user()->level() == 8)
					
					<a class="navbar-brand" href=""></a>
					
					<!-- <a class="navbar-brand" href="http://172.27.161.212"><span style="color:green;"><b>IntApp</b></span></a></li>
					<a class="navbar-brand" href="">|</a> -->
				@endif
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				
				@if(Auth::check() && Auth::user()->level() <= 3)
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/maintable') }}">Main Table</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/maintable_planer') }}">Main Table (planers)</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/main') }}">Po Table</a></li>
					</ul>
					{{-- 
					<ul class="nav navbar-nav">
						<li><a style="color:#D6E9C6" href="{{ url('/barcodestock') }}">Barcode Stock</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a style="color:#BCE8F1" href="{{ url('/carelabelstock') }}">Carelabel Stock</a></li>
					</ul>
					--}}
					<ul class="nav navbar-nav">
						<li><a style="color:#FFF" href="{{ url('/stock') }}">Stock</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a style="color:#D6E9C6" href="{{ url('/barcoderequesttable') }}">Barcode Requests</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a style="color:#BCE8F1" href="{{ url('/carelabelrequesttable') }}">Carelabel Requests</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/requestcreatep') }}"><span style="color:red;">Manual Request</span></a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a style="color:#FFBA8E" href="{{ url('/secondqrequesttable') }}">II quality Requests</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/import') }}">Import</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/log') }}">Log tables</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/bb_by_marker') }}">BB by Marker</a></li>
					</ul>
					<ul class="nav navbar-nav">
						<li><a href="{{ url('/leftover') }}">Leftover</a></li>
					</ul>
					
					<ul class="nav navbar-nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								Locations<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('prep_locations') }}">Location list</a></li>
								<li><a href="{{ url('location_assign') }}">Add PO to location</a></li>
							</ul>
						</li>
					</ul>

					
					@if(Auth::check() && Auth::user()->level() <= 1)
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/request') }}">Request from Modul</a></li>
						</ul>
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/import') }}">Import</a></li>
						</ul>
						<ul class="nav navbar-nav">
							<li><a href="{{ url('/importmodules') }}">Import module</a></li>
						</ul>
					@endif

				@endif

				@if(Auth::check() && Auth::user()->level() == 4)
				<ul class="nav navbar-nav">
					<li><a style="color:#D6E9C6" href="{{ url('/barcoderequesttablelogmodule') }}">History</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a style="color:#BCE8F1" href="{{ url('/carelabelrequesttablelogmodule') }}">History</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a style="color:#FFBA8E" href="{{ url('/secondqrequesttablelogmodule') }}">History</a></li>
				</ul>
				@endif
				

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						{{--<li><a href="{{ url('/auth/register') }}">Register</a></li>--}}
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
	<script src="{{ asset('/js/choosen.js') }}" type="text/javascript" ></script>
    
<script type="text/javascript">
$(function() {
    	
	$('#po').autocomplete({
		minLength: 3,
		autoFocus: true,
		source: '{{ URL('getpodata')}}'
	});
	$('#po_new').autocomplete({
		minLength: 3,
		autoFocus: true,
		source: '{{ URL('getpo_new_data')}}'
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

	$('.table tr').each(function(){
  		
  		//$("td:contains('pending')").addClass('pending');
  		//$("td:contains('confirmed')").addClass('confirmed');
  		//$("td:contains('back')").addClass('back');
  		//$("td:contains('error')").addClass('error');
  		//$("td:contains('TEZENIS')").addClass('tezenis');

  		// $("td:contains('TEZENIS')").function() {
  		// 	$(this).index().addClass('tezenis');
  		// }
	});

	$(".chosen").chosen();

	$('.to-print').each(function(){
		var qty = $(this).html();
		//console.log(qty);

		if (qty == 0 ) {
			$(this).addClass('zuto');
		} else if (qty > 0) {
			$(this).addClass('zeleno');
		} else if (qty < 0 ) {	
			$(this).addClass('crveno');
		}
	});

	$('.status').each(function(){
		var status = $(this).html();
		//console.log(qty);

		if (status == 'pending' ) {
			$(this).addClass('pending');
		} else if (status == 'confirmed') {
			$(this).addClass('confirmed');
		} else {	
			$(this).addClass('back');
		}
	});

	// $('td').click(function() {
	//    	var myCol = $(this).index();
 	//    	var $tr = $(this).closest('tr');
 	//    	var myRow = $tr.index();

 	//    	console.log("col: "+myCol+" tr: "+$tr+" row:"+ myRow);
	// });

	

});
</script>


{{-- 
<script type="text/javascript">
	$(document).ready(function() { 
		$("img.source-image").hide();
		var $source = $("img.source-image").attr("src");
		$('#page-body').css({
			'backgroundImage': 'url(' + $source +')',
			'backgroundRepeat': 'no-repeat',
			'backgroundPosition': 'top center'
		});
	}); 
</script>
</head>
<body id="page-body">
	<img class="source-image" src="{{  asset('/css/images/cr/2.jpg') }}" alt="" />
</body>
--}}


</body>
</html>


