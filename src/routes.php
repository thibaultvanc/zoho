<?php
Route::group(['namespace' => 'Organit\Zoho\Controllers', 'prefix'=>'organit/zoho'], function(){

  Route::get('/', ['as'=>'main', 'uses'=>'ZohoController@index']);
});
