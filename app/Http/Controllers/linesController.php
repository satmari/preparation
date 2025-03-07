<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

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

class linesController extends Controller {

	public function index()	{
		//
		// dd('lines');

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul or User is not autenticated';
			return view('Lines.error',compact('msg'));
		}

		$msgs = session('msgs');
    	
    	// Delete the session variable
    	session()->forget('msgs');

		return view('Lines.index', compact('msgs','module'));
	}

	public function leadercheck(Request $request) {
		//
		$this->validate($request, ['pin'=>'required|min:4|max:5']);
		$forminput = $request->all(); 
		// dd($forminput);

		$pin = $forminput['pin'];
		$module = $forminput['module'];

		$inteosleaders = DB::connection('sqlsrv2')->select(DB::raw("SELECT Name FROM [WEA_PersData] 
			WHERE (Func = 23) and (FlgAct = 1) and (PinCode = ".$pin.") "));
		// dd($inteosleaders);

		if (!isset($inteosleaders[0]->Name)) {
			$msg = 'LineLeader with this PIN not exist';
		    return view('Lines.error',compact('msg'));

		
		} else {
				
			$leader = $inteosleaders[0]->Name;
    		Session::set('leader', $leader);	
    		
    		return view('Lines.select', compact('leader','module'));
    	} 
    }

    public function lines_requestcreate($leader, $module) {
    	// dd($l);
    	if ($leader == '') {
    		return view('Lines.index');
    	}
    	if ($module == '') {
    		return view('Lines.index');
    	}
    	return view('Lines.create', compact('leader','module'));
    }

    public function lines_requeststore(Request $request) {
			
		$forminput = $request->all(); 
		//dd($forminput);

		$ponum = $forminput['po'];
		$leader = $forminput['leader'];
		$module = $forminput['module'];
		$comment = $forminput['comment'];

		//$size = $forminput['size'];
		//$qty = $forminput['qty'];
		//$module = $forminput['module'];
		//$key = $ponum.'-'.$size;

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
		if ($module == '') {
			// dd($module);
		    $module = Auth::user()->name;

		    if ($module == '') {

		    	$msg = 'Modul is not autenticated, please login';
				return view('Lines.error',compact('msg'));
		    }
		} 

		$userid_find = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM users
			WHERE  name = '".$module."' "));
		$userId = $userid_find[0]->id;
		
		// verify po_id
		try {
		    $poid = Po::where('po_new', $ponum)->firstOrFail()->id;
		    $po_closed = Po::where('po_new', $ponum)->firstOrFail()->closed_po;
		    $size = Po::where('po_new', $ponum)->firstOrFail()->size;

		} catch (ModelNotFoundException $e) {
		    $msg = 'PO / Komesa not exist in Po table, please start writing 3 numbers and then select PO from dropdown.';
		    return view('Lines.error',compact('msg'));
		}

		// verify po is closed
		if($po_closed == "Closed") {
			$msg = 'Komesa is Closed';
		    return view('Lines.error',compact('msg'));
		}
		$msg = "";

		if ($barcode == '1') {

			// try {
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
			// }
			// catch (\Illuminate\Database\QueryException $e) {
			// 	$msg = "Problem to save in barcode request table";
			// 	return view('Lines.error',compact('msg'));			
			// }
			//return view('Lines.success');
			// $msg = '<p style="color:green;">Barcode request successfully saved</p>';
		}

		if ($carelabel == '1') {
			
			// try {
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
			// }
			// catch (\Illuminate\Database\QueryException $e) {
			// 	$msg = "Problem to save in carelabel request table";
			// 	return view('Lines.error',compact('msg'));			
			// }
			//return view('Lines.success');
		
			// $msgs = 'Carelabel request successfully saved';
		}


		$msgs = 'Request successfully saved';

		Session::set('msgs', $msgs);	
		// return view('Lines.index', compact('msgs'));
		return Redirect::to('/');
	}

	

}
