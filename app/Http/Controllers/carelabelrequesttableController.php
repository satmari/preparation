<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

//use Gbrock\Table\Facades\Table;
//use Gbrock\Traits\Sortable;

use App\CarelabelRequest;
use App\print_request_label;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

class carelabelrequesttableController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		//$user = User::find(Auth::id());
		//if ($user->level() == 1) {
			//$rows = CarelabelRequest::all();
			//$rows = BarcodeStock::sorted()->get();
			//$rows = MainModel::sorted()->get(); 
 			//$table = Table::create($rows); // Generate a Table based on these "rows"
 			//$table = Table::create($rows, ['id','po_id','user_id','ponum','size','qty','module','leader','status','type','comment','created_at','updated_at']);

 		//}
 		//$request_c = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM carelabel_requests"));
 		// $request_c = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM carelabel_requests
			// 													WHERE 
			// 													(CAST(created_at AS DATE) = CAST(GETDATE() AS DATE) AND status != 'error')
			// 													OR
   			//													status = 'pending'
   			//													ORDER BY status desc,created_at asc"
   			//												  ));


 		$request_c = DB::connection('sqlsrv')->select(DB::raw("SELECT 
cr.*, 

(SELECT SUM(carelabel_stocks.qty) FROM carelabel_stocks WHERE carelabel_stocks.po_id = cr.po_id) as stock_c,
(SELECT SUM(carelabel_requests.qty) FROM carelabel_requests WHERE carelabel_requests.po_id = cr.po_id AND carelabel_requests.status != 'error') as request_c,

pos.total_order_qty,
pos.style,
pos.color,
pos.po_new

FROM carelabel_requests as cr

JOIN carelabel_stocks ON carelabel_stocks.po_id = cr.po_id
JOIN carelabel_requests ON carelabel_requests.po_id = cr.po_id
JOIN pos ON pos.id = cr.po_id

WHERE 
(CAST(cr.created_at AS DATE) = CAST(GETDATE() AS DATE) AND cr.status != 'error')
OR
cr.status = 'pending'

GROUP BY 

cr.id,
cr.po_id,
cr.user_id,
cr.ponum,
cr.size,
cr.module,
cr.leader,
cr.status,
cr.type,
cr.comment,
cr.qty,
cr.created_at,
cr.updated_at,
carelabel_requests.po_id,
pos.total_order_qty,
pos.style,
pos.color,
pos.po_new

ORDER BY cr.status desc,cr.created_at asc
"));

		return view('carelabelrequesttable.index', compact('request_c'));
	}

	public function log()
	{
		//$request_c = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM carelabel_requests ORDER BY created_at desc"));
		$request_c = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM carelabel_requests WHERE created_at >= DATEADD(day,-21,GETDATE()) ORDER BY created_at desc"));
 		return view('carelabelrequesttable.log', compact('request_c'));
	}
	public function logmodule()
	{
		$module = Auth::user()->name;
		$request_c = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM carelabel_requests
															   WHERE module = '".$module."'
															   ORDER BY created_at desc
     														"));
 		return view('carelabelrequesttable.log', compact('request_c'));
	}

	public function edit($id) {

		$request_c = CarelabelRequest::findOrFail($id);		
		return view('carelabelrequesttable.edit', compact('request_c'));
	}
	public function editp($id) {

		$request_c = CarelabelRequest::findOrFail($id);		
		return view('carelabelrequesttable.editp', compact('request_c'));
	}

	public function update($id, Request $request) {

		$request_c = CarelabelRequest::findOrFail($id);		
		//$request_c->update($request->all());

		$input = $request->all(); 
		//dd($input);

		//$request_c->id = $input['id'];
		$request_c->po_id = $input['po_id'];
		$request_c->user_id = $input['user_id'];
		$request_c->ponum = $input['ponum'];
		$request_c->size = $input['size'];
		
		$qty = $input['qty'];
		$status = $input['status'];

		if (($qty == 0) OR ($qty == NULL)) {

			if ($status == 'error'){
				$request_c->qty = NULL;			
				$request_c->status = 'error';	
			} else if ($status == 'back') {
				$request_c->qty = NULL;			
				$request_c->status = 'back';
			} else {
				$request_c->qty = NULL;			
				$request_c->status = 'pending';	
			}

		} else {				

			if ($status == 'error'){
				$request_c->qty = $qty;			
				$request_c->status = 'error';
			} else if ($status == 'back') {
				$request_c->qty = $qty;			
				$request_c->status = 'back';
			} else {
				$request_c->qty = $qty;			
				$request_c->status = 'confirmed';	
			}
		}
	
		$request_c->module = $input['module'];
		
		$request_c->leader = $input['leader'];
		$request_c->type = $input['type'];
		$request_c->comment = $input['comment'];
		$request_c->save();

		//return view('main.index');
		return Redirect::to('/carelabelrequesttable');
	}

	public function updatep($id, Request $request) {

		$request_c = CarelabelRequest::findOrFail($id);		
		//$request_c->update($request->all());

		$input = $request->all(); 
		//dd($input);

		$request_c->id = $input['id'];
		//$request_c->po_id = $input['po_id'];
		//$request_c->user_id = $input['user_id'];
		//$request_c->ponum = $input['ponum'];
		// $request_c->size = $input['size'];
		
		$qty = $input['qty'];
		$status = $input['status'];

		if (($qty == 0) OR ($qty == NULL)) {
			
			if ($status == 'error'){
				$request_c->qty = NULL;			
				$request_c->status = 'error';	
			} else if ($status == 'back') {
				$request_c->qty = NULL;			
				$request_c->status = 'back';
			} else {
				$request_c->qty = NULL;			
				$request_c->status = 'pending';	
			}

		} else {				

			if ($status == 'error'){
				$request_c->qty = $qty;			
				$request_c->status = 'error';
			} else if ($status == 'back') {
				$request_c->qty = $qty;			
				$request_c->status = 'back';
			} else {
				$request_c->qty = $qty;			
				$request_c->status = 'confirmed';	
			}
		}
	
		// $request_c->module = $input['module'];
		// $request_c->leader = $input['leader'];
		// $request_c->type = $input['type'];
		$request_c->comment = $input['comment'];
		$request_c->save();

		//return view('main.index');
		return Redirect::to('/carelabelrequesttable');
	}

	public function error($id, Request $request) {

		$request_c = CarelabelRequest::findOrFail($id);
		//$request_b->update($request->all());

		$input = $request->all(); 
		//dd($input);

		$request_c->id = $input['id'];
		$request_c->qty = NULL;
		$request_c->status = 'error';
		$request_c->save();

		//return view('main.index');
		return Redirect::to('/carelabelrequesttable');
	}

	public function rfid($id, Request $request) {

		$request_c = CarelabelRequest::findOrFail($id);
		//$request_b->update($request->all());

		$input = $request->all(); 
		// dd($input);

		$request_c->id = $input['id'];
		$request_c->qty = NULL;
		$request_c->status = 'done';
		$request_c->save();

		//return view('main.index');
		return Redirect::to('/carelabelrequesttable');
	}

	public function print_request_c(Request $request) {
		
		$input = $request->all(); 
		// dd($input['id']);

		$request_b = DB::connection('sqlsrv')->select(DB::raw("SELECT	p.[id] as po_id
				,p.[po_new]
		      ,p.[style]
		      ,p.[color]
		      ,p.[size]
		      ,b.[module]
		      ,b.[leader]
		      ,b.[comment]
		      ,b.[created_at]
		      ,b.[qty]
		  FROM [preparation].[dbo].[carelabel_requests] as b
		  JOIN [preparation].[dbo].[pos] as p ON p.[id] = b.[po_id]
		  WHERE b.[id] = '".$input['id']."' "));
		// dd($request_b[0]->po_new);


		$b = new print_request_label;
		$b->po_id = $request_b[0]->po_id;
		$b->po = 	$request_b[0]->po_new;
		$b->type = 'Carelabel';
		$b->style = $request_b[0]->style;
		$b->color = $request_b[0]->color;
		$b->size =  $request_b[0]->size;
		$b->module = $request_b[0]->module;
		$b->leader = $request_b[0]->leader;

		$b->comment = 	$request_b[0]->comment;
		$b->created =  	substr($request_b[0]->created_at, 0, 16);

		$b->printer =  'Preparacija Zebra';
		$b->qty =  $request_b[0]->qty;
		$b->save();

		return Redirect::to('/carelabelrequesttable');
	}

	
	

}
