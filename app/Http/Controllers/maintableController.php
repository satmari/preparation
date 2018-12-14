<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

use App\User;
use DB;

use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

class maintableController extends Controller {

	public function index()
	{
		
		$postable = DB::connection('sqlsrv')->select(DB::raw("SELECT  pos.id,
		pos.po,
		pos.size,
		pos.style,
		pos.color,
		pos.color_desc,
		pos.season,
		pos.flash,
		pos.brand,
		pos.total_order_qty,
		(SELECT SUM(barcode_stocks.qty)  FROM barcode_stocks WHERE barcode_stocks.po_id = pos.id ) stock_b,
		(SELECT SUM(barcode_requests.qty)  FROM barcode_requests WHERE barcode_requests.po_id = pos.id AND barcode_requests.status != 'error') request_b,
		(SELECT SUM(carelabel_stocks.qty)  FROM carelabel_stocks WHERE carelabel_stocks.po_id = pos.id ) stock_c,
		(SELECT SUM(carelabel_requests.qty)  FROM carelabel_requests WHERE carelabel_requests.po_id = pos.id AND carelabel_requests.status != 'error') request_c
		FROM pos
		LEFT JOIN barcode_stocks ON barcode_stocks.po_id = pos.id
		LEFT JOIN barcode_requests ON barcode_requests.po_id = pos.id
		LEFT JOIN carelabel_stocks ON carelabel_stocks.po_id = pos.id
		LEFT JOIN carelabel_requests ON carelabel_requests.po_id = pos.id
		WHERE pos.closed_po = 'Open'
		GROUP BY	pos.id,
					pos.po,
					pos.size,
					pos.style,
					pos.color,
					pos.color_desc,
					pos.season,
					pos.flash,
					pos.brand,
					pos.total_order_qty
		ORDER BY pos.po asc,
			     pos.size desc"
		));

		//dd($postable);

		return view('maintable.index',compact('postable'));
	}

	public function index_planer()
	{
		
		$postable = DB::connection('sqlsrv')->select(DB::raw("SELECT  pos.id,
		pos.po,
		pos.size,
		pos.style,
		pos.color,
		pos.color_desc,
		pos.season,
		pos.flash,
		pos.brand,
		pos.total_order_qty,
		pos.hangtag,
		(SELECT SUM(barcode_stocks.qty)  FROM barcode_stocks WHERE barcode_stocks.po_id = pos.id ) stock_b,
		(SELECT SUM(barcode_requests.qty)  FROM barcode_requests WHERE barcode_requests.po_id = pos.id AND barcode_requests.status != 'error') request_b,
		(SELECT SUM(carelabel_stocks.qty)  FROM carelabel_stocks WHERE carelabel_stocks.po_id = pos.id ) stock_c,
		(SELECT SUM(carelabel_requests.qty)  FROM carelabel_requests WHERE carelabel_requests.po_id = pos.id AND carelabel_requests.status != 'error') request_c
		FROM pos
		LEFT JOIN barcode_stocks ON barcode_stocks.po_id = pos.id
		LEFT JOIN barcode_requests ON barcode_requests.po_id = pos.id
		LEFT JOIN carelabel_stocks ON carelabel_stocks.po_id = pos.id
		LEFT JOIN carelabel_requests ON carelabel_requests.po_id = pos.id
		WHERE pos.closed_po = 'Open'
		GROUP BY	pos.id,
					pos.po,
					pos.size,
					pos.style,
					pos.color,
					pos.color_desc,
					pos.season,
					pos.flash,
					pos.brand,
					pos.total_order_qty,
					pos.hangtag
		ORDER BY pos.po asc,
			     pos.size desc"
		));

		//dd($postable);

		return view('maintable.index_planer',compact('postable'));
	}

}


