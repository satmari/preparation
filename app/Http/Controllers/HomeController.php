<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

use App\BarcodeRequest;
use DB;


class HomeController extends Controller {

	public function __construct()
	{
	    $this->middleware('auth');
	}

	public function index() {
		
		// $this->middleware('auth'); // Only apply middleware here

		// Roles

		//admin 1
		//preparacija 2
		//pogon 3 
		//modul 4
		

		$msg = '';
		$user = User::find(Auth::id());

		if (!$user) {
		    return redirect('auth/login')->with('error', 'Please log in first.');
		}

		// if ($user->is('admin')) { // you can pass an id or slug
		//     // or alternatively $user->hasRole('admin')
		//     $msg = "I am Admin";
		// }
		
		// if ($user->isAdmin()) {
		//     $msg = $msg + " admin";
		// }

		if ($user->is('admin')) { 
		    // if user has at least one role
		    $msg = "Hi admin";
		}
		if ($user->is('preparacija')) { 
		    // if user has at least one role
		    $msg = "Pa gde ste preparacija?";
		    //return redirect('/maintable');
		}
		if ($user->is('modul')) { 
		    // if user has at least one role
		    $msg = "Hi modul";
		    return redirect('/lines');
		    // return redirect('/request');
		}
		if ($user->is('kikinda')) { 
		    // if user has at least one role
		    $msg = "Hi preparation kikinda";
		    return redirect('/kikinda');
		}
		if ($user->is('senta')) { 
		    // if user has at least one role
		    $msg = "Hi preparation senta";
		    return redirect('/senta');
		}

		// if ($user->is('admin|moderator', true)) {
		//     /*
		//     | Or alternatively:
		//     | $user->is('admin, moderator', true), $user->is(['admin', 'moderator'], true),
		//     | $user->isAll('admin|moderator'), $user->isAll('admin, moderator'), $user->isAll(['admin', 'moderator'])
		//     */
		
		//     // if user has all roles
		//     $msg = $msg + "admin|mmoderator|true";

		// }
		
		 //Levels
		
		// if ($user->level() == 3) {
		//     $msg = "Level = 3";
		// }
		

		// //in view
		
		// @role('admin') // @if(Auth::check() && Auth::user()->is('admin'))
		// 	// user is admin
		// @endrole
		
		// @if(Auth::check() && Auth::user()->level() >= 2)
			// user has level 2 or higher
		// @endiff

		//Statistic

		// $today = date("Y-m-d"); 
		// var_dump($today);
		// $barcoderequests = DB::connection('sqlsrv')->select(DB::raw("SELECT count(id) FROM barcode_requests WHERE status = 'error' AND created_at > '".$today."'"));
		// var_dump($barcoderequests);

		return view('home', compact('msg'));
		
	}

	public function log() {
		return view('log');
	}

}
