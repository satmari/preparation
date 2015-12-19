<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http;
use Gbrock\Table\Facades\Table;

use App\Module;

use DB;
//use Log;

class importModulesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()	{

      

		$rows = Module::all();
		//$rows = BarcodeStock::sorted()->get();
		//$rows = MainModel::sorted()->get(); 
 		$table = Table::create($rows); // Generate a Table based on these "rows"
 		//$table = Table::create($rows, ['id','po_id','user_id','ponum','size','qty','module',/*'status',*/'type','comment','created_at']);

 		return view('importmodules.index', compact('table'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		//
		//$this->validate($request, ['inteos_bb_code' => 'required|max:10']);

		//$inteosinput = $request->all(); // change use (delete or comment user Requestl; )
		//1971107960

		//$inteosbbcode = $inteosinput['inteos_bb_code'];
		//var_dump($inteosbb);
		
		//$inteos = DB::connection('sqlsrv2')->select(DB::raw("SELECT IntKeyPO,BlueBoxNum,BoxQuant FROM [BdkCLZGtest].[dbo].[CNF_BlueBox] WHERE INTKEY = 56013339 "), array());
		// Test database
		/*$inteos = DB::connection('sqlsrv2')->select(DB::raw("SELECT [CNF_BlueBox].INTKEY,[CNF_BlueBox].IntKeyPO,[CNF_BlueBox].BlueBoxNum,[CNF_BlueBox].BoxQuant, [CNF_PO].POnum,[CNF_SKU].Variant,[CNF_SKU].ClrDesc,[CNF_STYLE].StyCod FROM [BdkCLZGtest].[dbo].[CNF_BlueBox] FULL outer join [BdkCLZGtest].[dbo].CNF_PO on [CNF_PO].INTKEY = [CNF_BlueBox].IntKeyPO FULL outer join [BdkCLZGtest].[dbo].[CNF_SKU] on [CNF_SKU].INTKEY = [CNF_PO].SKUKEY FULL outer join [BdkCLZGtest].[dbo].[CNF_STYLE] on [CNF_STYLE].INTKEY = [CNF_SKU].STYKEY WHERE [CNF_BlueBox].INTKEY =  :somevariable"), array(
			'somevariable' => $inteosbbcode,
		));*/
		// Live database

		DB::table('modules')->truncate();

		$inteosmodules = DB::connection('sqlsrv2')->select(DB::raw("SELECT DISTINCT mod.ModNam,per.Name,per.BadgeNum,per.PinCode FROM [BdkCLZG].[dbo].[CNF_Operators] as op JOIN [BdkCLZG].[dbo].[WEA_PersData] as per ON op.IntKeyPers = per.PersNum JOIN [BdkCLZG].[dbo].[CNF_Modules] as mod ON op.Module = mod.Module WHERE  (op.PersTyp = 2) and (per.FlgAct = 1) ORDER BY mod.ModNam"));

		foreach ($inteosmodules as $row) {
    		$modName = $row->ModNam;
    		$modName = str_replace(' ', '', $modName);
    		$group = substr($modName, 0, 1);
    		$leader_num = $row->BadgeNum;
    		$pin = $row->PinCode;


    		$leader = $row->Name;
    		$badge = $row->BadgeNum;

    		
    		//echo $modName." ".$leader." ".$badge."<br />";
    		//dd($modName." ".$leader." ".$badge);

    		//try {
    			$module = new Module;
    			$module->module=$modName;
    			$module->group=$group;
    			$module->leader=$leader;
    			$module->leader_num=$leader_num;
    			$module->leader_pin=$pin;
	
	   			$module->save();	
	   		//}
			/*
			catch (\Illuminate\Database\QueryException $e) {
				//$msg = "Problem to save in database";
				//return view('BarcodeRequest.error',compact('msg'));			
			}
			*/
			
   			
    	}

    	return view('importmodules.success');

		/*
		if ($inteosmodules) {
			//continue
		} else {
        	//$validator->errors()->add('field', 'Something is wrong with this field!');
        	
        	//Log::error('Cannot find BB in Inteos');
        	//return view('inteosdb.error');
    	}
    	*/
    }	

}


