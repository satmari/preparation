<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

use Gbrock\Table\Facades\Table;
//use Gbrock\Traits\Sortable;

use App\CarelabelRequest;
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
		//
		//
		//$table;

		//$user = User::find(Auth::id());
		//if ($user->level() == 1) {
			//$rows = CarelabelRequest::all();
			//$rows = BarcodeStock::sorted()->get();
			//$rows = MainModel::sorted()->get(); 
 			//$table = Table::create($rows); // Generate a Table based on these "rows"
 			//$table = Table::create($rows, ['id','po_id','user_id','ponum','size','qty','module','leader','status','type','comment','created_at','updated_at']);

 		//}
 		$request_c = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM carelabel_requests"));

		return view('carelabelrequesttable.index', compact('request_c'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
	public function edit($id) {

		$request_c = CarelabelRequest::findOrFail($id);		

		return view('carelabelrequesttable.edit', compact('request_c'));

	}
	public function update($id, Request $request) {

		$request_c = CarelabelRequest::findOrFail($id);		
		//$request_c->update($request->all());

		$input = $request->all(); 
		//dd($input);

		//$request_b->id = $input['id'];
		$request_c->po_id = $input['po_id'];
		$request_c->user_id = $input['user_id'];
		$request_c->ponum = $input['ponum'];
		$request_c->size = $input['size'];
		
		$qty = $input['qty'];
		if (($qty <= 0) OR ($qty == NULL)) {
			$request_c->qty = NULL;			
			$request_c->status = 'pending';
		} else if ($qty > 0) {				
			$request_c->qty = $qty;
			$request_c->status = 'confirmed';
		}
	
		$request_c->module = $input['module'];
		$request_c->leader = $input['leader'];
		$request_c->type = $input['type'];
		$request_c->comment = $input['comment'];
		$request_c->save();

		//return view('main.index');
		return Redirect::to('/carelabelrequesttable');
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
