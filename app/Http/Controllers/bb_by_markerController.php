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
		Session::set('bbarray', null);

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

		/*
		$inteosmarker = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
      bb.[BlueBoxNum] as bb
      ,bb.[BoxQuant] as qty
      ,bb.[CREATEDATE] as created
      ,bb.[IDMarker] as marker
      --,bb.IntKeyPO
      --,po.SKUKEY
      --,sku.STYKEY
      ,sku.Variant as variant
      ,st.StyCod as style
      
	  FROM [BdkCLZG].[dbo].[CNF_BlueBox] bb
	  JOIN [BdkCLZG].[dbo].[CNF_PO] po ON po.INTKEY = bb.IntKeyPO
	  JOIN [BdkCLZG].[dbo].[CNF_SKU] sku ON sku.INTKEY = po.SKUKEY
	  JOIN [BdkCLZG].[dbo].[CNF_STYLE] st ON st.INTKEY = sku.STYKEY
	  WHERE IDMarker = '".$marker."'
	  ORDER BY sku.Variant desc
		"));
		*/
		
		$inteosmarker = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
      bb.[BlueBoxNum] as bb
      ,bb.[BoxQuant] as qty
      ,bb.[CREATEDATE] as created
      ,bb.[IDMarker] as marker
      ,sku.Variant as variant
      ,st.StyCod as style
	  FROM [BdkCLZG].[dbo].[CNF_BlueBox] bb
	  JOIN [BdkCLZG].[dbo].[CNF_PO] po ON po.INTKEY = bb.IntKeyPO
	  JOIN [BdkCLZG].[dbo].[CNF_SKU] sku ON sku.INTKEY = po.SKUKEY
	  JOIN [BdkCLZG].[dbo].[CNF_STYLE] st ON st.INTKEY = sku.STYKEY
	  WHERE IDMarker = '".$marker."'
UNION ALL
SELECT 
      bb.[BlueBoxNum] as bb
      ,bb.[BoxQuant] as qty
      ,bb.[CREATEDATE] as created
      ,bb.[IDMarker] as marker
      ,sku.Variant as variant
      ,st.StyCod as style
FROM [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_BlueBox] as bb
	  JOIN [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_PO] as po ON po.INTKEY = bb.IntKeyPO
	  JOIN [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_SKU] as sku ON sku.INTKEY = po.SKUKEY
	  JOIN [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_STYLE]  as st ON st.INTKEY = sku.STYKEY
	  WHERE IDMarker = '".$marker."'
ORDER BY sku.Variant desc

		"));

		// dd($inteosmarker);
		$bb = array();

		if ($inteosmarker != []) {
			foreach ($inteosmarker as $line) {

				// dd($line->bb);
				// $komesa = substr($line->bb,-9,6);

				$brcrtica = substr_count($line->bb,"-");
				// echo $brcrtica." ";

				if ($brcrtica == 1)
				{
					list($one, $two) = explode('-', $line->bb);
					$komesa = $one;
					
				} else {
					$komesa = substr($line->bb,-9,6);
				}

				$bb = substr($line->bb,-3);
				// dd($bb);

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
			// Session::set('bbarray', null);

			return view('bb_by_marker.table',compact('marker', 'bb'));			
		} else {

			return view('bb_by_marker.table',compact('marker', 'bb'));
		}


	}

	public function print_labels ($id)
	{
		$marker = $id;
		// dd($marker);

		$bblist = Session::get('bbarray');
		$bb_array = array_map("unserialize", array_unique(array_map("serialize", $bblist)));

		// dd($bb_array);
		foreach ($bb_array as $array) {
			// dd($array);
			foreach ($array as $key => $value) {
				
				
				if ($key == 'komesa') {
					$komesa = $value;
				}
				if ($key == 'bb') {
					$bb = $value;
				}
				if ($key == 'marker') {
					// $marker = $value;
				}
				if ($key == 'style') {
					$style = $value;
				}
				if ($key == 'variant') {
					$variant = $value;
				}
				if ($key == 'qty') {
					$qty = $value;
				}
				if ($key == 'printed') {
					$printed = $value;
				}
			}

			// dd($bb);
			// list($color, $size) = explode('-', $variant);

			$brlinija = substr_count($variant,"-");
			// echo $brlinija." ";

			if ($brlinija == 2)
			{
				list($color, $size1, $size2) = explode('-', $variant);
				$size = $size1."-".$size2;
				// echo $color." ".$size;	
			} else {
				list($color, $size) = explode('-', $variant);
				// echo $color." ".$size;
			}

			try {
				$table = new PrintBBLabels;

				$table->komesa = $komesa;
				$table->bb = $bb;
				$table->marker = $marker;
				$table->style = $style;
				$table->variant = $variant;
				$table->size = $size;
				$table->qty = $qty;
				$table->printed = 0; // set 0
				
				
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				$msg = "Problem to save in database";
				return view('bb_by_marker.error',compact('msg'));			
			}

			Session::set('bbarray', null);
		}

		return view('bb_by_marker.index');
	}

	public function print_labels_no ($id)
	{
		
		$marker = $id;
		// dd($marker);

		$bblist = Session::get('bbarray');
		$bb_array = array_map("unserialize", array_unique(array_map("serialize", $bblist)));

		// dd($bb_array);
		foreach ($bb_array as $array) {
			// dd($array);
			foreach ($array as $key => $value) {
				
				
				if ($key == 'komesa') {
					$komesa = $value;
				}
				if ($key == 'bb') {
					$bb = $value;
				}
				if ($key == 'marker') {
					// $marker = $value;
				}
				if ($key == 'style') {
					$style = $value;
				}
				if ($key == 'variant') {
					$variant = $value;
				}
				if ($key == 'qty') {
					$qty = $value;
				}
				if ($key == 'printed') {
					$printed = $value;
				}
			}

			// dd($bb);
			// list($color, $size) = explode('-', $variant);

			$brlinija = substr_count($variant,"-");
			// echo $brlinija." ";

			if ($brlinija == 2)
			{
				list($color, $size1, $size2) = explode('-', $variant);
				$size = $size1."-".$size2;
				// echo $color." ".$size;	
			} else {
				list($color, $size) = explode('-', $variant);
				// echo $color." ".$size;
			}

			if ($printed == '0') {
				try {
					$table = new PrintBBLabels;

					$table->komesa = $komesa;
					$table->bb = $bb;
					$table->marker = $marker;
					$table->style = $style;
					$table->variant = $variant;
					$table->size = $size;
					$table->qty = $qty;
					$table->printed = 0; // set 0
					
					
					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to save in database";
					return view('bb_by_marker.error',compact('msg'));			
				}


			}

			Session::set('bbarray', null);
		}

		return view('bb_by_marker.index');
	}

	
}


/*

 "komesa" => "96729"
  "bb" => "0B3"
  "marker" => "35000"
  "style" => "MIP002"
  "variant" => "019-XL"
  "qty" => "64"
  "printed" => 0
  "created_at" => ""
  */