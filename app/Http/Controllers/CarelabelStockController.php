<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;

use App\CarelabelStock;
use App\CarelabelRequest;
use App\Po;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;


class CarelabelStockController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return view('CarelabelStock.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function createnew()
	{
		//
		return view('CarelabelStock.createnew');
	}
	public function createfrommodule()
	{
		//
		return view('CarelabelStock.createfrommodule');
	}
	public function createundo()
	{
		//
		return view('CarelabelStock.createundo');
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
			return view('CarelabelStock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Komesa and size not exist in Po table';
		    return view('CarelabelStock.error',compact('msg'));
		}
		
		// verify po is closed
		if($po_closed == 'Closed') {
			$msg = 'Komesa is Closed';
		    return view('CarelabelStock.error',compact('msg'));
		}

		try {
			$carelabel = new CarelabelStock;

			$carelabel->po_id = $poid;
			$carelabel->user_id = $userId;
			$carelabel->ponum = $ponum;
			$carelabel->size = $size;
			$carelabel->qty = $qty;
			//$carelabel->module = $module;
			//$carelabel->status = $status;
			$carelabel->type = $type;
			$carelabel->comment = $comment;
			
			$carelabel->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('CarelabelStock.error',compact('msg'));			
		}
		
		return view('CarelabelStock.success');
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
			return view('CarelabelStock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Po and size not exist in Po table';
		    return view('CarelabelStock.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == 'Closed') {
			$msg = 'Po is Closed';
		    return view('CarelabelStock.error',compact('msg'));
		}

		try {
			$carelabel = new CarelabelRequest;

			$carelabel->po_id = $poid;
			$carelabel->user_id = $userId;
			$carelabel->ponum = $ponum;
			$carelabel->size = $size;
			$carelabel->qty = $qty;
			$carelabel->module = $module;
			$carelabel->leader; // for req
			$carelabel->status = $status;
			$carelabel->type = $type;
			$carelabel->comment = $comment;
			
			$carelabel->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('CarelabelStock.error',compact('msg'));			
		}
		
		return view('CarelabelStock.success');
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
			return view('CarelabelStock.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Po and size not exist in Po table';
		    return view('CarelabelStock.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == 'Closed') {
			$msg = 'Po is Closed';
		    return view('CarelabelStock.error',compact('msg'));
		}

		try {
			$carelabel = new CarelabelStock;

			$carelabel->po_id = $poid;
			$carelabel->user_id = $userId;
			$carelabel->ponum = $ponum;
			$carelabel->size = $size;
			$carelabel->qty = $qty;
			//$carelabel->module = $module;
			//$carelabel->status = $status;
			$carelabel->type = $type;
			$carelabel->comment = $comment;
			
			$carelabel->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('CarelabelStock.error',compact('msg'));			
		}
		
		return view('CarelabelStock.success');
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
