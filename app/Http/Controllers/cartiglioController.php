<?php namespace App\Http\Controllers;

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


class cartiglioController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		// dd("test");

		$data = DB::connection('sqlsrv4');

		dd($data);

		$data = DB::connection('sqlsrv4')->select(DB::raw("SELECT [id]
	      ,[Cod_Bar]
	      ,[Cod_Art_CZ]
	      ,[Cod_Col_CZ]
	      ,[Tgl_ITA]
	      ,[Tgl_ENG]
	      ,[Tgl_SPA]
	      ,[Tgl_EUR]
	      ,[Tgl_USA]
	      ,[Descr_Col_CZ]
		  FROM cartiglio"));

		// dd($data);
		return view('cartiglio.index', compact('data'));
	}


}
