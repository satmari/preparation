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
		return view('Stock.createfrommodule');
	}
	public function createundo()
	{
		//
		return view('Stock.createundo');
	}

	public function storenew(Request $request)
	{
		//
		//validation
		$this->validate($request, ['po'=>'required|min:5|max:5','size'=>'required','qty'=>'required']);
		$forminput = $request->all(); 

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
		$this->validate($request, ['po'=>'required|min:5|max:5','size'=>'required','qty'=>'required','module'=>'max:8']);
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
		$this->validate($request, ['po'=>'required|min:5|max:5','size'=>'required','qty'=>'required']);
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


}
