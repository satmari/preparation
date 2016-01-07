<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\User;
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
		
		$postable = DB::connection('sqlsrv')->select(DB::raw("SELECT  pos.id,
		pos.po,
		pos.size,
		pos.style,
		pos.color,
		pos.color_desc,
		pos.season,
		pos.total_order_qty,
		(SELECT SUM(barcode_stocks.qty)  FROM barcode_stocks WHERE barcode_stocks.po_id = pos.id ) stock_b,
		(SELECT SUM(barcode_requests.qty)  FROM barcode_requests WHERE barcode_requests.po_id = pos.id ) request_b,
		(SELECT SUM(carelabel_stocks.qty)  FROM carelabel_stocks WHERE carelabel_stocks.po_id = pos.id ) stock_c,
		(SELECT SUM(carelabel_requests.qty)  FROM carelabel_requests WHERE carelabel_requests.po_id = pos.id ) request_c
		FROM pos
		LEFT JOIN barcode_stocks ON barcode_stocks.po_id = pos.id
		LEFT JOIN barcode_requests ON barcode_requests.po_id = pos.id
		LEFT JOIN carelabel_stocks ON carelabel_stocks.po_id = pos.id
		LEFT JOIN carelabel_requests ON carelabel_requests.po_id = pos.id
		GROUP BY	pos.id,
					pos.po,
					pos.size,
					pos.style,
					pos.color,
					pos.color_desc,
					pos.season,
					pos.total_order_qty"
		));

		//dd($postable);

		return view('maintable.index',compact('postable'));
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


