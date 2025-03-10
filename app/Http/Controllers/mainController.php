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

	public function index()
	{
		$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
			(SELECT location FROM prep_locations WHERE id = loc_id_su) as location
		    FROM pos
			WHERE created_at >= DATEADD(YEAR, -1, GETDATE()) 
			OR (created_at < DATEADD(YEAR, -1, GETDATE()) AND status = 'Open')
			ORDER BY created_at desc
		"));

		//dd($postable);

		return view('main.index',compact('pos'));
	}

	
	public function edit($id) {

		$po = Po::findOrFail($id);		

		return view('main.edit', compact('po'));

	}
	public function update($id, Request $request) {

		// $input = $request->all(); 
		// dd($input);
		
		$po = Po::findOrFail($id);
		$po->update($request->all());

		//return view('main.index');
		return Redirect::to('/main');
	}

	

}
