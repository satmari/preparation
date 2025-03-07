<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

//use Gbrock\Table\Facades\Table;

use App\BarcodeKiStock;
use App\CarelabelKiStock;

use App\Po;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

class kikindaController extends Controller {


	public function index() {
		//
		// dd('kik');
		$msg='kikinda';

		return view('kikinda.index', compact('msg'));
	}

	public  function kikinda_stock() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT  pos.id,
		pos.po,
		pos.po_new,
		posum.location_all,
		pos.size,
		pos.style,
		pos.color,
		pos.color_desc,
		pos.flash,
		pos.brand,
		pos.skeda,
		pos.total_order_qty,
		pos.no_lines_by_skeda,
		(SELECT p.location FROM prep_locations as p WHERE p.id = pos.loc_id_ki) as location,

		(SELECT SUM(barcode_stocks.qty)  FROM barcode_stocks WHERE barcode_stocks.po_id = pos.id ) stock_b,
		(SELECT SUM(barcode_requests.qty)  FROM barcode_requests WHERE barcode_requests.po_id = pos.id AND barcode_requests.status != 'error') request_b,
		(SELECT SUM(carelabel_stocks.qty)  FROM carelabel_stocks WHERE carelabel_stocks.po_id = pos.id ) stock_c,
		(SELECT SUM(carelabel_requests.qty)  FROM carelabel_requests WHERE carelabel_requests.po_id = pos.id AND carelabel_requests.status != 'error') request_c,

		(SELECT SUM([barcode_ki_stocks].qty)  FROM [barcode_ki_stocks] WHERE [barcode_ki_stocks].po_id = pos.id AND [barcode_ki_stocks].status = 'to_receive') as to_receive_ki_b,
		(SELECT SUM([barcode_ki_stocks].qty)  FROM [barcode_ki_stocks] WHERE [barcode_ki_stocks].po_id = pos.id AND [barcode_ki_stocks].status != 'to_receive') as stock_ki_b,

		(SELECT SUM([carelabel_ki_stocks].qty)  FROM [carelabel_ki_stocks] WHERE [carelabel_ki_stocks].po_id = pos.id AND [carelabel_ki_stocks].status = 'to_receive') as to_receive_ki_c,
		(SELECT SUM([carelabel_ki_stocks].qty)  FROM [carelabel_ki_stocks] WHERE [carelabel_ki_stocks].po_id = pos.id AND [carelabel_ki_stocks].status != 'to_receive') as stock_ki_c
		
		FROM pos

		--LEFT JOIN barcode_ki_stocks ON barcode_ki_stocks.po_id = pos.id
		--LEFT JOIN carelabel_ki_stocks ON carelabel_ki_stocks.po_id = pos.id

		LEFT JOIN [posummary].dbo.pro as posum ON posum.po_new = pos.po_new

		WHERE pos.closed_po = 'Open' AND posum.location_all = 'Kikinda'
		GROUP BY	pos.id,
					pos.po,
					pos.po_new,
					posum.location_all,
					pos.size,
					pos.style,
					pos.color,
					pos.color_desc,
					--pos.season,
					pos.flash,
					pos.brand,
					pos.skeda,
					pos.total_order_qty,
					pos.no_lines_by_skeda,
					pos.loc_id_ki

		ORDER BY pos.po asc,
			     pos.size desc
		"));

		// dd($data);

		return view('kikinda.kikinda_stock',compact('data'));
	}

	public function receive_from_su_b() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
				(SELECT po_new FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as po_new,
				(SELECT style FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as style,
				(SELECT color FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as color
				 FROM barcode_ki_stocks
				WHERE status = 'to_receive'
		"));
		// dd($data);

		return view('kikinda.receive_from_su_b',compact('data'));
	}

	public function receive_from_su_b_post($id, $qty) {
		//
		return view('kikinda.receive_from_su_b_post', compact('id','qty'));
	}

	public function receive_from_su_b_post_confirm(Request $request) {		
		
		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		$qty = $input['qty'];
		$status = 'stock';
		$type = NULL;
		
		$barcode_ki = BarcodeKiStock::where('id', $id)->firstOrFail();
		// $barcode_ki->po_id = $poid;
		// $barcode_ki->user_id = $userId;
		// $barcode_ki->ponum = $ponum;
		// $barcode_ki->size = $size;
		$barcode_ki->qty = $qty;
		// $barcode_ki->qty = $qty;
		//$barcode_ki->module = $module;
		$barcode_ki->status = $status;
		$barcode_ki->type = $type;
		// $barcode_ki->comment = $comment;
		$barcode_ki->save();

		return redirect('receive_from_su_b');

	}


}
