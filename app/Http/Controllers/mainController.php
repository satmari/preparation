<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

use App\User;
use App\Po;
use DB;

use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;


class mainController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM pos"));

		//dd($postable);

		return view('main.index',compact('pos'));
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

		$po = Po::findOrFail($id);		

		return view('main.edit', compact('po'));

	}
	public function update($id, Request $request) {

		$po = Po::findOrFail($id);		
		$po->update($request->all());

		//return view('main.index');
		return Redirect::to('/main');
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
