<?php 
namespace App\Http\Controllers;

use App\User;
use Bican\Roles\Models\Role;
use Auth;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	/*
	public function __construct()
	{
		$this->middleware('guest');
	}
	*/

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{	
		//add roles
		/*
		$adminRole = Role::create([
		    'name' => 'Admin',
		    'slug' => 'admin',
		    'description' => '', // optional
		    'level' => 1, // optional, set to 1 by default
		]);

		$preparacijaRole = Role::create([
		    'name' => 'Preparacija',
		    'slug' => 'preparacija',
		    'description' => '', // optional
		    'level' => 2, // optional, set to 1 by default
		]);

		$pogonRole = Role::create([
		    'name' => 'Pogon',
		    'slug' => 'pogon',
		    'description' => '', // optional
		    'level' => 3, // optional, set to 1 by default
		]);

		$modulRole = Role::create([
		    'name' => 'Modul',
		    'slug' => 'modul',
		    'description' => '', // optional
		    'level' => 4, // optional, set to 1 by default
		]);
		*/

		// attach roles
		//find role
		//$role = Role::find(1); // when you know id
		$role = Role::where('level', 1)->first(); // when you want to find

		//Admin 1
		//Preparacija 2
		//Pogon 3
		//Modul 4

		//find user
		//$id = 1;
		$id = Auth::id();

		$user = User::find($id);
		$user->attachRole($role); // you can pass whole object, or just an id

		// deatach roles
		//$user->detachRole($adminRole); // in case you want to detach role
		//$user->detachAllRoles(); // in case you want to detach all roles


		return view('welcome');
	}

}
