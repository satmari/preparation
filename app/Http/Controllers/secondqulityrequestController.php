<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

//use Gbrock\Table\Facades\Table;
//use Gbrock\Traits\Sortable;

use App\SecondQRequest;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;


class secondqulityrequestController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
 		$request_q = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM secondq_requests
																WHERE 
																(CAST(created_at AS DATE) = CAST(GETDATE() AS DATE) AND status != 'error')
																OR
    															status = 'pending'
    															ORDER BY status desc,created_at asc"
    														  ));

		return view('secondqrequesttable.index', compact('request_q'));
	}

	public function log()
	{
		$request_q = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM secondq_requests ORDER BY created_at desc"));
 		return view('secondqrequesttable.log', compact('request_q'));
	}
	public function logmodule()
	{
		$module = Auth::user()->name;
		$request_q = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM secondq_requests
															   WHERE module = '".$module."'
															   ORDER BY created_at desc
     														"));
 		return view('secondqrequesttable.log', compact('request_q'));
	}

	public function edit($id) {

		$request_q = SecondQRequest::findOrFail($id);		
		return view('secondqrequesttable.edit', compact('request_q'));
	}
	public function editp($id) {

		$request_q = SecondQRequest::findOrFail($id);		
		return view('secondqrequesttable.editp', compact('request_q'));
	}
	
	public function update($id, Request $request) {

		$request_q = SecondQRequest::findOrFail($id);		
		//$request_q->update($request->all());

		$input = $request->all(); 
		//dd($input);

		//$request_q->id = $input['id'];
		$request_q->po_id = $input['po_id'];
		$request_q->user_id = $input['user_id'];
		$request_q->ponum = $input['ponum'];
		$request_q->size = $input['size'];
		
		$qty = $input['qty'];
		$status = $input['status'];

		if (($qty <= 0) OR ($qty == NULL)) {

			if ($status == 'error'){
				$request_q->qty = NULL;			
				$request_q->status = 'error';	
			} else if ($status == 'pending') {
				$request_q->qty = NULL;			
				$request_q->status = 'pending';	
			} else {
				$request_q->qty = NULL;			
				$request_q->status = 'pending';	
			}

		} else if ($qty > 0) {				

			if ($status == 'error'){
				$request_q->qty = $qty;			
				$request_q->status = 'error';	
			} else if ($status == 'pending') {
				$request_q->qty = $qty;			
				$request_q->status = 'pending';	
			} else {
				$request_q->qty = $qty;			
				$request_q->status = 'confirmed';	
			}
		}
	
		$request_q->module = $input['module'];
		
		$request_q->leader = $input['leader'];
		$request_q->type = $input['type'];
		$request_q->comment = $input['comment'];
		$request_q->save();

		//return view('main.index');
		return Redirect::to('/secondqrequesttable');
	}

	public function updatep($id, Request $request) {

		$request_q = SecondQRequest::findOrFail($id);
		//$request_q->update($request->all());

		$input = $request->all(); 
		//dd($input);

		$request_q->id = $input['id'];
		//$request_q->po_id = $input['po_id'];
		//$request_q->user_id = $input['user_id'];
		//$request_q->ponum = $input['ponum'];
		//$request_q->size = $input['size'];
		
		$qty = $input['qty'];
		$status = $input['status'];

		if (($qty <= 0) OR ($qty == NULL)) {

			if ($status == 'error'){
				$request_q->qty = NULL;			
				$request_q->status = 'error';	
			} else if ($status == 'pending') {
				$request_q->qty = NULL;			
				$request_q->status = 'pending';	
			} else {
				$request_q->qty = NULL;			
				$request_q->status = 'pending';
			}

		} else if ($qty > 0) {				

			if ($status == 'error'){
				$request_q->qty = $qty;			
				$request_q->status = 'error';	
			} else if ($status == 'pending') {
				$request_q->qty = $qty;			
				$request_q->status = 'pending';	
			} else {
				$request_q->qty = $qty;			
				$request_q->status = 'confirmed';
			}
		}
	
		//$request_q->module = $input['module'];
		//$request_q->leader = $input['leader'];
		//$request_q->type = $input['type'];
		$request_q->comment = $input['comment'];
		$request_q->save();

		//return view('main.index');
		return Redirect::to('/secondqrequesttable');
	}

	public function error($id, Request $request) {

		$request_q = SecondQRequest::findOrFail($id);
		//$request_q->update($request->all());

		$input = $request->all(); 
		//dd($input);

		$request_q->id = $input['id'];
		$request_q->qty = NULL;
		$request_q->status = 'error';
		$request_q->save();

		//return view('main.index');
		return Redirect::to('/secondqrequesttable');
	}

	public function confirm($id, Request $request) {

		$request_q = SecondQRequest::findOrFail($id);
		//$request_q->update($request->all());

		$input = $request->all(); 
		//dd($input);

		$request_q->id = $input['id'];
		$request_q->qty;
		$request_q->status = 'confirmed';
		$request_q->save();

		//return view('main.index');
		return Redirect::to('/secondqrequesttable');
	}

	//NOT NEEDED 
	public function secondqrequestupdate(Request $request) {

		$request_q = DB::connection('sqlsrv')->select(DB::raw("SELECT id,ponum FROM secondq_requests"));
		//dd($request_q);

		foreach($request_q as $row) {

			$id = $row->id;
			$po = $row->ponum;
			
			//dd($id." ".$po." ".$size);
			$po = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT style,color FROM pos WHERE po=".$po));

			$style = $po['0']->style;
			$color = $po['0']->color;

			$update = SecondQRequest::findOrFail($id);
			
			$update->style = $style;
			$update->color = $color;
			$update->save();
		}

		return Redirect::to('/secondqrequesttable');	
	}

	public function secondqrequestupdatenav() {

		$request_q = DB::connection('sqlsrv')->select(DB::raw("SELECT id,style,color,size,status FROM secondq_requests WHERE status = 'pending'"));
		// dd($request_q);

		foreach($request_q as $row) {

			$id = $row->id;
			$style = $row->style;
			$color = $row->color;
			$size = $row->size;
			$status = $row->status;
			
			try {
				
				$po = DB::connection('sqlsrv3')->select(DB::raw("SELECT [Item No_]
																      ,[Color]
																      ,[TG]
																      ,[Materiale] as materiale
																      ,[Description Model] as des
																      ,[TG2] as tg2
																      ,[Commersial Color code] as ccc
																      ,[Color decstionption] as cd
																      ,[Barcode] as barcode
																  FROM [Gordon_LIVE].[dbo].[GORDON\$Barocde Table Quality]
																  WHERE [Item No_] = '".$style."' 
																  	AND [Color] = '".$color."' 
																  	AND [TG] = '".$size."'"));

				// dd($po);

				if(isset($po[0]->materiale) OR isset($po[0]->des) OR isset($po[0]->tg2) OR isset($po[0]->ccc) OR isset($po[0]->cd) OR isset($po[0]->barcode)) {
					// continnue
				} else {
					$msg = "Problem to find in table: ".$style." ". $color." ". $size." , salji mail u Italiju, i zovi Dekija :)" ;
					return view('secondqrequesttable.error',compact('msg'));
				}

				$materiale = $po[0]->materiale;
				$desc = $po[0]->des;
				$tg2 = $po[0]->tg2;
				$ccc = $po[0]->ccc;
				$cd = $po[0]->cd;
				$barcode = $po[0]->barcode;
   			
					try {

					$update = SecondQRequest::findOrFail($id);
					
					$update->materiale = $materiale;
					$update->desc = $desc;
					$update->tg2 = $tg2;
					$update->ccc = $ccc;
					$update->cd = $cd;
					$update->barcode = $barcode;
					$update->save();

					}
						catch (\Illuminate\Database\QueryException $e) {
							$msg = "Problem to save in table";
							return view('secondqrequesttable.error',compact('msg'));
					}
			}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem";
					return view('secondqrequesttable.error',compact('msg'));
			}
		}

		return Redirect::to('/secondqrequesttable');	
	}

}
