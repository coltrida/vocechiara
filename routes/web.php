<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontController@index')->name('index');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/getSpedizioni', 'HomeController@getSpedizioni')->name('home.getSpedizioni');
Route::get('/getFatture', 'HomeController@getFatture')->name('home.getFatture');

//-------------------CLIENT----------------------------//
Route::resource('clients', 'ClientController');
Route::get('/getClients', 'ClientController@getClients')->name('clients.getClients');
Route::get('/info/{client}', 'ClientController@show')->name('clients.info');
Route::get('/destroycall/{client}', 'ClientController@destroyCall')->name('clients.destroy.call');
Route::get('/destroycallauto/{client}', 'ClientController@destroyCallAuto')->name('clients.destroy.auto');
Route::get('/phone/{client}', 'ClientController@phone')->name('clients.phone');
Route::post('/phone', 'ClientController@phonestore')->name('clients.phone.store');

//-------------------FONTI----------------------------//
Route::resource('fonts', 'FontController');
Route::get('/getFonts', 'FontController@getFonts')->name('fonts.getFonts');

//-------------------NOTE----------------------------//
Route::post('/nota', 'NoteController@notastore')->name('nota.store');
Route::get('/getNotes/{client}', 'NoteController@getNotes')->name('nota.getNotes');
Route::delete('/eliminanota/{nota}', 'NoteController@eliminaNota')->name('nota.elimina');

//-------------------PRODUCT----------------------------//
Route::resource('products', 'ProductController');
/*Route::get('/createCon', 'ProductController@createCon')->name('products.createCon');*/
Route::get('/richiediProdotti', 'ProductController@richiedi')->name('products.richiedi');
Route::get('/prodottiRichiesti/{id}', 'ProductController@getProdottiRichiesti')->name('products.prodottiRichiesti');
Route::post('/storeRichiesti', 'ProductController@storeRichiedi')->name('products.storeRichiesti');
Route::get('/altroMagazzino/{filiale}', 'ProductController@altroindex')->name('products.altroMagazzino');
Route::get('/getProducts', 'ProductController@getProducts')->name('products.getProducts');
Route::get('/getAltriProducts/{filiale}', 'ProductController@getAltriProducts')->name('products.getAltriProducts');
Route::post('/assegnaprova', 'ProductController@assegnaProva')->name('products.assegnaProva');
Route::post('/storeCon', 'ProductController@storeCon')->name('products.storeCon');
/*Route::post('products/assegnadestinazione', 'ProductController@assegnaDestinazione')->name('products.assegnaDestinazione');*/

//-------------------LISTINO----------------------------//
Route::resource('listino', 'ListinoController');
Route::get('/getListino', 'ListinoController@getListino')->name('listino.getListino');
Route::get('/getIdListino{descrizione}', 'ListinoController@getIdListino')->name('listino.getIdListino');
Route::get('/getPrezzo{descrizione}', 'ListinoController@getPrezzo')->name('listino.getPrezzo');
Route::get('/getProdotti{categoria}', 'ListinoController@getProdotti')->name('listino.getProdotti');

//-------------------PROVE----------------------------//
Route::resource('prove', 'ProveController');
Route::get('/infoprova/{id}', 'ProveController@info')->name('prove.info');
Route::get('/provesel/{id}', 'ProveController@show')->name('provesel.show');
Route::get('/getProve/{id}', 'ProveController@getProve')->name('prove.getProve');
Route::get('/getMatricole/{id}', 'ProveController@getMatricole')->name('prove.getMatricole');
Route::get('/getMatricolePresenti/{id}', 'ProveController@getMatricolepresenti')->name('prove.getMatricolePresenti');
Route::get('/vendita/{prova}', 'ProveController@vendita')->name('prove.vendita');
Route::get('/reso/{prova}', 'ProveController@reso')->name('prove.reso');
Route::get('/annulla/{prova}', 'ProveController@annulla')->name('prove.annulla');
Route::get('/provegetClients', 'ProveController@getClients')->name('prove.getClients');

//-------------------FILIALI----------------------------//
Route::resource('/filiale', 'FilialeController');
Route::get('/getFiliali', 'FilialeController@getFiliali')->name('filiali.getFiliali');

//-------------------FATTURE----------------------------//
Route::get('/listafatture/{filiale}', 'FattureController@index')->name('fatture.index');
Route::get('/getlistaFatture/{filiale}', 'FattureController@getFatture')->name('fatture.getFatture');
Route::get('/fatture/infoFattura/{id}', 'FattureController@info')->name('fatture.infoFattura');
Route::post('/assegnaPagamento', 'FattureController@assegnapagamento')->name('fatture.assegnaPagamento');

//-------------------DDT----------------------------//
Route::get('/listaddt/{filiale}', 'DdtController@index')->name('ddt.index');

//-----------------------PDF----------------------------//
Route::get('/copiacomm/{prova}', 'PdfController@copiacomm')->name('prove.copiacomm');
Route::get('/fattura/{prova}', 'PdfController@fattura')->name('prove.fattura');
Route::post('/ddt/{prova}', 'PdfController@ddt')->name('prove.ddt');

//-------------------AUDIOPROTESISTI----------------------------//
Route::get('/audio', 'AudiopController@index')->name('audio.index');
Route::get('/getAudio', 'AudiopController@getAudio')->name('audio.getAudio');
Route::get('/audio/edit/{user}', 'AudiopController@edit')->name('audio.edit');
Route::delete('/audio/delete/{user}', 'AudiopController@destroy')->name('audio.delete');
Route::patch('/audio/update/{user}', 'AudiopController@update')->name('audio.update');

//-------------------STATISTICHE----------------------------//
Route::get('/statistiche/mese/', 'ProveController@mese')->name('statistiche.mese');
Route::get('/statistiche/anno/', 'ProveController@anno')->name('statistiche.anno');

//-------------------SPECIAL----------------------------//
Route::get('/resetDB', 'HomeController@resetDB')->name('resetDB');
Route::get('/import', 'ClientController@import')->name('clients.import');
Route::get('/importProduct', 'ProductController@import')->name('product.import');