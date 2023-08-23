<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

//use Gbrock\Table\Facades\Table;

use App\BarcodeStock;
use App\BarcodeRequest;
use App\CarelabelStock;
use App\CarelabelRequest;
use App\Po;
use App\throw_away;
use App\leftover;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

class StockController extends Controller {

	public function index()
	{
		//
		return view('Stock.index');
	}
	public function createnew()
	{
		//
		return view('Stock.createnew');
	}
	public function createfrommodule()
	{
		//
		$lines = DB::connection('sqlsrv8')->select(DB::raw("SELECT  [id]
		      ,[location]
		      ,[location_type]
		      ,[location_dest]
		  FROM [bbStock].[dbo].[locations]"));
		// dd($lines);

		return view('Stock.createfrommodule', compact('lines'));
	}
	public function createundo()
	{
		//
		return view('Stock.createundo');
	}
	public function createtransfer()
	{
		//
		return view('Stock.createtransfer');
	}
	public function createthrow_away()
	{
		//
		$materials = DB::connection('sqlsrv7')->select(DB::raw("SELECT DISTINCT [material]
		  FROM [trebovanje].[dbo].[sap_coois_all] 
		  WHERE [material] like 'A%' and wc = 'WC01'"));

		// dd($materials);

		return view('Stock.throw_away',compact('materials'));
	}
	public function createleftover()
	{
		//
		$materials = DB::connection('sqlsrv7')->select(DB::raw("SELECT DISTINCT [material]
		  FROM [trebovanje].[dbo].[sap_coois_all] 
		  WHERE (([material] like 'A%') OR ([material] like 'ES%') OR ([material] like 'ET%') OR ([material] like 'KAFU%')) and (wc = 'WC01' OR wc = 'WC04') "));

		$skus = DB::connection('sqlsrv6')->select(DB::raw("SELECT DISTINCT sku
		  FROM [posummary].[dbo].[pro] ORDER BY sku asc"));
		
		return view('Stock.createleftover',compact('materials','skus'));
	}

	public function storenew(Request $request)
	{
		//
		//validation
		$this->validate($request, ['po'=>'required|min:6|max:6','size'=>'required','qty'=>'required']);
		$forminput = $request->all(); 
		// dd($forminput);

		$ponum = $forminput['po'];
		$size = $forminput['size'];
		$qty = $forminput['qty'];
		$comment = $forminput['comment'];
		$key = $ponum.'-'.$size;
		//dd($key);

		$type = "new";

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		} else {
			$msg = 'User is not autenticated';
			return view('Stock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Komesa and size not exist in Po table';
		    return view('Stock.error',compact('msg'));
		}
		
		// verify po is closed
		if($po_closed == 'Closed') {
			$msg = 'Komesa is Closed';
		    return view('Stock.error',compact('msg'));
		}

		if (isset($forminput['barcode'])) {
			$barcode = $forminput['barcode'];	
		} else {
			$barcode = '0';
		}
		if (isset($forminput['carelabel'])) {
			$carelabel = $forminput['carelabel'];
		} else {
			$carelabel = '0';
		}

		if ($barcode != '0') {

			if (isset($forminput['machine'])) {
				$machine = $forminput['machine'];
			} else {
				// $machine = NULL;
				$msg = 'Machine not set for barcode labels';
		    	return view('Stock.error',compact('msg'));
			}
		} else {
			$machine = NULL;
		}
		

		$msg = "";

		if ($barcode == '1') {

			try {
				$tableb = new BarcodeStock;

				$tableb->po_id = $poid;
				$tableb->user_id = $userId;
				$tableb->ponum = $ponum;
				$tableb->size = $size;
				$tableb->qty = $qty;
				//$tableb->module = $module;
				//$tableb->status = $status;
				$tableb->type = $type;
				$tableb->comment = $comment;
				$tableb->machine = $machine;
				
				$tableb->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in barcode database";
				return view('Stock.error',compact('msg'));			
			}
			$msg = '<p style="color:green;">Barcode request successfully saved</p>';
		}

		if ($carelabel == '1') {

			try {
				$tablec = new CarelabelStock;

				$tablec->po_id = $poid;
				$tablec->user_id = $userId;
				$tablec->ponum = $ponum;
				$tablec->size = $size;
				$tablec->qty = $qty;
				//$tablec->module = $module;
				//$tablec->status = $status;
				$tablec->type = $type;
				$tablec->comment = $comment;
				
				$tablec->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save carelabel in database";
				return view('Stock.error',compact('msg'));			
			}
			$msg = '<p style="color:green;">Carelabel request successfully saved</p>';

		}

		if ($msg == "") {
			$msg = '<p style="color:red;"><big>BARCODE OR CARELABEL NOT SELECTED !!!</big></p>';
			return view('Stock.error',compact('msg'));
		}
		return view('Stock.success');

	}

	public function storefrommodule(Request $request)
	{
		//
		//validation
		$this->validate($request, ['po'=>'required|min:6|max:6','size'=>'required','qty'=>'required','module'=>'required']);
		$forminput = $request->all(); 

		$ponum = $forminput['po'];
		$size = $forminput['size'];
		$qty = $forminput['qty'];
		$module = $forminput['module'];
		$comment = $forminput['comment'];
		$key = $ponum.'-'.$size;
		//dd($key);

		$qty = $qty * (-1);
		
		//$type = "back";
		$type = "modul";
		$status = "back";

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		} else {
			$msg = 'User is not autenticated';
			return view('Stock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Po and size not exist in Po table';
		    return view('Stock.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == 'Closed') {
			$msg = 'Po is Closed';
		    return view('Stock.error',compact('msg'));
		}

		if (isset($forminput['barcode'])) {
			$barcode = $forminput['barcode'];	
		} else {
			$barcode = '0';
		}
		if (isset($forminput['carelabel'])) {
			$carelabel = $forminput['carelabel'];
		} else {
			$carelabel = '0';
		}

		$msg = "";

		if ($barcode == '1') {

			try {
				//$tableb = new BarcodeStock;
				$tableb = new BarcodeRequest;

				$tableb->po_id = $poid;
				$tableb->user_id = $userId;
				$tableb->ponum = $ponum;
				$tableb->size = $size;
				$tableb->qty = $qty;
				$tableb->module = $module;
				$tableb->leader; // for req
				$tableb->status = $status;
				$tableb->type = $type;
				$tableb->comment = $comment;
				
				$tableb->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save barcode in database";
				return view('Stock.error',compact('msg'));			
			}
			$msg = '<p style="color:green;">Barcode request successfully saved</p>';
		}

		if ($carelabel == '1') {

			try {
				$tablec = new CarelabelRequest;

				$tablec->po_id = $poid;
				$tablec->user_id = $userId;
				$tablec->ponum = $ponum;
				$tablec->size = $size;
				$tablec->qty = $qty;
				$tablec->module = $module;
				$tablec->leader; // for req
				$tablec->status = $status;
				$tablec->type = $type;
				$tablec->comment = $comment;
				
				$tablec->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save carelabel in database";
				return view('Stock.error',compact('msg'));			
			}
			$msg = '<p style="color:green;">Carelabel request successfully saved</p>';

		}

		if ($msg == "") {
			$msg = '<p style="color:red;"><big>BARCODE OR CARELABEL NOT SELECTED !!!</big></p>';
			return view('Stock.error',compact('msg'));
		}
		
		return view('Stock.success');
	}

	public function storeundo(Request $request)
	{
		//
		//validation
		$this->validate($request, ['po'=>'required|min:6|max:6','size'=>'required','qty'=>'required']);
		$forminput = $request->all(); 

		$ponum = $forminput['po'];
		$size = $forminput['size'];
		$qty = $forminput['qty'];
		$comment = $forminput['comment'];
		$key = $ponum.'-'.$size;
		//dd($key);

		$type = "undo";
		$qty = $qty * (-1);

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		} else {
			$msg = 'User is not autenticated';
			return view('Stock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Po and size not exist in Po table';
		    return view('Stock.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == 'Closed') {
			$msg = 'Po is Closed';
		    return view('Stock.error',compact('msg'));
		}

		if (isset($forminput['barcode'])) {
			$barcode = $forminput['barcode'];	
		} else {
			$barcode = '0';
		}
		if (isset($forminput['carelabel'])) {
			$carelabel = $forminput['carelabel'];
		} else {
			$carelabel = '0';
		}

		$msg = "";

		if ($barcode == '1') {

			try {
				$tableb = new BarcodeStock;

				$tableb->po_id = $poid;
				$tableb->user_id = $userId;
				$tableb->ponum = $ponum;
				$tableb->size = $size;
				$tableb->qty = $qty;
				//$tableb->module = $module;
				//$tableb->status = $status;
				$tableb->type = $type;
				$tableb->comment = $comment;
				
				$tableb->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save barcode in database";
				return view('Stock.error',compact('msg'));			
			}
			$msg = '<p style="color:green;">Barcode stock successfully saved</p>';
		}

		if ($carelabel == '1') {

			try {
				$tablec = new CarelabelStock;

				$tablec->po_id = $poid;
				$tablec->user_id = $userId;
				$tablec->ponum = $ponum;
				$tablec->size = $size;
				$tablec->qty = $qty;
				//$tablec->module = $module;
				//$tablec->status = $status;
				$tablec->type = $type;
				$tablec->comment = $comment;
				
				$tablec->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save caerlabel in database";
				return view('Stock.error',compact('msg'));			
			}
			$msg = '<p style="color:green;">Carelabel stock successfully saved</p>';
		}

		if ($msg == "") {
			$msg = '<p style="color:red;"><big>BARCODE OR CARELABEL NOT SELECTED !!!</big></p>';
			return view('Stock.error',compact('msg'));
		}
		
		return view('Stock.success');
	}

	public function stockstoretransfer(Request $request) {

		//
		//validation
		$this->validate($request, ['po'=>'required|min:6|max:6','size'=>'required','qty'=>'required']);
		$forminput = $request->all(); 

		$ponum = $forminput['po'];
		$size = $forminput['size'];
		$qty = $forminput['qty'];
		$comment = $forminput['comment'];
		$key = $ponum.'-'.$size;
		//dd($key);

		$type = "transfer";
		$qty = $qty * (-1);

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		} else {
			$msg = 'User is not autenticated';
			return view('Stock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Po and size not exist in Po table';
		    return view('Stock.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == 'Closed') {
			$msg = 'Po is Closed';
		    return view('Stock.error',compact('msg'));
		}

		if (isset($forminput['barcode'])) {
			$barcode = $forminput['barcode'];	
		} else {
			$barcode = '0';
		}
		if (isset($forminput['carelabel'])) {
			$carelabel = $forminput['carelabel'];
		} else {
			$carelabel = '0';
		}

		$msg = "";

		if ($barcode == '1') {

			try {
				$tableb = new BarcodeStock;

				$tableb->po_id = $poid;
				$tableb->user_id = $userId;
				$tableb->ponum = $ponum;
				$tableb->size = $size;
				$tableb->qty = $qty;
				//$tableb->module = $module;
				//$tableb->status = $status;
				$tableb->type = $type;
				$tableb->comment = $comment;
				
				// $tableb->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save barcode in database";
				return view('Stock.error',compact('msg'));			
			}
			$msg = '<p style="color:green;">Barcode stock successfully saved</p>';
		}

		if ($carelabel == '1') {

			try {
				$tablec = new CarelabelStock;

				$tablec->po_id = $poid;
				$tablec->user_id = $userId;
				$tablec->ponum = $ponum;
				$tablec->size = $size;
				$tablec->qty = $qty;
				//$tablec->module = $module;
				//$tablec->status = $status;
				$tablec->type = $type;
				$tablec->comment = $comment;
				
				// $tablec->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save caerlabel in database";
				return view('Stock.error',compact('msg'));			
			}
			$msg = '<p style="color:green;">Carelabel stock successfully saved</p>';
		}

		if ($msg == "") {
			$msg = '<p style="color:red;"><big>BARCODE OR CARELABEL NOT SELECTED !!!</big></p>';
			return view('Stock.error',compact('msg'));
		}
		
		return view('Stock.success');
	}

	public function stockthow_away(Request $request) {

		//
		$this->validate($request, ['material'=>'required','type'=>'required','qty'=>'required']);
		$forminput = $request->all(); 
		// dd($forminput);

		$material = $forminput['material'];
		$type = $forminput['type'];
		$qty = (int)$forminput['qty'];

		try {
			$tablec = new throw_away;

			$tablec->material = $material;
			$tablec->type = $type;
			$tablec->qty = $qty;
			$tablec->save();

		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('Stock.error',compact('msg'));			
		}

		return view('Stock.success');
	}

	public function stockstoreleftover(Request $request) {

		//
		$this->validate($request, ['material'=>'required','sku'=>'required','price'=>'required', 'qty'=>'required' , 'location'=>'required']);
		$forminput = $request->all(); 
		// dd($forminput);

		$material = trim($forminput['material']);
		$sku = trim($forminput['sku']);
		$price = (float)$forminput['price'];
		$qty = (int)$forminput['qty'];
		$status = $forminput['status'];
		$location = $forminput['location'];
		$place = $forminput['place'];

		if ($place == '') {
    		$place = null;
    	} 		

		if ($status == "USED") {
			$qty = $qty * (-1);

			$exist_sum = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM(qty) as qty FROM leftovers WHERE sku = '".$sku."' AND material = '".$material."' AND price = '".$price."' GROUP BY material, price, sku"));
			// dd((int)$exist_sum[0]->qty);

			if(isset($exist_sum[0])) {
				if ((int)$exist_sum[0]->qty > 0) {
					
				} else {
					dd("Nemate dovoljnu kolicinu na stanju da skinete ".$qty." komada");
				}
			} else {
				dd("Kombinacija sku, material i cena ne postoji u tabeli");
			}
		}

		try {
			$tablec = new leftover;

			$tablec->material = trim($material);
			$tablec->sku = trim($sku);
			$tablec->price = round($price,2);
			$tablec->qty = $qty;
			$tablec->location = $location;
			$tablec->place = $place;
			$tablec->status = $status;
			$tablec->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('Stock.error',compact('msg'));			
		}

		return view('Stock.index');
	}

	public function leftover() {

		$leftovers = DB::connection('sqlsrv')->select(DB::raw("SELECT
		 material, price, sku, location, place, SUM(qty) as qty 
		 FROM leftovers 
		 GROUP BY material, price, sku, location, place
		 HAVING SUM(qty) != 0"));
		// dd($leftovers);

		return view('Stock.leftover_table',compact('leftovers'));
	}
	public function leftover_full() {

		$leftovers = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM leftovers"));
		// dd($leftovers);

		return view('Stock.leftover_table_full',compact('leftovers'));
	}

	// public function use_leftover(Request $request) {

	// 	//
	// 	$this->validate($request, ['id'=>'required']);
	// 	$forminput = $request->all(); 
	// 	// dd($forminput);

	// 	$id = $forminput['id'];

	// 	$request = leftover::findOrFail($id);
	// 	$request->status = "USED" ;
	// 	$request->save();

	// 	return view('Stock.index');
	// }



}
