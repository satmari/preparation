<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Maatwebsite\Excel\Facades\Excel;

use App\CarelabelStock;
use App\BarcodeStock;
use Request;
use App\Po;
use App\User;
use DB;

class importController extends Controller {

	public function index()
	{
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
	                	// dd($delivery_date);
						
	                	$hangtag = $row['hangtag'];

	                	$po = substr($order_code, 9, 5);
	                	$size = substr($order_code, 23, 3);
						
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
							$brand = "";
						}

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
						$porder->save();

						$barcode = new BarcodeStock;
						$barcode->po_id = $porder->id; // ???
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
						$carelabel->user_id = 3;
						$carelabel->ponum = $po;
						$carelabel->size = $size;
						$carelabel->qty = 0;
						$carelabel->module;
						$carelabel->status;
						$carelabel->type = "insert";;
						$carelabel->comment;
						$carelabel->save();

	                }
	            });
	       
	    }
		
	    //Session::flash('file_uploaded_successfully', 'File has been uploaded successfully and has also updated the database.');
	    return redirect('/');
	    //return view('import.importresult', compact('reader'));
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
	                	/*
						$userbulk = new User;
						$userbulk->name = $row['user'];;
						$userbulk->email = $row['email'];
						$userbulk->password = bcrypt($row['pass']);
						//$userbulk->created_at = date(2015-12-22);
						//$userbulk->updated_at = date(2015-12-22);
												
						$userbulk->save();
						*/
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
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file4'))->chunk(50, function ($reader)
	            
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
						//$userbulk->created_at = date(2015-12-22);
						//$userbulk->updated_at = date(2015-12-22);
												
						$userbulk->save();
						*/
	                }
	            });
	    }
		return redirect('/');
	}
	
}