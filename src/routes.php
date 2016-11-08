<?php
Route::group(['namespace' => 'Organit\Zoho\Controllers', 'prefix'=>'organit/zoho'], function(){

  Route::get('/', ['as'=>'main', 'uses'=>'ZohoController@index']);

  // 
  // Route::get('organizations', 'OrganizationController@index');
  // Route::get('contacts', 'ContactController@index');
  //







  Route::get('organizations', 'OrganizationController@index');
  Route::post('organization', 'OrganizationController@store');
  Route::get('organization/{item_id}', 'OrganizationController@get');
  Route::put('organization/{item_id}', 'OrganizationController@update');
  Route::delete('organization/{item_id}', 'OrganizationController@delete');


  Route::get('contacts', 'ContactController@index');
  Route::post('contact', 'ContactController@store');
  Route::get('contact/{item_id}', 'ContactController@get');
  Route::put('contact/{item_id}', 'ContactController@update');
  Route::delete('contact/{item_id}', 'ContactController@delete');

  Route::get('{organization}/items', 'ItemController@index');
  Route::post('{organization}/items', 'ItemController@store');
  Route::get('{organization}/items/{item_id}', 'ItemController@get');
  Route::put('{organization}/items/{item_id}', 'ItemController@update');
  Route::delete('items{organization}//{item_id}', 'ItemController@delete');

  Route::get('{organization}/invoices', 'InvoiceController@index');
  Route::get('{organization}/invoices/filter/{filterName}', 'InvoiceController@filtered');
  Route::get('{organization}/invoices/{invoice_id}', 'InvoiceController@get');
  Route::put('{organization}/invoices/{invoice_id}', 'InvoiceController@update');
  Route::delete('{organization}/invoices/{invoice_id}', 'InvoiceController@delete');

  Route::get('invoice/{invoice}/transactions', 'TransactionController@index');




});
