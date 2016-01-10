<?php 
namespace App\Http\Controllers;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Illuminate\Support\Facades\Redirect;
use Auth;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		// Roles

		//admin 1
		//preparacija 2
		//pogon 3 
		//modul 4

		$msg = '';
		$user = User::find(Auth::id());

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
		    return redirect('/request');
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
		

		return view('home', compact('msg'));
		
	}

	public function log()
	{
		return view('log');
	}

}
