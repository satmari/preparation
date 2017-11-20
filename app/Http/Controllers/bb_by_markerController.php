<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

//use Gbrock\Table\Facades\Table;
//use Gbrock\Traits\Sortable;

use App\PrintBBLabels;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;

class bb_by_markerController extends Controller {

	public function index()
	{
		//
		return view('bb_by_marker.index');
	}

	public function search_by_marker(Request $request)
	{
		//
		$this->validate($request, ['marker' => 'required' ]);
		$input = $request->all(); 

		$marker = $input['marker'];
		// dd($marker);

		Session::set('bbarray', null);

		$inteosmarker = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
      SUBSTRING(bb.[BlueBoxNum],10,8) as bb
      ,bb.[BoxQuant] as qty
      ,bb.[CREATEDATE] as created
      ,bb.[IDMarker] as marker
      /*,bb.IntKeyPO
      ,po.SKUKEY
      ,sku.STYKEY*/
      ,sku.Variant as variant
      ,st.StyCod as style
      
	  FROM [BdkCLZG].[dbo].[CNF_BlueBox] bb
	  JOIN [BdkCLZG].[dbo].[CNF_PO] po ON po.INTKEY = bb.IntKeyPO
	  JOIN [BdkCLZG].[dbo].[CNF_SKU] sku ON sku.INTKEY = po.SKUKEY
	  JOIN [BdkCLZG].[dbo].[CNF_STYLE] st ON st.INTKEY = sku.STYKEY
	  WHERE IDMarker = '".$marker."'
	  ORDER BY sku.Variant desc
		"));

		// dd($inteosmarker);
		$bb = array();

		if ($inteosmarker != []) {
			foreach ($inteosmarker as $line) {

				$komesa = substr($line->bb,0,5);
				$bb = substr($line->bb,5,3);

				$bbprinted = DB::connection('sqlsrv')->select(DB::raw("SELECT created_at
				  FROM [preparation].[dbo].[print_b_b_labels]
				  WHERE komesa = '".$komesa."' AND bb = '".$bb."' AND marker = '".$marker."' "));

				// dd($bbprinted->printed);

				if ($bbprinted == []) {
					$printed = 0;
					$created_at = "";
				} else {
					$printed = 1;
					$created_at = $bbprinted[0]->created_at;
				}
				
	        	$bbarray = array(
	        		'komesa' => $komesa,
					'bb' => $bb,
					'marker' => $line->marker,
					'style' => $line->style,
					'variant' => $line->variant,
					'qty' => $line->qty,
					'printed' => $printed,
					'created_at' => $created_at
				);
				
				// dd($bbarray);
				// array_push($bb, $bbarray);

				Session::push('bbarray',$bbarray);
			}

			$bblist = Session::get('bbarray');
			$bb = array_map("unserialize", array_unique(array_map("serialize", $bblist)));
			// dd($bb);
			Session::set('bbarray', null);

			return view('bb_by_marker.table',compact('marker', 'bb'));			
		} else {

			return view('bb_by_marker.table',compact('marker', 'bb'));
		}


	}

	public function print_labels ($id)
	{
		
		$marker = $id;
		// dd($marker);

		$inteosmarker = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
	      SUBSTRING(bb.[BlueBoxNum],10,8) as bb
	      ,bb.[BoxQuant] as qty
	      ,bb.[CREATEDATE] as created
	      ,bb.[IDMarker] as marker
	      /*,bb.IntKeyPO
	      ,po.SKUKEY
	      ,sku.STYKEY*/
	      ,sku.Variant as variant
	      ,st.StyCod as style
	      
		  FROM [BdkCLZG].[dbo].[CNF_BlueBox] bb
		  JOIN [BdkCLZG].[dbo].[CNF_PO] po ON po.INTKEY = bb.IntKeyPO
		  JOIN [BdkCLZG].[dbo].[CNF_SKU] sku ON sku.INTKEY = po.SKUKEY
		  JOIN [BdkCLZG].[dbo].[CNF_STYLE] st ON st.INTKEY = sku.STYKEY
		  WHERE IDMarker = '".$marker."'
		  ORDER BY sku.Variant desc
		"));


		foreach ($inteosmarker as $line) {
			
			$komesa = substr($line->bb,0,5);
			$bb = substr($line->bb,5,3);
			// dd($komesa);

			/*
			$searchinprinted = DB::connection('sqlsrv')->select(DB::raw("SELECT *
  				FROM [print_b_b_labels]
  				WHERE komesa = '".$komesa."' AND bb = '".$bb."' AND marker = '".$marker."'
			"));
			*/

			// dd($searchinprinted);
			
			
			try {
			$table = new PrintBBLabels;

			$table->komesa = $komesa;
			$table->bb = $bb;
			$table->marker = $line->marker;
			$table->style = $line->style;
			$table->variant = $line->variant;
			$table->qty = $line->qty;
			$table->printed = 0; // set 0
			
			
			$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in database";
				return view('bb_by_marker.error',compact('msg'));			
			}

		}

		return view('bb_by_marker.index');
	}

	
}
