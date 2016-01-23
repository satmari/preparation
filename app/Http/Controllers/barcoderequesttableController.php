<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

//use Gbrock\Table\Facades\Table;
//use Gbrock\Traits\Sortable;

use App\BarcodeRequest;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

class barcoderequesttableController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		//$user = User::find(Auth::id());
		//if ($user->level() == 1) {
			//$rows = BarcodeRequest::all();
			//$rows = BarcodeStock::sorted()->get();
			//$rows = MainModel::sorted()->get(); 
 			//$table = Table::create($rows); // Generate a Table based on these "rows"
 			//$table = Table::create($rows, ['id','po_id','user_id','ponum','size','qty','module','leader','status','type','comment','created_at','updated_at']);

 		//}
 		//$request_b = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM barcode_requests"));
 		// $request_b = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM barcode_requests
		//	 													WHERE 
		//	 													(CAST(created_at AS DATE) = CAST(GETDATE() AS DATE) AND status != 'error')
		//	 													OR
   		//  													status = 'pending'
   		//														ORDER BY status desc,created_at asc"
		//		    											  ));
		

		$request_b = DB::connection('sqlsrv')->select(DB::raw("SELECT 
br.*, 

(SELECT SUM(barcode_stocks.qty) FROM barcode_stocks WHERE barcode_stocks.po_id = br.po_id) as stock_b,
(SELECT SUM(barcode_requests.qty) FROM barcode_requests WHERE barcode_requests.po_id = br.po_id AND barcode_requests.status != 'error') as request_b,

pos.total_order_qty,
pos.style,
pos.color

FROM barcode_requests as br

JOIN barcode_stocks ON barcode_stocks.po_id = br.po_id
JOIN barcode_requests ON barcode_requests.po_id = br.po_id
JOIN pos ON pos.id = br.po_id

WHERE 
(CAST(br.created_at AS DATE) = CAST(GETDATE() AS DATE) AND br.status != 'error')
OR
br.status = 'pending'

GROUP BY 

br.id,
br.po_id,
br.user_id,
br.ponum,
br.size,
br.module,
br.leader,
br.status,
br.type,
br.comment,
br.qty,
br.created_at,
br.updated_at,
barcode_requests.po_id,
pos.total_order_qty,
pos.style,
pos.color

ORDER BY br.status desc,br.created_at asc
"));

		return view('barcoderequesttable.index', compact('request_b'));
	}

	public function log()
	{
		$request_b = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM barcode_requests ORDER BY created_at desc"));
 		return view('barcoderequesttable.log', compact('request_b'));
	}
	public function logmodule()
	{
		$module = Auth::user()->name;
		$request_b = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM barcode_requests
															   WHERE module = '".$module."'
															   ORDER BY created_at desc
     														"));
 		return view('barcoderequesttable.log', compact('request_b'));
	}

	public function edit($id) {

		$request_b = BarcodeRequest::findOrFail($id);		
		return view('barcoderequesttable.edit', compact('request_b'));
	}
	public function editp($id) {

		$request_b = BarcodeRequest::findOrFail($id);		
		return view('barcoderequesttable.editp', compact('request_b'));
	}
	
	public function update($id, Request $request) {

		$request_b = BarcodeRequest::findOrFail($id);		
		//$request_b->update($request->all());

		$input = $request->all(); 
		//dd($input);

		//$request_b->id = $input['id'];
		$request_b->po_id = $input['po_id'];
		$request_b->user_id = $input['user_id'];
		$request_b->ponum = $input['ponum'];
		$request_b->size = $input['size'];
		
		$qty = $input['qty'];
		$status = $input['status'];

		if (($qty == 0) OR ($qty == NULL)) {

			if ($status == 'error'){
				$request_b->qty = NULL;			
				$request_b->status = 'error';	
			} else if ($status == 'back') {
				$request_b->qty = NULL;
				$request_b->status = 'back';
			} else {
				$request_b->qty = NULL;
				$request_b->status = 'pending';	
			}

		} else {				

			if ($status == 'error'){
				$request_b->qty = $qty;			
				$request_b->status = 'error';
			} else if ($status == 'back') {
				$request_b->qty = $qty;	;
				$request_b->status = 'back';
			} else {
				$request_b->qty = $qty;			
				$request_b->status = 'confirmed';	
			}
		}
	
		$request_b->module = $input['module'];
		
		$request_b->leader = $input['leader'];
		$request_b->type = $input['type'];
		$request_b->comment = $input['comment'];
		$request_b->save();

		//return view('main.index');
		return Redirect::to('/barcoderequesttable');
	}

	public function updatep($id, Request $request) {

		$request_b = BarcodeRequest::findOrFail($id);
		//$request_b->update($request->all());

		$input = $request->all(); 
		//dd($input);

		$request_b->id = $input['id'];
		//$request_b->po_id = $input['po_id'];
		//$request_b->user_id = $input['user_id'];
		//$request_b->ponum = $input['ponum'];
		//$request_b->size = $input['size'];
		
		$qty = $input['qty'];
		$status = $input['status'];

		if (($qty == 0) OR ($qty == NULL)) {

			if ($status == 'error'){
				$request_b->qty = NULL;			
				$request_b->status = 'error';	
			} else if ($status == 'back') {
				$request_b->qty = NULL;
				$request_b->status = 'back';
			} else {
				$request_b->qty = NULL;
				$request_b->status = 'pending';	
			}

		} else {				

			if ($status == 'error'){
				$request_b->qty = $qty;			
				$request_b->status = 'error';
			} else if ($status == 'back') {
				$request_b->qty = $qty;	;
				$request_b->status = 'back';
			} else {
				$request_b->qty = $qty;			
				$request_b->status = 'confirmed';	
			}
		}
	
		//$request_b->module = $input['module'];
		//$request_b->leader = $input['leader'];
		//$request_b->type = $input['type'];
		$request_b->comment = $input['comment'];
		$request_b->save();

		//return view('main.index');
		return Redirect::to('/barcoderequesttable');
	}

	public function error($id, Request $request) {

		$request_b = BarcodeRequest::findOrFail($id);
		//$request_b->update($request->all());

		$input = $request->all(); 
		//dd($input);

		$request_b->id = $input['id'];
		$request_b->qty = NULL;
		$request_b->status = 'error';
		$request_b->save();

		//return view('main.index');
		return Redirect::to('/barcoderequesttable');
	}

}
