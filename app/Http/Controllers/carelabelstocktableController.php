<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

//use Gbrock\Table\Facades\Table;
//use Gbrock\Traits\Sortable;

use App\CarelabelStock;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

class carelabelstocktableController extends Controller {

	public function index()
	{
		//
		//
		//$table;

		//$user = User::find(Auth::id());
		//if ($user->level() == 1) {
			//$rows = CarelabelStock::all();
			//$rows = BarcodeStock::sorted()->get();
			//$rows = MainModel::sorted()->get(); 
 			//$table = Table::create($rows); // Generate a Table based on these "rows"
 			//$table = Table::create($rows, ['id','po_id','user_id','ponum','size','qty','module',/*'status',*/'type','comment','created_at']);

 		//}


 		$stock_c = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM carelabel_stocks WHERE created_at >= DATEADD(day,-60,GETDATE()) ORDER BY created_at desc"));

 		return view('carelabelstocktable.index', compact('stock_c'));
	}

	public function create()
	{
		//
	}

	public function store()
	{
		//
	}

	public function show($id)
	{
		//
	}

	public function edit($id)
	{
		//
	}

	public function update($id)
	{
		//
	}

	public function destroy($id)
	{
		//
	}

}
