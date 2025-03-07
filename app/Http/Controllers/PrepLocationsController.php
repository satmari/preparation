<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\BarcodeRequest;
use App\CarelabelRequest;
use App\SecondQRequest;
use App\Po;
use App\Module;
use App\prep_location;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;


class PrepLocationsController extends Controller {

	public function index()	{

		$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM prep_locations 
			ORDER BY created_at desc "));

		return view('PrepLocations.index', compact('locations'));
	}

	public function location_create() {

		return view('PrepLocations.create');
	}

	public function location_create_post(Request $request)	{

		$input = $request->all(); 
		// dd($input);

		if (!isset($input['location'])) {
			dd('Location must be set');

			
		}
		if ($input['location_plant'] == '') {
			dd('Location Plant must be set');
			
			
		}

		$location = $input['location'];
		$location_desc = $input['location_desc'];
		$location_plant = $input['location_plant'];

		$table = new prep_location;
		$table->location = $location;
		$table->location_desc = $location_desc;
		$table->location_plant = $location_plant;
		$table->save();
		
		return Redirect::to('prep_locations');

	}

	public function location_edit($id) {

		// dd($id);

		$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM prep_locations
			WHERE  id = '".$id."' "));
		$location = $locations[0]->location;
		$location_desc = $locations[0]->location_desc;
		$location_plant = $locations[0]->location_plant;

		return view('PrepLocations.edit', compact('id','location', 'location_desc', 'location_plant'));

	}

	public function location_edit_post(Request $request)	{

		$input = $request->all(); 
		// dd($input);

		if (!isset($input['location'])) {
			dd('Location must be set');
			
		}
		if ($input['location_plant'] == '') {
			dd('Location Plant must be set');
						
		}

		$id = $input['id'];
		$location = $input['location'];
		$location_desc = $input['location_desc'];
		$location_plant = $input['location_plant'];

		$table = prep_location::findOrFail($id);
		$table->location = $location;
		$table->location_desc = $location_desc;
		$table->location_plant = $location_plant;
		$table->save();
		
		return Redirect::to('prep_locations');

	}

	public function location_assign() {


		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul or User is not autenticated';
			return view('Lines.error',compact('msg'));
		}
		// dd($module);
		if ($module == 'kikinda') {
			$location_plant = 'Kikinda';
		} elseif ($module == 'senta') {
			$location_plant = 'Senta';
		} else {
			$location_plant = 'Subotica';
		}

		$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT pos.*
		  --,(SELECT location FROM prep_locations WHERE id = pos.loc_id_su) as loc_su,
		  --,(SELECT location FROM prep_locations WHERE id = pos.loc_id_ki) as loc_ki,
		  --,(SELECT location FROM prep_locations WHERE id = pos.loc_id_se) as loc_se
			FROM pos as pos
			LEFT JOIN [posummary].dbo.pro as posum ON posum.po_new = pos.po_new
			WHERE pos.closed_po = 'Open' AND posum.location_all = '".$location_plant."'
		"));
		// dd($pos);


		$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM prep_locations
			WHERE  location_plant = '".$location_plant."' "));

		// dd($locations);
		$posArray = ['' => ''];
		foreach ($pos as $item) {
		    $posArray[$item->id] = $item->po_new;
		}

		$locationsArray = ['' => ''];
		foreach ($locations as $item) {
		    $locationsArray[$item->id] = $item->location;
		}

		return view('PrepLocations.location_assign', compact('location_plant','posArray','locationsArray'));

	}

	public function location_assign_post(Request $request)	{

		$input = $request->all(); 
		// dd($input);

		$po_id = $input['po_id'];
		$location_id = $input['location_id'];
		$location_plant = $input['location_plant'];

		$location = prep_location::findOrFail($location_id);

		$po = po::findOrFail($po_id);
		// dd($po);

		if ($location_plant == 'Senta') {
			$po->loc_id_se = $location_id;
			
		} elseif ($location_plant == 'Kikinda') {
			$po->loc_id_ki = $location_id;

		} else {
			$po->loc_id_su = $location_id;
		}

		$po->save();






	}




	

}
