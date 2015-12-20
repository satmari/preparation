<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;

use Request;
use App\Po;
use DB;

class importController extends Controller {

	public function index()
	{
		//
		return view('import.index');
	}
	
	public function postImportPo(Request $request)
	{
	 
	    $getSheetName = Excel::load(Request::file('file'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        //if ($sheetName === 'Product-General-Table')  {
	    	//selectSheetsByIndex(0)
	           	//DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	            DB::table('pos')->truncate();
	
	            //Excel::selectSheets($sheetName)->load($request->file('file'), function ($reader)
	            //Excel::selectSheets($sheetName)->load(Input::file('file'), function ($reader)
	            //Excel::filter('chunk')->selectSheetsByIndex(0)->load(Request::file('file'))->chunk(50, function ($reader)
	            Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file'))->chunk(50, function ($reader)
	            
	            {
	                foreach($reader->toArray() as $sheet)
	                {
	                    //MainModel::create($sheet);
	                    //$sheet->dump();

	                }

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

	                	$po = substr($order_code, 9, 5); // 
	                	$size = substr($order_code, 23, 3);
						
						$style = substr($product, 0, 8);
						$color = substr($product, 9, 4);

						$po_key = $po ."-".$size;

						if ($flash == 'F') {
							$flash = True;
						} else {
							$flash = False;
						}

						$closed = False;

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
						$porder->brand;
						$porder->status;
						$porder->type;
						$porder->comment;

						$porder->save();

	                }
	                
	                //po_key	po	order_code	size	style	color	color_desc	season	total_order_qty	flash	closed_po	status	comment


				    // Loop through all sheets
					// $reader->each(function($sheet) {
					
					//     // Loop through all rows
					//     $sheet->each(function($rows) {

					//     	// Loop through all cells
					// 		//$rows->each(function($row) {

					// 			$order_code = $rows['order_code'];
					// 			$po = substr($order_code, 4,5);
					// 			//$rest = substr("abcdef", -3, 1); // returns "d" 16IC5800050656::00102::M


								
					// 			$porder = new PoModel;
					// 			$porder->po_key = $po;
					// 			$porder->order_code = $order_code;
					// 			$porder->save();

					// 		//});
					//     });
					// });

				    //$reader->get(array('po_key', 'po'));
					

	            });

	         
	
	            //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	            //var_dump('product general done');
	        //}
	
	        // if ($sheetName === 'Product-Meta-Table')  {
	        //     // dd('loading meta');
	        //     DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	        //     DB::table('product_metas')->truncate();
	        //     Excel::selectSheets($sheetName)->load($request->file('productsFile'), function ($reader)
	        //     {    
	        //         foreach($reader->toArray() as $sheet)
	        //         {
	        //             ProductMeta::create($sheet);
	        //         }
	        //     });
	        //     DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	        //     //var_dump('product meta done');
	        // }
	    }
		
	    //Session::flash('file_uploaded_successfully', 'File has been uploaded successfully and has also updated the database.');
	    return redirect('/datatables');
	    //return view('import.importresult', compact('reader'));

	}

	public function import(Request $request)
	{

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{

		//
		return view('import.importresult');
		
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}

