<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;

use App\BarcodeRequest;
use App\Po;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

class BarcodeRequestController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return view('BarcodeRequest.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		return view('BarcodeRequest.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
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

		//$type = "";
		$status = "panding";

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		} else {
			$msg = 'User is not autenticated';
			return view('BarcodeRequest.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'Po and size not exist in Po table';
		    return view('BarcodeRequest.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == True) {
			$msg = 'Po is Closed';
		    return view('BarcodeRequest.error',compact('msg'));
		}

		try {
			$barcode = new BarcodeRequest;

			$barcode->po_id = $poid;
			$barcode->user_id = $userId;
			$barcode->ponum = $ponum;
			$barcode->size = $size;
			$barcode->qty = $qty;
			$barcode->module = $module;
			$barcode->leader;
			$barcode->status = $status;
			//$barcode->type = $type;
			$barcode->comment = $comment;
			
			$barcode->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('BarcodeRequest.error',compact('msg'));			
		}
		
		return view('BarcodeRequest.success');
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
