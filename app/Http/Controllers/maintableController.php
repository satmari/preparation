<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Gbrock\Table\Facades\Table;

use App\User;
use App\Po;
use App\BarcodeStock;
use DB;

use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

class maintableController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//$table;
		//$user = User::find(Auth::id());
		//if ($user->level() == 1) {
			$pos = Po::all(); 
			//po = Po::find(35); 
			//$rows = MainModel::sorted()->get(); 
 			$table = Table::create($pos); // Generate a Table based on these "rows"
 			//$barcode = BarcodeStock::all();
 			//$test = $comment->$rows;
 			$potest = Po::find(1)->barcode_stocks;
 			//$test = $rowss;
 			//$comments = Post::find(1)->comments;

 			$test=0;
 			foreach (Po::find(1)->barcode_stocks as $line) {
 				$test = $test + $line->qty;
 			}

 			$sum = Po::find(1)->barcode_stocks->sum('qty');

 			$bartest = BarcodeStock::find(1)->user->name;
 			$bartest1 = BarcodeStock::find(1)->po->order_code;



  		//}
 		return view('maintable.index', compact('table','potest','test','sum','bartest','bartest1'));
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
	public function show()
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

