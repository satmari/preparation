<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;

use App\BarcodeStock;
use App\Po;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;


class BarcodeStockController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return view('BarcodeStock.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function createnew()
	{
		//
		return view('BarcodeStock.createnew');
	}
	public function createfrommodule()
	{
		//
		return view('BarcodeStock.createfrommodule');
	}
	public function createundo()
	{
		//
		return view('BarcodeStock.createundo');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
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
			return view('BarcodeStock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Komesa and size not exist in Po table';
		    return view('BarcodeStock.error',compact('msg'));
		}
		
		// verify po is closed
		if($po_closed == True) {
			$msg = 'Komesa is Closed';
		    return view('BarcodeStock.error',compact('msg'));
		}

		try {
			$barcode = new BarcodeStock;

			$barcode->po_id = $poid;
			$barcode->user_id = $userId;
			$barcode->ponum = $ponum;
			$barcode->size = $size;
			$barcode->qty = $qty;
			//$barcode->module = $module;
			//$barcode->status = $status;
			$barcode->type = $type;
			$barcode->comment = $comment;
			
			$barcode->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('BarcodeStock.error',compact('msg'));			
		}
		
		return view('BarcodeStock.success');
	}

	public function storefrommodule(Request $request)
	{
		//
		//validation
		$this->validate($request, ['po'=>'required|min:5|max:5','size'=>'required','qty'=>'required','module'=>'min:4|max:10']);
		$forminput = $request->all(); 

		$ponum = $forminput['po'];
		$size = $forminput['size'];
		$qty = $forminput['qty'];
		$module = $forminput['module'];
		$comment = $forminput['comment'];
		$key = $ponum.'-'.$size;
		//dd($key);

		$type = "back";

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		} else {
			$msg = 'User is not autenticated';
			return view('BarcodeStock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Po and size not exist in Po table';
		    return view('BarcodeStock.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == True) {
			$msg = 'Po is Closed';
		    return view('BarcodeStock.error',compact('msg'));
		}

		try {
			$barcode = new BarcodeStock;

			$barcode->po_id = $poid;
			$barcode->user_id = $userId;
			$barcode->ponum = $ponum;
			$barcode->size = $size;
			$barcode->qty = $qty;
			$barcode->module = $module;
			//$barcode->status = $status;
			$barcode->type = $type;
			$barcode->comment = $comment;
			
			$barcode->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('BarcodeStock.error',compact('msg'));			
		}
		
		return view('BarcodeStock.success');
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
			return view('BarcodeStock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Po and size not exist in Po table';
		    return view('BarcodeStock.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == True) {
			$msg = 'Po is Closed';
		    return view('BarcodeStock.error',compact('msg'));
		}

		try {
			$barcode = new BarcodeStock;

			$barcode->po_id = $poid;
			$barcode->user_id = $userId;
			$barcode->ponum = $ponum;
			$barcode->size = $size;
			$barcode->qty = $qty;
			//$barcode->module = $module;
			//$barcode->status = $status;
			$barcode->type = $type;
			$barcode->comment = $comment;
			
			$barcode->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('BarcodeStock.error',compact('msg'));			
		}
		
		return view('BarcodeStock.success');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
