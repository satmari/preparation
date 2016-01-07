<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/add', 'WelcomeController@index');
Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/maintable', 'maintableController@index');

Route::get('/main', 'mainController@index');
Route::get('/main/edit/{id}', 'mainController@edit');
Route::patch('/main/{id}', 'mainController@update');

// Log table
Route::get('/barcodestocktable', 'barcodestocktableController@index');
Route::get('/carelabelstocktable', 'carelabelstocktableController@index');

Route::get('/barcoderequesttable', 'barcoderequesttableController@index');
Route::get('/barcoderequesttable/edit/{id}', 'barcoderequesttableController@edit');
Route::patch('/barcoderequesttable/{id}', 'barcoderequesttableController@update');

Route::get('/carelabelrequesttable', 'carelabelrequesttableController@index');
Route::get('/carelabelrequesttable/edit/{id}', 'carelabelrequesttableController@edit');
Route::patch('/carelabelrequesttable/{id}', 'carelabelrequesttableController@update');

// BarcodeStock
Route::get('/barcodestock', 'BarcodeStockController@index');
Route::get('/barcodestockcreatenew', 'BarcodeStockController@createnew');
Route::get('/barcodestockcreatefrommodule', 'BarcodeStockController@createfrommodule');
Route::get('/barcodestockcreateundo', 'BarcodeStockController@createundo');
Route::post('/barcodestockstorenew', 'BarcodeStockController@storenew');
Route::post('/barcodestockstorefrommodule', 'BarcodeStockController@storefrommodule');
Route::post('/barcodestockstoreundo', 'BarcodeStockController@storeundo');

// CarelabelStock
Route::get('/carelabelstock', 'CarelabelStockController@index');
Route::get('/carelabelstockcreatenew', 'CarelabelStockController@createnew');
Route::get('/carelabelstockcreatefrommodule', 'CarelabelStockController@createfrommodule');
Route::get('/carelabelstockcreateundo', 'CarelabelStockController@createundo');
Route::post('/carelabelstockstorenew', 'CarelabelStockController@storenew');
Route::post('/carelabelstockstorefrommodule', 'CarelabelStockController@storefrommodule');
Route::post('/carelabelstockstoreundo', 'CarelabelStockController@storeundo');

// Request
Route::get('/request', 'RequestController@index');
Route::get('/requestcreate', 'RequestController@create');
Route::get('/requeststore', 'RequestController@store');
Route::post('/requestcreate', 'RequestController@create');
Route::post('/requeststore', 'RequestController@store');

// Import Po
Route::get('/import', 'importController@index');
Route::get('/importresult', 'importController@show');
Route::post('/import', 'importController@postImportPo');
Route::post('/import2', 'importController@postImportUser');

 // Import Modules
Route::get('/importmodules', 'importModulesController@index');
Route::post('/importmodulesimport', 'importModulesController@create');


Route::any('getpodata', function() {
	$term = Input::get('term');

	$data = DB::connection('sqlsrv')->table('pos')->distinct()->select('po')->where('po','LIKE', $term.'%')->where('closed_po','=',0)->groupBy('po')->take(10)->get();
	foreach ($data as $v) {
		$retun_array[] = array('value' => $v->po);
	}
return Response::json($retun_array);
});
Route::any('getmoduledata', function() {
	$term = Input::get('term');

	$data = DB::connection('sqlsrv')->table('modules')->distinct()->select('module')->where('module','LIKE', $term.'%')->groupBy('module')->take(15)->get();
	foreach ($data as $v) {
		$retun_array[] = array('value' => $v->module);
	}
return Response::json($retun_array);
});