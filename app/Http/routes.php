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
Route::get('/barcodestocktable', 'barcodestocktableController@index');
Route::get('/barcoderequesttable', 'barcoderequesttableController@index');

// BarcodeStock
Route::get('/barcodestock', 'BarcodeStockController@index');
Route::get('/barcodestockcreatenew', 'BarcodeStockController@createnew');
Route::get('/barcodestockcreatefrommodule', 'BarcodeStockController@createfrommodule');
Route::get('/barcodestockcreateundo', 'BarcodeStockController@createundo');

Route::post('/barcodestockstorenew', 'BarcodeStockController@storenew');
Route::post('/barcodestockstorefrommodule', 'BarcodeStockController@storefrommodule');
Route::post('/barcodestockstoreundo', 'BarcodeStockController@storeundo');

// Request
Route::get('/barcoderequest', 'BarcodeRequestController@index');
Route::get('/barcoderequestcreate', 'BarcodeRequestController@create');

Route::post('/barcoderequestcreate', 'BarcodeRequestController@create');
Route::post('/barcoderequeststore', 'BarcodeRequestController@store');

// Import
Route::get('/import', 'importController@index');
Route::post('/import', 'importController@postImportPo');
Route::get('/importresult', 'importController@show');

Route::get('/importmodules', 'importModulesController@index');
Route::post('/importmodulesimport', 'importModulesController@create');

//Datatables
Route::controller('datatables', 'DatatablesController', [
    'anyData'  => 'datatables.data',
    'getIndex' => 'datatables',
]);

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