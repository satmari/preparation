<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

use Gbrock\Table\Facades\Table;
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
		//Gbrock

		//$user = User::find(Auth::id());
		//if ($user->level() == 1) {
			//$rows = BarcodeRequest::all();
			//$rows = BarcodeStock::sorted()->get();
			//$rows = MainModel::sorted()->get(); 
 			//$table = Table::create($rows); // Generate a Table based on these "rows"
 			//$table = Table::create($rows, ['id','po_id','user_id','ponum','size','qty','module','leader','status','type','comment','created_at','updated_at']);

 		//}
 		$request_b = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM barcode_requests"));

		return view('barcoderequesttable.index', compact('request_b'));
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

		$request_b = BarcodeRequest::findOrFail($id);		

		return view('barcoderequesttable.edit', compact('request_b'));

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
		if (($qty <= 0) OR ($qty == NULL)) {
			$request_b->qty = NULL;			
			$request_b->status = 'pending';
		} else if ($qty > 0) {				
			$request_b->qty = $qty;
			$request_b->status = 'confirmed';
		}
	
		$request_b->module = $input['module'];
		$request_b->leader = $input['leader'];
		$request_b->type = $input['type'];
		$request_b->comment = $input['comment'];
		$request_b->save();

		//return view('main.index');
		return Redirect::to('/barcoderequesttable');
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
