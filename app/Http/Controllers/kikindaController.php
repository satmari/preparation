<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use Session;
use Validator;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use App\BarcodeStock;
use App\CarelabelStock;
use App\BarcodeKiStock;
use App\CarelabelKiStock;
use App\prep_location;

use App\Po;
use DB;


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
		(SELECT SUM([barcode_ki_stocks].qty)*(-1)  FROM [barcode_ki_stocks] WHERE [barcode_ki_stocks].po_id = pos.id AND [barcode_ki_stocks].type = 'in_line') as given_ki_b,

		(SELECT SUM([carelabel_ki_stocks].qty)  FROM [carelabel_ki_stocks] WHERE [carelabel_ki_stocks].po_id = pos.id AND [carelabel_ki_stocks].status = 'to_receive') as to_receive_ki_c,
		(SELECT SUM([carelabel_ki_stocks].qty)  FROM [carelabel_ki_stocks] WHERE [carelabel_ki_stocks].po_id = pos.id AND [carelabel_ki_stocks].status != 'to_receive') as stock_ki_c,
		(SELECT SUM([carelabel_ki_stocks].qty)*(-1)  FROM [carelabel_ki_stocks] WHERE [carelabel_ki_stocks].po_id = pos.id AND [carelabel_ki_stocks].type = 'in_line') as given_ki_c
		
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

//Barcodes
	public function receive_from_su_b() {
		// dd(phpinfo());
		try {
		
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
					(SELECT po_new FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as po_new,
					(SELECT style FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as style,
					(SELECT color FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as color,
					(SELECT l.location FROM [pos] as p 
						JOIN prep_locations as l ON l.id = p.loc_id_ki 
						WHERE p.id = barcode_ki_stocks.po_id) as location
					 FROM barcode_ki_stocks
					WHERE status = 'to_receive'
			"));
			// dd($data);
		} catch (\Illuminate\Database\QueryException $e) {
		    $data = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
					(SELECT po_new FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as po_new,
					(SELECT style FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as style,
					(SELECT color FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as color,
					(SELECT l.location FROM [pos] as p 
						JOIN prep_locations as l ON l.id = p.loc_id_ki 
						WHERE p.id = barcode_ki_stocks.po_id) as location
					 FROM barcode_ki_stocks
					WHERE status = 'to_receive'
			"));
			// dd($data);
		}

		return view('kikinda.receive_from_su_b',compact('data'));
	}

	public function receive_from_su_b_post($id) {
		//
		// dd($id);
		try {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
					(SELECT po_new FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as po_new,
					(SELECT style FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as style,
					(SELECT color FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as color,
					(SELECT loc_id_ki FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as location_id,
					(SELECT l.location FROM [pos] as p 
						JOIN prep_locations as l ON l.id = p.loc_id_ki 
						WHERE p.id = barcode_ki_stocks.po_id) as location
					 FROM barcode_ki_stocks
					WHERE status = 'to_receive' and id = '".$id."'
			"));
		} catch (\Illuminate\Database\QueryException $e) {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
					(SELECT po_new FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as po_new,
					(SELECT style FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as style,
					(SELECT color FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as color,
					(SELECT loc_id_ki FROM [pos] WHERE pos.id = barcode_ki_stocks.po_id) as location_id,
					(SELECT l.location FROM [pos] as p 
						JOIN prep_locations as l ON l.id = p.loc_id_ki 
						WHERE p.id = barcode_ki_stocks.po_id) as location
					 FROM barcode_ki_stocks
					WHERE status = 'to_receive' and id = '".$id."'
			"));
		}
		// dd($data);
		$id = $data[0]->id;
		$qty = $data[0]->qty;
		$location = $data[0]->location;
		$location_id = $data[0]->location_id;

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul or User is not autenticated';
			return view('kikinda.error',compact('msg'));
		}
		// dd($module);
		if ($module == 'kikinda') {
			$location_plant = 'Kikinda';
		} elseif ($module == 'senta') {
			$location_plant = 'Senta';
		} else {
			$location_plant = 'Subotica';
		}


		try {

			$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM prep_locations
				WHERE  location_plant = '".$location_plant."' "));
		} catch (\Illuminate\Database\QueryException $e) {
			$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM prep_locations
				WHERE  location_plant = '".$location_plant."' "));
		}

		$locationsArray = ['' => ''];
		foreach ($locations as $item) {
		    $locationsArray[$item->id] = $item->location;
		}

		return view('kikinda.receive_from_su_b_post', compact('id','qty','location','location_id','locationsArray'));
	}

	public function receive_from_su_b_post_confirm(Request $request) {		
		
		$input = $request->all(); 

		// dd($input);

		$id = $input['id'];
		$qty = $input['qty'];
		$location_id = (int)$input['location_id'];
		// dd($location_id);

		// $locations = prep_location::where('id', $location_id)->firstOrFail();
		// dd($locations);

		$status = 'stock';
		$type = NULL;
		
		if ($location_id == 0) {
			dd('location must be set');
		}

		try {
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

			$po = Po::where('id', $barcode_ki->po_id)->firstOrFail();
			$po->loc_id_ki = $location_id;
			$po->save();

		} catch (\Illuminate\Database\QueryException $e) {
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

			$po = Po::where('id', $barcode_ki->po_id)->firstOrFail();
			$po->loc_id_ki = $location_id;
			$po->save();
		}

		return redirect('receive_from_su_b');
	}


//Carelabels
	public function receive_from_su_c() {
		// dd(phpinfo());
		try {

			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
					(SELECT po_new FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as po_new,
					(SELECT style FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as style,
					(SELECT color FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as color,
					(SELECT l.location FROM [pos] as p 
						JOIN prep_locations as l ON l.id = p.loc_id_ki 
						WHERE p.id = carelabel_ki_stocks.po_id) as location
					 FROM carelabel_ki_stocks
					WHERE status = 'to_receive'
			"));
		} catch (\Illuminate\Database\QueryException $e) {

			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
					(SELECT po_new FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as po_new,
					(SELECT style FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as style,
					(SELECT color FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as color,
					(SELECT l.location FROM [pos] as p 
						JOIN prep_locations as l ON l.id = p.loc_id_ki 
						WHERE p.id = carelabel_ki_stocks.po_id) as location
					 FROM carelabel_ki_stocks
					WHERE status = 'to_receive'
			"));
		}

		// dd($data);

		return view('kikinda.receive_from_su_c',compact('data'));
	}

	public function receive_from_su_c_post($id) {
		//
		// dd($id);
		try {

			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
					(SELECT po_new FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as po_new,
					(SELECT style FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as style,
					(SELECT color FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as color,
					(SELECT loc_id_ki FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as location_id,
					(SELECT l.location FROM [pos] as p 
						JOIN prep_locations as l ON l.id = p.loc_id_ki 
						WHERE p.id = carelabel_ki_stocks.po_id) as location
					 FROM carelabel_ki_stocks
					WHERE status = 'to_receive' and id = '".$id."'
			"));

		} catch (\Illuminate\Database\QueryException $e) {

			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *,
					(SELECT po_new FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as po_new,
					(SELECT style FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as style,
					(SELECT color FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as color,
					(SELECT loc_id_ki FROM [pos] WHERE pos.id = carelabel_ki_stocks.po_id) as location_id,
					(SELECT l.location FROM [pos] as p 
						JOIN prep_locations as l ON l.id = p.loc_id_ki 
						WHERE p.id = carelabel_ki_stocks.po_id) as location
					 FROM carelabel_ki_stocks
					WHERE status = 'to_receive' and id = '".$id."'
			"));
		}
		// dd($data);
		$id = $data[0]->id;
		$qty = $data[0]->qty;
		$location = $data[0]->location;
		$location_id = $data[0]->location_id;

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul or User is not autenticated';
			return view('kikinda.error',compact('msg'));
		}
		// dd($module);
		if ($module == 'kikinda') {
			$location_plant = 'Kikinda';
		} elseif ($module == 'senta') {
			$location_plant = 'Senta';
		} else {
			$location_plant = 'Subotica';
		}
		try {
			$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM prep_locations
				WHERE  location_plant = '".$location_plant."' "));
		} catch (\Illuminate\Database\QueryException $e) {
			$locations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM prep_locations
				WHERE  location_plant = '".$location_plant."' "));
		}

		$locationsArray = ['' => ''];
		foreach ($locations as $item) {
		    $locationsArray[$item->id] = $item->location;
		}

		return view('kikinda.receive_from_su_c_post', compact('id','qty','location','location_id','locationsArray'));
	}

	public function receive_from_su_c_post_confirm(Request $request) {		
		
		$input = $request->all(); 

		// dd($input);

		$id = $input['id'];
		$qty = $input['qty'];
		$location_id = $input['location_id'];

		// $locations = prep_location::where('id', $location_id)->firstOrFail();
		// dd($locations);

		$status = 'stock';
		$type = NULL;
		
		try {

			$barcode_ki = CarelabelKiStock::where('id', $id)->firstOrFail();
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

			$po = Po::where('id', $barcode_ki->po_id)->firstOrFail();
			$po->loc_id_ki = $location_id;
			$po->save();

		} catch (\Illuminate\Database\QueryException $e) {

			$barcode_ki = CarelabelKiStock::where('id', $id)->firstOrFail();
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

			$po = Po::where('id', $barcode_ki->po_id)->firstOrFail();
			$po->loc_id_ki = $location_id;
			$po->save();
		}

		return redirect('receive_from_su_c');
	}

// Give

	public function give_to_the_line() {

		$location_plant = 'Kikinda';

		try {
			$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT pos.*
			  	FROM pos as pos
				LEFT JOIN [posummary].dbo.pro as posum ON posum.po_new = pos.po_new
				WHERE pos.closed_po = 'Open' AND posum.location_all = '".$location_plant."'
			"));

		} catch (\Illuminate\Database\QueryException $e) {
			$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT pos.*
			  	FROM pos as pos
				LEFT JOIN [posummary].dbo.pro as posum ON posum.po_new = pos.po_new
				WHERE pos.closed_po = 'Open' AND posum.location_all = '".$location_plant."'
			"));
		}
		// dd($pos);

		$posArray = ['' => ''];
		foreach ($pos as $item) {
		    $posArray[$item->po_new] = $item->po_new;
		}
		// dd($posArray);
		try {
			$lines = DB::connection('sqlsrv8')->select(DB::raw("SELECT [location]
			  FROM [locations]
			  WHERE location_dest = 'KIKINDA' AND
			  		location_type = 'MODULE/LINE' AND 
			  		location LIKE '%A'
			  		ORDER BY location asc
			   "));
		} catch (\Illuminate\Database\QueryException $e) {
			$lines = DB::connection('sqlsrv8')->select(DB::raw("SELECT [location]
			  FROM [locations]
			  WHERE location_dest = 'KIKINDA' AND
			  		location_type = 'MODULE/LINE' AND 
			  		location LIKE '%A'
			  		ORDER BY location asc
			   "));
		}
		// dd($lines);
		
		$locationsArray = ['' => ''];
		foreach ($lines as $item) {
		    $locationsArray[$item->location] = $item->location;
		}
		// dd($locationsArray);

		return view('kikinda.give_to_the_line', compact('posArray','locationsArray'));
	}

	public function give_to_the_line_post(Request $request) {		
		
		$input = $request->all(); 
		// dd($input);

		$ponum = $input['po'];
		$location = $input['location'];
		$qty = $input['qty'];
		$comment = $input['comment'];

		if (isset($input['barcode'])) {
			$barcode = $input['barcode'];
		} else {
			$barcode = '0';
		}
		if (isset($input['carelabel'])) {
			$carelabel = $input['carelabel'];
		} else {
			$carelabel = '0';
		}

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul or User is not autenticated';
			return view('Request.error',compact('msg'));
		}

		$poid = Po::where('po', $ponum)->firstOrFail()->id;
		$size = Po::where('po', $ponum)->firstOrFail()->size;

		$msg = "";

		$msgs = "";
		$msge = "";

		$type = "in_line";
		$status = "stock";
		$qty = $qty * (-1);

		if ($barcode == '1') {
			// dd('b');
			try {
				$check_if_is_in_stock = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([barcode_ki_stocks].qty) as barcode_ki_stock
					FROM [barcode_ki_stocks] 
					WHERE [barcode_ki_stocks].po_id = '".$poid."' AND 
						[barcode_ki_stocks].status != 'to_receive' 
						
			   	"));
			} catch (\Illuminate\Database\QueryException $e) {
				$check_if_is_in_stock = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([barcode_ki_stocks].qty) as barcode_ki_stock
					FROM [barcode_ki_stocks] 
					WHERE [barcode_ki_stocks].po_id = '".$poid."' AND 
						[barcode_ki_stocks].status != 'to_receive' 
						
			   	"));
			}
			// dd((int)$check_if_is_in_stock[0]->barcode_ki_stock);
			
			if ((int)$check_if_is_in_stock[0]->barcode_ki_stock + $qty > 0) {
				// dd('ima na stanju');

				
				$barcode_ki = new BarcodeKiStock;
				$barcode_ki->po_id = $poid;
				$barcode_ki->user_id = $userId;
				$barcode_ki->ponum = $ponum;
				$barcode_ki->size = $size;
				$barcode_ki->qty = $qty;
				// $barcode_ki->module = $module;
				$barcode_ki->status = $status;
				$barcode_ki->type = $type;
				$barcode_ki->comment = $comment;
				$barcode_ki->save();

				$msgs = 'Barcode successfully given to  the line';
			} else {
				// dd('nema na stanju');
				$msge = 'Barcode not enough on stock!';

			}

		} 

		if ($carelabel == '1') {
			// dd('c');
			$check_if_is_in_stock = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([carelabel_ki_stocks].qty) as carelabel_ki_stock
				FROM [carelabel_ki_stocks] 
				WHERE [carelabel_ki_stocks].po_id = '".$poid."' AND 
					[carelabel_ki_stocks].status != 'to_receive' 
		   	"));
			// dd((int)$check_if_is_in_stock[0]->carelabel_ki_stock);
			
			if ((int)$check_if_is_in_stock[0]->carelabel_ki_stock + $qty > 0) {
				// dd('ima na stanju');

				$carelabel_ki = new CarelabelKiStock;
				$carelabel_ki->po_id = $poid;
				$carelabel_ki->user_id = $userId;
				$carelabel_ki->ponum = $ponum;
				$carelabel_ki->size = $size;
				$carelabel_ki->qty = $qty;
				// $carelabel_ki->module = $module;
				$carelabel_ki->status = $status;
				$carelabel_ki->type = $type;
				$carelabel_ki->comment = $comment;
				$carelabel_ki->save();

				$msgs = $msgs. 'Carelabel successfully given to  the line';
			} else {
				// dd('nema na stanju');
				$msge = $msge. 'Carelabel not enough on stock!';
			}
		}

		if ($barcode == '0' AND $carelabel == '0') {
			$msge = 'Barcode or Carelabel NOT SELECTED !!!';
			// return view('kikinda.error',compact('msg'));
		}


		//same
		$location_plant = 'Kikinda';

		$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT pos.*
		  	FROM pos as pos
			LEFT JOIN [posummary].dbo.pro as posum ON posum.po_new = pos.po_new
			WHERE pos.closed_po = 'Open' AND posum.location_all = '".$location_plant."'
		"));
		// dd($pos);

		$posArray = ['' => ''];
		foreach ($pos as $item) {
		    $posArray[$item->po_new] = $item->po_new;
		}
		// dd($posArray);

		$lines = DB::connection('sqlsrv8')->select(DB::raw("SELECT [location]
		  FROM [locations]
		  WHERE location_dest = 'KIKINDA' AND
		  		location_type = 'MODULE/LINE' AND 
		  		location LIKE '%A'
		  		ORDER BY location asc
		   "));
		// dd($lines);
		
		$locationsArray = ['' => ''];
		foreach ($lines as $item) {
		    $locationsArray[$item->location] = $item->location;
		}
		// dd($locationsArray);

		return view('kikinda.give_to_the_line', compact('posArray','locationsArray','msge','msgs'));
		//
	}	

	public function return_su_from_kikinda() {
		// dd('return_su_from_kikinda');

		$location_plant = 'Kikinda';

		$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT pos.*
		  	FROM pos as pos
			LEFT JOIN [posummary].dbo.pro as posum ON posum.po_new = pos.po_new
			WHERE pos.closed_po = 'Open' AND posum.location_all = '".$location_plant."'
		"));
		// dd($pos);

		$posArray = ['' => ''];
		foreach ($pos as $item) {
		    $posArray[$item->po_new] = $item->po_new;
		}
		// dd($posArray);

		return view('kikinda.return_su_from_kikinda',compact('posArray'));
	}

	public function return_su_from_kikinda_post(Request $request) {		
		
		$input = $request->all(); 
		// dd($input);

		$ponum = $input['po'];
		$qty = $input['qty'];
		$comment = $input['comment'];

		if (isset($input['barcode'])) {
			$barcode = $input['barcode'];
		} else {
			$barcode = '0';
		}
		if (isset($input['carelabel'])) {
			$carelabel = $input['carelabel'];
		} else {
			$carelabel = '0';
		}

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul or User is not autenticated';
			return view('Request.error',compact('msg'));
		}

		$poid = Po::where('po', $ponum)->firstOrFail()->id;
		$size = Po::where('po', $ponum)->firstOrFail()->size;

		$msg = "";

		$msgs = "";
		$msge = "";

		$type = "returned";
		$status = "stock";
		$qty = $qty * (-1);

		if ($barcode == '1') {
			// dd('b');
			try {
				$check_if_is_in_stock = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([barcode_ki_stocks].qty) as barcode_ki_stock
					FROM [barcode_ki_stocks] 
					WHERE [barcode_ki_stocks].po_id = '".$poid."' AND 
						[barcode_ki_stocks].status != 'to_receive' 
						
			   	"));
			} catch (\Illuminate\Database\QueryException $e) {
				$check_if_is_in_stock = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([barcode_ki_stocks].qty) as barcode_ki_stock
					FROM [barcode_ki_stocks] 
					WHERE [barcode_ki_stocks].po_id = '".$poid."' AND 
						[barcode_ki_stocks].status != 'to_receive' 
						
			   	"));
			}
			// dd((int)$check_if_is_in_stock[0]->barcode_ki_stock);
			
			if ((int)$check_if_is_in_stock[0]->barcode_ki_stock + $qty > 0) {
				// dd('ima na stanju');

				// 				
				$barcode_ki = new BarcodeKiStock;
				$barcode_ki->po_id = $poid;
				$barcode_ki->user_id = $userId;
				$barcode_ki->ponum = $ponum;
				$barcode_ki->size = $size;
				$barcode_ki->qty = $qty;
				// $barcode_ki->module = $module;
				$barcode_ki->status = $status;
				$barcode_ki->type = $type;
				$barcode_ki->comment = $comment;
				$barcode_ki->save();

				// Add in Subotica
				$barcode = new BarcodeStock;
				$barcode->po_id = $poid;
				$barcode->user_id = $userId;
				$barcode->ponum = $ponum;
				$barcode->size = $size;
				$barcode->qty = $qty* (-1);
				//$barcode->module = $module;
				$barcode->status = NULL;
				$barcode->type = $type;
				$barcode->comment = $comment;
				$barcode->save();

				$msgs = 'Barcode successfully returned to Subotica';
			} else {
				// dd('nema na stanju');
				$msge = 'Barcode not enough on stock!';

			}

		} 

		if ($carelabel == '1') {
			// dd('c');
			$check_if_is_in_stock = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([carelabel_ki_stocks].qty) as carelabel_ki_stock
				FROM [carelabel_ki_stocks] 
				WHERE [carelabel_ki_stocks].po_id = '".$poid."' AND 
					[carelabel_ki_stocks].status != 'to_receive' 
		   	"));
			// dd((int)$check_if_is_in_stock[0]->carelabel_ki_stock);
			
			if ((int)$check_if_is_in_stock[0]->carelabel_ki_stock + $qty > 0) {
				// dd('ima na stanju');

				$carelabel_ki = new CarelabelKiStock;
				$carelabel_ki->po_id = $poid;
				$carelabel_ki->user_id = $userId;
				$carelabel_ki->ponum = $ponum;
				$carelabel_ki->size = $size;
				$carelabel_ki->qty = $qty;
				// $carelabel_ki->module = $module;
				$carelabel_ki->status = $status;
				$carelabel_ki->type = $type;
				$carelabel_ki->comment = $comment;
				$carelabel_ki->save();

				// Add in Subotica
				$barcode = new CarelabelStock;
				$barcode->po_id = $poid;
				$barcode->user_id = $userId;
				$barcode->ponum = $ponum;
				$barcode->size = $size;
				$barcode->qty = $qty* (-1);
				//$barcode->module = $module;
				$barcode->status = NULL;
				$barcode->type = $type;
				$barcode->comment = $comment;
				$barcode->save();

				$msgs = $msgs. 'Carelabel successfully returned to Subotica';
			} else {
				// dd('nema na stanju');
				$msge = $msge. 'Carelabel not enough on stock!';
			}
		}

		if ($barcode == '0' AND $carelabel == '0') {
			$msge = 'Barcode or Carelabel NOT SELECTED !!!';
			// return view('kikinda.error',compact('msg'));
		}

		//same
		$location_plant = 'Kikinda';

		$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT pos.*
		  	FROM pos as pos
			LEFT JOIN [posummary].dbo.pro as posum ON posum.po_new = pos.po_new
			WHERE pos.closed_po = 'Open' AND posum.location_all = '".$location_plant."'
		"));
		// dd($pos);

		$posArray = ['' => ''];
		foreach ($pos as $item) {
		    $posArray[$item->po_new] = $item->po_new;
		}
		// dd($posArray);

		$lines = DB::connection('sqlsrv8')->select(DB::raw("SELECT [location]
		  FROM [locations]
		  WHERE location_dest = 'KIKINDA' AND
		  		location_type = 'MODULE/LINE' AND 
		  		location LIKE '%A'
		  		ORDER BY location asc
		   "));
		// dd($lines);
		
		$locationsArray = ['' => ''];
		foreach ($lines as $item) {
		    $locationsArray[$item->location] = $item->location;
		}
		// dd($locationsArray);

		return view('kikinda.return_su_from_kikinda', compact('posArray','locationsArray','msge','msgs'));
		//
	}	

	public function throw_away_kikinda() {
		
		$location_plant = 'Kikinda';

		$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT pos.*
		  	FROM pos as pos
			LEFT JOIN [posummary].dbo.pro as posum ON posum.po_new = pos.po_new
			WHERE pos.closed_po = 'Open' AND posum.location_all = '".$location_plant."'
		"));
		// dd($pos);

		$posArray = ['' => ''];
		foreach ($pos as $item) {
		    $posArray[$item->po_new] = $item->po_new;
		}
		// dd($posArray);

		return view('kikinda.throw_away_kikinda', compact('posArray'));
	}
		
	public function throw_away_kikinda_post(Request $request) {		
		
		$input = $request->all(); 
		// dd($input);

		$ponum = $input['po'];
		$qty = $input['qty'];
		$comment = $input['comment'];

		if (isset($input['barcode'])) {
			$barcode = $input['barcode'];
		} else {
			$barcode = '0';
		}
		if (isset($input['carelabel'])) {
			$carelabel = $input['carelabel'];
		} else {
			$carelabel = '0';
		}

		// virfy userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul or User is not autenticated';
			return view('Request.error',compact('msg'));
		}

		$poid = Po::where('po', $ponum)->firstOrFail()->id;
		$size = Po::where('po', $ponum)->firstOrFail()->size;

		$msg = "";

		$msgs = "";
		$msge = "";

		$type = "throw_away";
		$status = "stock";
		$qty = $qty * (-1);


		if ($barcode == '1') {
			// dd('b');
			try {
				$check_if_is_in_stock = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([barcode_ki_stocks].qty) as barcode_ki_stock
					FROM [barcode_ki_stocks] 
					WHERE [barcode_ki_stocks].po_id = '".$poid."' AND 
						[barcode_ki_stocks].status != 'to_receive' 
			   	"));
			} catch (\Illuminate\Database\QueryException $e) {
				$check_if_is_in_stock = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([barcode_ki_stocks].qty) as barcode_ki_stock
					FROM [barcode_ki_stocks] 
					WHERE [barcode_ki_stocks].po_id = '".$poid."' AND 
						[barcode_ki_stocks].status != 'to_receive' 
			   	"));
			}
			// dd((int)$check_if_is_in_stock[0]->barcode_ki_stock);
			
			if ((int)$check_if_is_in_stock[0]->barcode_ki_stock + $qty > 0) {
				// dd('ima na stanju');
				
				$barcode_ki = new BarcodeKiStock;
				$barcode_ki->po_id = $poid;
				$barcode_ki->user_id = $userId;
				$barcode_ki->ponum = $ponum;
				$barcode_ki->size = $size;
				$barcode_ki->qty = $qty;
				// $barcode_ki->module = $module;
				$barcode_ki->status = $status;
				$barcode_ki->type = $type;
				$barcode_ki->comment = $comment;
				$barcode_ki->save();

				$msgs = 'Barcode successfully throwed away';
			} else {
				// dd('nema na stanju');
				$msge = 'Barcode not enough on stock!';
			}
		}


		if ($carelabel == '1') {
			// dd('c');
			$check_if_is_in_stock = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([carelabel_ki_stocks].qty) as carelabel_ki_stock
				FROM [carelabel_ki_stocks] 
				WHERE [carelabel_ki_stocks].po_id = '".$poid."' AND 
					[carelabel_ki_stocks].status != 'to_receive' 
		   	"));
			// dd((int)$check_if_is_in_stock[0]->carelabel_ki_stock);
			
			if ((int)$check_if_is_in_stock[0]->carelabel_ki_stock + $qty > 0) {
				// dd('ima na stanju');

				$carelabel_ki = new CarelabelKiStock;
				$carelabel_ki->po_id = $poid;
				$carelabel_ki->user_id = $userId;
				$carelabel_ki->ponum = $ponum;
				$carelabel_ki->size = $size;
				$carelabel_ki->qty = $qty;
				// $carelabel_ki->module = $module;
				$carelabel_ki->status = $status;
				$carelabel_ki->type = $type;
				$carelabel_ki->comment = $comment;
				$carelabel_ki->save();

				$msgs = $msgs. 'Carelabel successfully throwed away';
			} else {
				// dd('nema na stanju');
				$msge = $msge. 'Carelabel not enough on stock!';
			}
		}

		if ($barcode == '0' AND $carelabel == '0') {
			$msge = 'Barcode or Carelabel NOT SELECTED !!!';
			// return view('kikinda.error',compact('msg'));
		}

		//same
		$location_plant = 'Kikinda';

		$pos = DB::connection('sqlsrv')->select(DB::raw("SELECT pos.*
		  	FROM pos as pos
			LEFT JOIN [posummary].dbo.pro as posum ON posum.po_new = pos.po_new
			WHERE pos.closed_po = 'Open' AND posum.location_all = '".$location_plant."'
		"));
		// dd($pos);

		$posArray = ['' => ''];
		foreach ($pos as $item) {
		    $posArray[$item->po_new] = $item->po_new;
		}
		// dd($posArray);

		return view('kikinda.return_su_from_kikinda', compact('posArray','locationsArray','msge','msgs'));
	}	

}
