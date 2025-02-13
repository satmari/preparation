<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

//use Gbrock\Table\Facades\Table;
//use Gbrock\Traits\Sortable;

use App\BarcodeStock;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

class barcodestocktableController extends Controller {

	public function index()
	{
		//
		//$table;

		//$user = User::find(Auth::id());
		//if ($user->level() == 1) {
			//$rows = BarcodeStock::all();
			//$rows = BarcodeStock::sorted()->get();
			//$rows = MainModel::sorted()->get(); 
 			//$table = Table::create($rows); // Generate a Table based on these "rows"
 			//$table = Table::create($rows, ['id','po_id','user_id','ponum','size','qty','module',/*'status',*/'type','comment','created_at']);
		 //}

		$stock_b = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM barcode_stocks WHERE created_at >= DATEADD(day,-60,GETDATE()) ORDER BY created_at desc"));
 		return view('barcodestocktable.index', compact('stock_b'));
 		
	}


}
