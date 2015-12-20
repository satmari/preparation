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
use App\Module;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Validator;

class RequestController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return view('Request.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		//
		$this->validate($request, ['pin'=>'required|min:4|max:5']);
		$forminput = $request->all(); 

		$pin = $forminput['pin'];
		//dd($pin);

		try {
		    $leader = Module::where('leader_pin', $pin)->firstOrFail();
			//dd($leader->leader);
		} catch (ModelNotFoundException $e) {
		    $msg = 'LineLeader with this PIN not exist';
		    return view('Request.error',compact('msg'));
		}

		return view('Request.create', compact('leader'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request2)
	{
		//
		//validation
		//$this->validate($request2, ['po'=>'required|min:5|max:5','size'=>'required|min:1|max:2','qty'=>'required'/*,'module'=>'min:4|max:10'*/]);
		
		$validator = Validator::make($request2->all(), [
            'po' => 'required|min:5|max:5',
            'size' => 'required|min:1|max:2',
            'qty'=>'required'
        ]);
		if ($validator->fails()) {
            return redirect('/request')
                ->withErrors($validator)
                ->withInput();
        }
		
		$forminput = $request2->all(); 

		$ponum = $forminput['po'];
		$size = $forminput['size'];
		$qty = $forminput['qty'];
		//$module = $forminput['module'];
		$leader = $forminput['leader'];
		$comment = $forminput['comment'];
		$key = $ponum.'-'.$size;

		//$type = "";
		$status = "pending";
		
		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul or User is not autenticated';
			return view('Request.error',compact('msg'));
		}
		
		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} catch (ModelNotFoundException $e) {
		    $msg = 'PO and size not exist in Po table';
		    return view('Request.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == True) {
			$msg = 'Komesa is Closed';
		    return view('Request.error',compact('msg'));
		}

		try {
			$barcode = new BarcodeRequest;

			$barcode->po_id = $poid;
			$barcode->user_id = $userId;
			$barcode->ponum = $ponum;
			$barcode->size = $size;
			$barcode->qty = $qty;
			$barcode->module = $module;
			$barcode->leader = $leader;
			$barcode->status = $status;
			$barcode->type;
			$barcode->comment = $comment;
			
			$barcode->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in database";
			return view('Request.error',compact('msg'));			
		}
		
		return view('Request.success');

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
