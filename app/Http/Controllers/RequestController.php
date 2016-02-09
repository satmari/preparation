<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;

use App\BarcodeRequest;
use App\CarelabelRequest;
use App\SecondQRequest;
use App\Po;
use App\Module;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
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
	public function check(Request $request)
	{
		//
		$this->validate($request, ['pin'=>'required|min:4|max:5']);
		$forminput = $request->all(); 

		$pin = $forminput['pin'];
		//dd($pin);

		/*
		try {
		    $leader = Module::where('leader_pin', $pin)->firstOrFail();
			//dd($leader->leader);
		} catch (ModelNotFoundException $e) {
		    $msg = 'LineLeader with this PIN not exist';
		    return view('Request.error',compact('msg'));
		}
		*/
		$inteosleaders = DB::connection('sqlsrv2')->select(DB::raw("SELECT Name FROM BdkCLZG.dbo.WEA_PersData WHERE (Func = 23) and (FlgAct = 1) and (PinCode = ".$pin.")"));

		if (empty($inteosleaders)) {
			$msg = 'LineLeader with this PIN not exist';
		    return view('Request.error',compact('msg'));
		
		} else {
			foreach ($inteosleaders as $row) {
    			$leader = $row->Name;
    			Session::set('leader', $leader);		
    		}
    		//dd($leader);
    		return view('Request.select', compact('leader'));
    	} 
	}

	public function create(Request $request)
	{
		$leader = Session::get('leader');
		//dd($leader);
		return view('Request.create', compact('leader'));
	}

	public function createsec(Request $request)
	{
		$leader = Session::get('leader');
		//dd($leader);
		return view('Request.createsec', compact('leader'));
	}

	public function createp(Request $request)
	{
		return view('Request.createp');
	}

	public function store(Request $request2)
	{
		//
		//validation
		//$this->validate($request2, ['po'=>'required|min:5|max:5','size'=>'required|min:1|max:2','qty'=>'required'/*,'module'=>'min:4|max:10'*/]);
		
		$validator = Validator::make($request2->all(), [
            'po' => 'required|min:5|max:5',
            'size' => 'required|min:1|max:3',
            //'qty' => 'required',
            'leader' => 'required'
        ]);
		if ($validator->fails()) {
            return redirect('/request')
                ->withErrors($validator)
                ->withInput();
        }
		
		$forminput = $request2->all(); 
		//dd($forminput);

		$ponum = $forminput['po'];
		$size = $forminput['size'];
		//$qty = $forminput['qty'];
		//$module = $forminput['module'];
		$leader = $forminput['leader'];
		$comment = $forminput['comment'];
		$key = $ponum.'-'.$size;

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

		//dd("B: ".$barcode." C: ".$carelabel);

		$type = "modul";
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
		if($po_closed == "Closed") {
			$msg = 'Komesa is Closed';
		    return view('Request.error',compact('msg'));
		}

		$msg = "";

		if ($barcode == '1') {

			try {
				$barcode = new BarcodeRequest;

				$barcode->po_id = $poid;
				$barcode->user_id = $userId;
				$barcode->ponum = $ponum;
				$barcode->size = $size;
				$barcode->qty ;		//= $qty;
				$barcode->module = $module;
				$barcode->leader = $leader;
				$barcode->status = $status;
				$barcode->type = $type;
				$barcode->comment = $comment;
			
				$barcode->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in barcode request table";
				return view('Request.error',compact('msg'));			
			}
		//return view('Request.success');
		$msg = '<p style="color:green;">Barcode request successfully saved</p>';
		}

		if ($carelabel == '1') {
			
			try {
				$carelabel = new CarelabelRequest;

				$carelabel->po_id = $poid;
				$carelabel->user_id = $userId;
				$carelabel->ponum = $ponum;
				$carelabel->size = $size;
				$carelabel->qty ;	//= $qty;
				$carelabel->module = $module;
				$carelabel->leader = $leader;
				$carelabel->status = $status;
				$carelabel->type = $type;
				$carelabel->comment = $comment;
			
				$carelabel->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in carelabel request table";
				return view('Request.error',compact('msg'));			
			}
		//return view('Request.success');
		
		$msg = $msg. '<p style="color:green;">Carelabel request successfully saved</p>';
		}

		if ($msg == "") {
			$msg = '<p style="color:red;"><big>BARCODE AND CARELABEL NOT SELECTED !!!</big></p>';
		}

		if(time() < mktime(08, 30, 0)) {
		    $del = "Delivery at 9:00";
		} else if (time() < mktime(11, 30, 0)) {
			$del = "Delivery at 12:00";
		} else 	{
			$del = "Delivery tomorow at 07:00";
		}

		//return view('Request.index');
		return view('Request.success', compact('msg','del'));
	}

	public function storep(Request $request2)
	{
		//
		//validation
		//$this->validate($request2, ['po'=>'required|min:5|max:5','size'=>'required|min:1|max:2','qty'=>'required'/*,'module'=>'min:4|max:10'*/]);
		
		$validator = Validator::make($request2->all(), [
            'po' => 'required|min:5|max:5',
            'size' => 'required|min:1|max:3',
            //'qty' => 'required',
            'module' => 'required|min:4|max:5',
            'leader' => 'required'
        ]);
		if ($validator->fails()) {
            return redirect('/request')
                ->withErrors($validator)
                ->withInput();
        }
		
		$forminput = $request2->all(); 
		//dd($forminput);

		$ponum = $forminput['po'];
		$size = $forminput['size'];
		$qty = $forminput['qty'];
		$module = ucfirst($forminput['module']);
		$leader = $forminput['leader'];
		$comment = $forminput['comment'];
		$key = $ponum.'-'.$size;

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

		$type = "preparation";

		if (($qty <= 0) AND ($qty == NULL)) {
			$qty = NULL;
			$status = "pending";
		} else {
			$status = "confirmed";
		}
		
		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    //$module = Auth::user()->name;
		} else {
			$msg = 'Modul or User is not autenticated';
			return view('Request.errorp',compact('msg'));
		}
		

		// verify po_id
		try {
		    $poid = Po::where('po_key', $key)->firstOrFail()->id;
		} catch (ModelNotFoundException $e) {
		    $msg = 'PO and size not exist in Po table';
		    return view('Request.errorp',compact('msg'));
		}

		// verify po is closed
		$po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		if($po_closed == True) {
			$msg = 'Komesa is Closed';
		    return view('Request.errorp',compact('msg'));
		}

		$msg = "";

		if ($barcode == '1') {

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
				$barcode->type = $type;
				$barcode->comment = $comment;
			
				$barcode->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in barcode request table";
				return view('Request.errorp',compact('msg'));			
			}
		//return view('Request.success');
		$msg = '<p style="color:green;">Barcode request successfully saved</p>';
		}

		if ($carelabel == '1') {
			
			try {
				$carelabel = new CarelabelRequest;

				$carelabel->po_id = $poid;
				$carelabel->user_id = $userId;
				$carelabel->ponum = $ponum;
				$carelabel->size = $size;
				$carelabel->qty = $qty;
				$carelabel->module = $module;
				$carelabel->leader = $leader;
				$carelabel->status = $status;
				$carelabel->type = $type;
				$carelabel->comment = $comment;
			
				$carelabel->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in carelabel request table";
				return view('Request.errorp',compact('msg'));			
			}
		//return view('Request.success');
		
		$msg = $msg. '<p style="color:green;">Carelabel request successfully saved</p>';
		}

		if ($msg == "") {
			$msg = '<p style="color:red;"><big>BARCODE AND CARELABEL NOT SELECTED !!!</big></p>';
			return view('Request.errorp',compact('msg'));
		}

		if(time() < mktime(08, 30, 0)) {
		    $del = "Delivery at 9:00";
		} else if (time() < mktime(11, 30, 0)) {
			$del = "Delivery at 12:00";
		} else 	{
			$del = "Delivery tomorow at 07:00";
		}

		return view('Request.success', compact('msg','del'));
	}

	public function storesec(Request $request2)
	{
		//
		//validation
		//$this->validate($request2, ['po'=>'required|min:5|max:5','size'=>'required|min:1|max:2','qty'=>'required'/*,'module'=>'min:4|max:10'*/]);
		
		$validator = Validator::make($request2->all(), [
            'po' => 'required|min:5|max:5',
            'size' => 'required|min:1|max:3',
            'qty' => 'required',
            'leader' => 'required'
        ]);

		if ($validator->fails()) {
            return redirect('/request')
                ->withErrors($validator)
                ->withInput();
        }
		
		$forminput = $request2->all(); 
		//dd($forminput);

		$ponum = $forminput['po'];
		$size = $forminput['size'];
		$qty = $forminput['qty'];
		//$module = $forminput['module'];
		$leader = $forminput['leader'];
		$comment = $forminput['comment'];
		$key = $ponum.'-'.$size;

		//dd("B: ".$barcode." C: ".$carelabel);

		$type = "modul";
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
			$po = DB::connection('sqlsrv')->select(DB::raw("SELECT id, style, color, closed_po FROM pos WHERE po_key ='".$key."'"));

			//dd($po);

		    $poid = $po['0']->id;
		    $style = $po['0']->style;
		    $color = $po['0']->color;
		    $po_closed = $po['0']->closed_po;

		    // $poid = Po::where('po_key', $key)->firstOrFail()->id;
		    // $po_closed = Po::where('po_key', $key)->firstOrFail()->closed_po;
		} 
		catch (ModelNotFoundException $e) {
		    $msg = 'PO and size not exist in Po table';
		    return view('Request.error',compact('msg'));
		}
		catch (\Exception $e) {
		    $msg = 'PO and size not exist in Po table';
		    return view('Request.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == "Closed") {
			$msg = 'Komesa is Closed';
		    return view('Request.error',compact('msg'));
		}

		$msg = "";

			try {
				$squality = new SecondQRequest;

				$squality->po_id = $poid;
				$squality->user_id = $userId;
				$squality->ponum = $ponum;
				$squality->size = $size;
				$squality->qty = $qty;
				$squality->module = $module;
				$squality->leader = $leader;
				$squality->status = $status;
				$squality->type = $type;
				$squality->comment = $comment;
				$squality->style = $style;
				$squality->color = $color;
			
				$squality->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in request table for II quality";
				return view('Request.error',compact('msg'));			
			}
		//return view('Request.success');
		$msg = '<p style="color:green;">II quality request successfully saved</p>';
		
		if(time() < mktime(08, 30, 0)) {
		    $del = "Delivery at 9:00";
		} else if (time() < mktime(11, 30, 0)) {
			$del = "Delivery at 12:00";
		} else 	{
			$del = "Delivery tomorow at 07:00";
		}

		return view('Request.success', compact('msg','del'));
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
