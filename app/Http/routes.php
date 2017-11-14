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

Route::get('/addadmin', 'addRollController@addadmin');
Route::get('/addpreparation', 'addRollController@addpreparation');
Route::get('/addmodule', 'addRollController@addmodule');

Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::get('log', 'HomeController@log');

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
Route::get('/barcoderequesttablep/edit/{id}', 'barcoderequesttableController@editp');
Route::post('/barcoderequesttable-error/{id}', 'barcoderequesttableController@error');
Route::patch('/barcoderequesttable/{id}', 'barcoderequesttableController@update');
Route::patch('/barcoderequesttablep/{id}', 'barcoderequesttableController@updatep');
Route::get('/barcoderequesttablelog', 'barcoderequesttableController@log');
Route::get('/barcoderequesttablelogmodule', 'barcoderequesttableController@logmodule');

Route::get('/carelabelrequesttable', 'carelabelrequesttableController@index');
Route::get('/carelabelrequesttable/edit/{id}', 'carelabelrequesttableController@edit');
Route::get('/carelabelrequesttablep/edit/{id}', 'carelabelrequesttableController@editp');
Route::post('/carelabelrequesttable-error/{id}', 'carelabelrequesttableController@error');
Route::patch('/carelabelrequesttable/{id}', 'carelabelrequesttableController@update');
Route::patch('/carelabelrequesttablep/{id}', 'carelabelrequesttableController@updatep');
Route::get('/carelabelrequesttablelog', 'carelabelrequesttableController@log');
Route::get('/carelabelrequesttablelogmodule', 'carelabelrequesttableController@logmodule');

Route::get('/secondqrequesttable', 'secondqulityrequestController@index');
Route::get('/secondqrequesttable/edit/{id}', 'secondqulityrequestController@edit');
Route::get('/secondqrequesttablep/edit/{id}', 'secondqulityrequestController@editp');
Route::post('/secondqrequesttable-error/{id}', 'secondqulityrequestController@error');
Route::post('/secondqrequesttable-confirm/{id}', 'secondqulityrequestController@confirm');
Route::patch('/secondqrequesttable/{id}', 'secondqulityrequestController@update');
Route::patch('/secondqrequesttablep/{id}', 'secondqulityrequestController@updatep');
Route::get('/secondqrequesttablelog', 'secondqulityrequestController@log');
Route::get('/secondqrequesttablelogmodule', 'secondqulityrequestController@logmodule');
Route::get('/secondqrequestupdate', 'secondqulityrequestController@secondqrequestupdate');
Route::get('/secondqrequestupdatenav', 'secondqulityrequestController@secondqrequestupdatenav');

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

// Stock
Route::get('/stock', 'StockController@index');
Route::get('/stockcreatenew', 'StockController@createnew');
Route::get('/stockcreatefrommodule', 'StockController@createfrommodule');
Route::get('/stockcreateundo', 'StockController@createundo');
Route::post('/stockstorenew', 'StockController@storenew');
Route::post('/stockstorefrommodule', 'StockController@storefrommodule');
Route::post('/stockstoreundo', 'StockController@storeundo');

// Request
Route::get('/request', 'RequestController@index');
Route::get('/requestcheck', 'RequestController@check');
Route::get('/requestselect', 'RequestController@select');
Route::get('/requestcreate', 'RequestController@create');
Route::get('/requeststore', 'RequestController@store');
Route::post('/requestcheck', 'RequestController@check');
Route::post('/requestcreate', 'RequestController@create');
Route::post('/requeststore', 'RequestController@store');

Route::get('/requestcreatep', 'RequestController@createp');
Route::get('/requeststorep', 'RequestController@storep');
Route::post('/requeststorep', 'RequestController@storep');

Route::get('/requestcreatesec', 'RequestController@createsec');
Route::get('/requeststoresec', 'RequestController@storesec');
Route::post('/requeststoresec', 'RequestController@storesec');

// Import Po
Route::get('/import', 'importController@index');
Route::get('/importresult', 'importController@show');
Route::post('/import', 'importController@postImportPo');
Route::post('/import2', 'importController@postImportUser');
Route::post('/import3', 'importController@postImportRoll');
Route::post('/import4', 'importController@postImportUserRole');

// Import Modules
Route::get('/importmodules', 'importModulesController@index');
Route::post('/importmodulesimport', 'importModulesController@create');
// Clear data form Issuing table (Navision)
Route::get('/import/deleteIssueTable', 'importController@deleteIssueTable');

//Cartiglio
Route::get('/cartiglio', 'cartiglioController@index');

// BB by marker
Route::get('/bb_by_marker', 'bb_by_markerController@index');
Route::post('/search_by_marker', 'bb_by_markerController@search_by_marker');
Route::get('/print_labels/{id}', 'bb_by_markerController@print_labels');


Route::any('getpodata', function() {
	$term = Input::get('term');

	$data = DB::connection('sqlsrv')->table('pos')->distinct()->select('po')->where('po','LIKE', $term.'%')->where('closed_po','=','Open')->groupBy('po')->take(10)->get();
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