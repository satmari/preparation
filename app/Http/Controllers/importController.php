<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Maatwebsite\Excel\Facades\Excel;

use App\CarelabelStock;
use App\BarcodeStock;
use Request;
use App\Po;
use App\User;
use App\leftover;
use DB;

class importController extends Controller {

	public function index() {
		//
		return view('import.index');
	}
	
	public function postImportPo(Request $request) {
	 
	    $getSheetName = Excel::load(Request::file('file'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            //DB::table('pos')->truncate();
	
	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file'))->chunk(50, function ($reader)
	            {
	            	$readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {
	                	/*
	                	$order_code = $row['order_code']; // 16IC5800050657::00102::M
	                	$product = $row['product'];		  // 1MC07A   4619L
	                	// dd($order_code);

						$style = substr($product, 0, 8);
						$style = str_replace(' ', '', $style);
						
						// dd($style);
						var_dump("po: ".$order_code." , style: ".$style);

	                	//$sql = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM po WHERE order_code = '".$order_code."' "));

	                	$sql2 = DB::connection('sqlsrv')->select(DB::raw("SET NOCOUNT ON;
							UPDATE pos
							SET style = '".$style."'
							WHERE order_code = '".$order_code."';
							SELECT TOP 1 [id] FROM pos;
						"));	
	                	*/
						
	                	//var_dump($row['po_key']);
	                	//var_dump($row['po']);

	                	//$order_code = $row->order_code;
	                	//$order_code = $row['order_code'];
						//$po = substr($order_code, 4,10);
						//$rest = substr("abcdef", -3, 1); // returns "d" 16IC5800050657::00102::M
						
						//var_dump($po);
	                	$season = $row['season'];
	                	$order_code = $row['order_code']; // 16IC5800050657::00102::M
	                	$product = $row['product'];		  // 1MC07A   4619L
	                	$product_des = $row['product_description'];
	                	$qty = $row['total_qty'];
	                	$flash = $row['flash'];
	                	$delivery_date = $row['delivery_date'];
	                	
						
						// $delivery_date = $row->delivery_date->format('d-m-Y');
						// dd($delivery_date);

						//$yourData = date('d.m.Y', strtotime($delivery_date_in));
	                	//$long = strtotime($delivery_date_in);
	                	//dd($yourData);
	                	//dd($long);
	                	// $delivery_date = date('d.m.Y', $delivery_date_in);

	                	$unix_date = ($delivery_date - 25569) * 86400;
	                	$excel_date = 25569 + ($unix_date / 86400);
						$unix_date = ($excel_date - 25569) * 86400;
						// echo gmdate("Y-m-d", $unix_date);
						$delivery_date = gmdate("Y-m-d", $unix_date);
	                	// dd($delivery_date);
						
	                	$hangtag = $row['hangtag'];

	                	// $po = substr($order_code, 8, 6);
	                	$po_array = explode('::', $order_code);
	                	// dd($po_array);
	                	$po = substr($po_array[0], -6);
	                	// dd($po);

	                	// $size = substr($order_code, 23, 5);
	                	$size = $po_array[2];
						// dd($size);

						$style = substr($product, 0, 8);
						$color = substr($product, 9, 4);

						$po_key = $po ."-".$size;
						$brand = substr($order_code, 2, 1);

						if ($brand == "T") {
							$brand	= "TEZENIS";
						} elseif ($brand == "I") {
							$brand	= "INTIMISSIMI";
						} elseif ($brand == "C") {
							$brand	= "CALZEDONIA";
						} else {
							if ($style == 'LTD50C') {
								$brand	= "INTIMISSIMI";
							} elseif ($style == 'CMU05A') {
								$brand	= "INTIMISSIMI";
							} else {
								$brand = "";	
							}
						}

						// take brand from styles tabel in settings database
						$get_brand =DB::connection('sqlsrv5')->select(DB::raw("SELECT brand FROM [settings].[dbo].[styles] WHERE style = '".$style."' "));
						// dd($get_brand);

						if (isset($get_brand[0]->brand)) {
							$brand = $get_brand[0]->brand;
						} else {
							$brand = 'no info';
						}
  						// dd($brand);


						if ($flash == 'N') {
							$flash = '';
						} else {
							$flash = $flash ;
						}

						$closed = 'Open';

						$style = str_replace(' ', '', $style);
						$size = str_replace(' ', '', $size);
						$color = str_replace(' ', '', $color);

						
						$porder = new Po;
						$porder->po_key = $po_key;
						$porder->order_code = $order_code;
						$porder->po = $po;
						$porder->size = $size;
						$porder->style = $style;
						$porder->color = $color;
						$porder->color_desc = $product_des;
						$porder->season = $season;
						$porder->total_order_qty = $qty;
						$porder->flash = $flash;
						$porder->closed_po = $closed;
						$porder->brand = $brand;
						$porder->status;
						$porder->type;
						$porder->comment;
						$porder->delivery_date = $delivery_date;
						$porder->hangtag = $hangtag;
						$porder->sap_material = $product;
						// $porder->save();

						// $order_id =DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM pos WHERE order_code = '".$order_code."' "));
						// dd($order_id);

						$barcode = new BarcodeStock;
						$barcode->po_id = $porder->id; // ???
						// $barcode->po_id = $order_id[0]->id; // ???
						$barcode->user_id = 3;
						$barcode->ponum = $po;
						$barcode->size = $size;
						$barcode->qty = 0;
						$barcode->module;
						$barcode->status;
						$barcode->type = "insert";
						$barcode->comment;
						// $barcode->save();

						$carelabel = new CarelabelStock;
						$carelabel->po_id = $porder->id; // ???
						// $carelabel->po_id = $order_id[0]->id; // ???
						$carelabel->user_id = 3;
						$carelabel->ponum = $po;
						$carelabel->size = $size;
						$carelabel->qty = 0;
						$carelabel->module;
						$carelabel->status;
						$carelabel->type = "insert";;
						$carelabel->comment;
						// $carelabel->save();
	                }
	            });
	    }
		
	    //Session::flash('file_uploaded_successfully', 'File has been uploaded successfully and has also updated the database.');
	    return redirect('/');
	    //return view('import.importresult', compact('reader')); //ne koristi se
	}

	public function postImportHangtag(Request $request) {
	    $getSheetName = Excel::load(Request::file('file1'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            //DB::table('users')->truncate();
	
	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file1'))->chunk(50, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {
	                	
	                	$po = $row['po'];
						$hangtag = $row['hangtag'];

	                	$data = DB::connection('sqlsrv')->update(DB::raw("UPDATE pos
							SET [hangtag] = '".$hangtag."' 
							WHERE [po] = '".$po."' "));
					}
	            });
	    }
		return redirect('/');
	}

	public function postImportUser(Request $request) {
	    $getSheetName = Excel::load(Request::file('file2'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            //DB::table('users')->truncate();
	
	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file2'))->chunk(50, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {	
						/*
						$userbulk = new User;
						$userbulk->name = $row['user'];;
						$userbulk->email = $row['email'];
						$userbulk->password = bcrypt($row['pass']);
						// $userbulk->created_at = date('2019-00-00');
						// $userbulk->updated_at = date('2019-00-00');
						$userbulk->save();

						*/
	                }
	            });
	    }
		return redirect('/');
	}

	public function postImportRoll(Request $request) {
	    $getSheetName = Excel::load(Request::file('file3'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            //DB::table('users')->truncate();
	
	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file3'))->chunk(50, function ($reader)
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {
	                	
						$userbulk = new User;
						$userbulk->name = $row['user'];;
						$userbulk->email = $row['email'];
						$userbulk->password = bcrypt($row['pass']);
						//$userbulk->created_at = date(2015-12-22);
						//$userbulk->updated_at = date(2015-12-22);
												
						$userbulk->save();
						
	                }
	            });
	    }
		return redirect('/');
	}

	public function postImportUserRole(Request $request) {
	    $getSheetName = Excel::load(Request::file('file4'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            //DB::table('users')->truncate();
	
	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	    		/*
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file4'))->chunk(50, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {
	                	
						$userbulk = new User;
						$userbulk->name = $row['user'];
						$userbulk->email = $row['email'];
						$userbulk->password = bcrypt($row['pass']);
						//$userbulk->created_at = date(2015-12-22);
						//$userbulk->updated_at = date(2015-12-22);
												
						$userbulk->save();
						
	                }
	            });
	            */
	    }
		return redirect('/');
	}
	
	public function deleteIssueTable() { // Ne koristi se vise 
	 	// dd("deleteIssueTable");
	 	//DB::connection('sqlsrv3')->delete(DB::raw("DELETE FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit Issue]"));
	 	return redirect('http://172.27.161.221/Reports_GPD/Pages/Report.aspx?ItemPath=%2fTEST+SSRS%2fWarehouse%2fIssueTempTable');
	}

	public function postImportUpdatePass() {
	    
	    
	    
	    $sql = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM users"));

	    for ($i=0; $i < count($sql) ; $i++) { 
	    	
	    	// dd($sql[$i]->password);

	    	$password = bcrypt($sql[$i]->name);
	    	// dd($password);

			$sql2 = DB::connection('sqlsrv')->select(DB::raw("
					SET NOCOUNT ON;
					UPDATE [preparation].[dbo].[users]
					SET password = '".$password."'
					WHERE name = '".$sql[$i]->name."';
					SELECT TOP 1 [id] FROM [preparation].[dbo].[users];
				"));	    	

	    }

		return redirect('/');
	}

	public function update_po_from_posummary() {
	    
	//OPEN PRO	

	    // dd("test");
	    $posummary = DB::connection('sqlsrv6')->select(DB::raw("SELECT * FROM [posummary].[dbo].[pro]
	     WHERE status_int != 'Closed'  AND created_fr > '2020-06-30' AND deleted != 'DELETED' "));
	    // dd($posummary);

	    $x = 0;
	    $y = 0;
	
	    for ($i=0; $i < count($posummary); $i++) { 

	    		$pos = DB::connection('sqlsrv')->select(DB::raw("
	    			SELECT * FROM [preparation].[dbo].[pos] 
	    			WHERE SUBSTRING(order_code, 0,10) = '".$posummary[$i]->pro."' "));

	    		// dd($pos);
	    		// var_dump($pos);
	    		
	    		if (empty($pos[0]->id)) {

					// dd("not exist, insert");
					$x = $x +1;
					
						//var_dump($po);
	                	$season = $posummary[$i]->season;
	                	// dd($season);
	                	
	                	$order_code = substr($posummary[$i]->pro_fr,3); // 16IC5800050657::00102::L
	                	$product = $posummary[$i]->material;		  // 1MC07A   4619L
	                	$product_des = $posummary[$i]->color_desc;
	                	$qty = (int)$posummary[$i]->qty;
	                	$flash = $posummary[$i]->segment;
	                	$delivery_date = substr($posummary[$i]->delivery_date_orig,0,10);
	                	$skeda = $posummary[$i]->skeda;
	                	$no_lines_by_skeda = $posummary[$i]->no_lines_by_skeda;
	                	
						
	                	// $po = substr($order_code, 8, 6);
	                	$po_array = explode('::', $order_code);
	                	// dd($po_array);
	                	$po = substr($po_array[0], -7);
	                	// dd($po);

	                	$po_new = substr($po_array[0], -7);
	                	// dd($po_new);

	                	// $size = substr($order_code, 23, 5);
	                	$size = $po_array[2];
						// dd($size);

						$style = substr($product, 0, 8);
						$color = substr($product, 9, 4);

						$po_key = $po ."-".$size;
						// dd($po_key);
						// dd($posummary[$i]->brand);

						if (isset($posummary[$i]->brand)) {
							$brand = $posummary[$i]->brand;
						} else {
							$brand = 'no info';
						}
  						// dd($brand);
  						
						$closed = 'Open';

						$style = str_replace(' ', '', $style);
						$size = str_replace(' ', '', $size);
						$color = str_replace(' ', '', $color);

						$hangtag = ''; ///////////////////////////////////////////////////////////////?
						$hang =DB::connection('sqlsrv7')->select(DB::raw("SELECT material
						  FROM [trebovanje].[dbo].[sap_coois]
						  WHERE ((wc = 'WC01' AND material like 'ES%') OR (wc = 'WC01' AND material like 'AF%') OR (wc like 'WC04%' AND material like 'ET%'))  AND  po = '".$posummary[$i]->pro."' "));
						// dd($hang);

						if (isset($hang[0]->material)) {
							
							$hangtag = '';
							for ($c=0; $c < count($hang); $c++) { 
								$hangtag .= $hang[$c]->material." |";
							}
						}
						// dd($hangtag);
						
						$porder = new Po;
						$porder->po_key = $po_key;
						$porder->order_code = $order_code;
						$porder->po = $po;
						$porder->po_new = $po_new;
						$porder->size = $size;
						$porder->style = $style;
						$porder->color = $color;
						$porder->color_desc = $product_des;
						$porder->season = $season;
						$porder->total_order_qty = $qty;
						$porder->flash = $flash;
						$porder->closed_po = $closed;
						$porder->brand = $brand;
						$porder->status;
						$porder->type;
						$porder->comment;
						$porder->delivery_date = $delivery_date;
						$porder->hangtag = $hangtag;
						$porder->sap_material = $product;
						$porder->skeda = $skeda;
						$porder->no_lines_by_skeda = $no_lines_by_skeda;
						$porder->save();
						
						// $order_id =DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM pos WHERE order_code = '".$order_code."' "));
						// dd($order_id);

						$barcode = new BarcodeStock;
						$barcode->po_id = $porder->id; // ???
						// $barcode->po_id = $order_id[0]->id; // ???
						$barcode->user_id = 3;
						$barcode->ponum = $po;
						$barcode->size = $size;
						$barcode->qty = 0;
						$barcode->module;
						$barcode->status;
						$barcode->type = "insert";
						$barcode->comment;
						$barcode->save();

						$carelabel = new CarelabelStock;
						$carelabel->po_id = $porder->id; // ???
						// $carelabel->po_id = $order_id[0]->id; // ???
						$carelabel->user_id = 3;
						$carelabel->ponum = $po;
						$carelabel->size = $size;
						$carelabel->qty = 0;
						$carelabel->module;
						$carelabel->status;
						$carelabel->type = "insert";;
						$carelabel->comment;
						$carelabel->save();

	    		} else {

	    			// dd("exist, update");
	    			$y = $y + 1;
	    			
	    				$season = $posummary[$i]->season;
	                	$order_code = substr($posummary[$i]->pro_fr,3); // 16IC5800050657::00102::L
	                	$product = $posummary[$i]->material;		  // 1MC07A   4619L
	                	$product_des = $posummary[$i]->color_desc;
	                	$qty = (int)$posummary[$i]->qty;
	                	$flash = $posummary[$i]->segment;
	                	$delivery_date = substr($posummary[$i]->delivery_date_orig,0,10);
	                	$skeda = $posummary[$i]->skeda;
	                	$no_lines_by_skeda = $posummary[$i]->no_lines_by_skeda;

						$hangtag = ''; ///////////////////////////////////////////////////////////////?
						$hang =DB::connection('sqlsrv7')->select(DB::raw("SELECT material
						  FROM [trebovanje].[dbo].[sap_coois]
						  WHERE ((wc = 'WC01' AND material like 'ES%') OR (wc = 'WC01' AND material like 'AF%') OR (wc like 'WC04%' AND material like 'ET%'))  AND  po = '".$posummary[$i]->pro."' "));
						// dd($hang);

						if (isset($hang[0]->material)) {
							
							$hangtag = '';
							for ($c=0; $c < count($hang); $c++) { 
								$hangtag .= $hang[$c]->material." |";
							}
						}
						// dd($hangtag);					

	                	$po = substr($order_code, 8, 6);
	                	$po_array = explode('::', $order_code);
	                	// dd($po_array);
	                	$po = substr($po_array[0], -7);
	                	// dd($po);

	                	$size = substr($order_code, 23, 5);
	                	$size = $po_array[2];
						// dd($size);

						$style = substr($product, 0, 8);
						$color = substr($product, 9, 4);

						//$po_key = $po ."-".$size;
						
						if (isset($posummary[$i]->brand)) {
							$brand = $posummary[$i]->brand;
						} else {
							$brand = 'no info';
						}
  						// dd($brand);
						$closed = $posummary[$i]->status_int;
						// dd($closed);
						$style = str_replace(' ', '', $style);
						$size = str_replace(' ', '', $size);
						$color = str_replace(' ', '', $color);

						$da = date("Y-m-d H:i:s");
						
						$sql2 = DB::connection('sqlsrv')->update(DB::raw("
							UPDATE [preparation].[dbo].[pos]
							SET 
							 season = '".$season."',
							 total_order_qty = '".$qty."',
							 flash = '".$flash."',
							 closed_po = '".$closed."',
							 brand = '".$brand."',
							 delivery_date = '".$delivery_date."',
							 hangtag = '".$hangtag."',
							 skeda = '".$skeda."',
							 no_lines_by_skeda = '".$no_lines_by_skeda."',
							 updated_at = '".$da."'

							WHERE SUBSTRING(order_code, 0,10) = '".$posummary[$i]->pro."'
							"));

						//WHERE po = '".$posummary[$i]->po."' AND size = '".$posummary[$i]->size."';
						//SUBSTRING(order_code, 0,10) = '".$posummary[$i]->pro."'

	    		}
	    }
	//	    

	/*
	//CLOSED PRO
	    $posummary_closed = DB::connection('sqlsrv6')->select(DB::raw("SELECT * FROM [posummary].[dbo].[pro]
	    WHERE status_int = 'Closed'  AND created_fr > '2020-06-30' AND deleted = 'DELETED' "));

	    $z = 0;

	    for ($k=0; $k < count($posummary_closed); $k++) { 

	  		  	$pos_closed = DB::connection('sqlsrv')->select(DB::raw("
	    			SELECT * FROM [preparation].[dbo].[pos] 
	    			WHERE SUBSTRING(order_code, 0,10) = '".$posummary_closed[$i]->pro."' and closed_po = 'Open' "));

	  		  	if (empty($pos_closed[0]->id)) {

	  		  		// Already closed

	  		  	} else {
	  		  		$z = $z + 1;
					
					$da = date("Y-m-d H:i:s");

	  		  		$sql3 = DB::connection('sqlsrv')->update(DB::raw("
						UPDATE [preparation].[dbo].[pos]
						SET 
						closed_po = 'Closed',
						updated_at = '".$da."'
						WHERE SUBSTRING(order_code, 0,10) = '".$posummary_closed[$i]->pro."'

						"));
	  		  	}
	    }


	//
		//dd("created pro: ".$x." , upradetd pro: ".$y);
		 $msg = "Created PO: ".$x."   ,  Updated PO:  ".$y."     , Closed PO:   ".$z. "";
	*/
	    $msg = "Created PO: ".$x."   ,  Updated PO:  ".$y." ";
	    return view('import.importresult', compact('msg'));
	    
		// return redirect('/');
	}

	public function update_po_from_posummary_close() {

	//CLOSED PRO
	    $posummary_closed = DB::connection('sqlsrv6')->select(DB::raw("SELECT * FROM [posummary].[dbo].[pro]
	    WHERE (status_int = 'Closed' OR deleted = 'DELETED') AND created_fr > '2020-06-30' "));

	    $z = 0;

	    for ($k=0; $k < count($posummary_closed); $k++) { 

	  		  	$pos_closed = DB::connection('sqlsrv')->select(DB::raw("
	    			SELECT * FROM [preparation].[dbo].[pos] 
	    			WHERE SUBSTRING(order_code, 0,10) = '".$posummary_closed[$k]->pro."' and closed_po = 'Open' "));

	  		  	if (empty($pos_closed[0]->id)) {

	  		  		// Already closed

	  		  	} else {
	  		  		$z = $z + 1;

	  		  		$da = date("Y-m-d H:i:s");

	  		  		$sql3 = DB::connection('sqlsrv')->update(DB::raw("
						UPDATE [preparation].[dbo].[pos]
						SET 
						closed_po = 'Closed',
						updated_at = '".$da."'
						WHERE SUBSTRING(order_code, 0,10) = '".$posummary_closed[$k]->pro."'

						"));
	  		  	}
	    }
	//
		
		$msg = " Closed PO:   ".$z. "";
		return view('import.importresult', compact('msg'));
	}

	public function postImportLeftoverPos(Request $request) {
	    $getSheetName = Excel::load(Request::file('file3'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            //DB::table('users')->truncate();
	
	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	    		
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file3'))->chunk(5000, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {	

	                	if ($row['place'] == '') {
	                		$place = null;
	                	} else {
	                		$place = $row['place'];
	                	}

						$tablec = new leftover;

						$tablec->material = trim($row['material']);
						$tablec->sku = trim($row['sku']);
						$tablec->price = round($row['price'],2);
						$tablec->location = $row['location'];
						$tablec->place = $place;
						$tablec->qty = (int)$row['qty'];
						$tablec->status = 'ON STOCK';
						$tablec->save();
					}
	            });
	    }
		return redirect('/');
	}

	public function postImportLeftoverNeg(Request $request) {
	    $getSheetName = Excel::load(Request::file('file4'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            //DB::table('users')->truncate();
	
	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	    		
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file4'))->chunk(5000, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);

	                foreach($readerarray as $row)
	                {
	                	
	                	if ($row['place'] == '') {
	                		$place = null;
	                	} else {
	                		$place = $row['place'];
	                	}

						$tablec = new leftover;

						$tablec->material = trim($row['material']);
						$tablec->sku = trim($row['sku']);
						$tablec->price = round($row['price'],2);
						$tablec->location = $row['location'];
						$tablec->place = $place;
						$tablec->qty = (int)$row['qty'] * -1;
						$tablec->status = 'USED';
						$tablec->save();
	                }
	            });
	            
	    }
		return redirect('/');
	}
	
	
}