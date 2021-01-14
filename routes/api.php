<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('locations', 'Api\LocationController', ['only' => ['index', 'store', 'destroy', 'update']]);
Route::resource('items', 'Api\ItemController', ['only' => ['index', 'store', 'destroy', 'update']]);
Route::resource('categories', 'Api\CategoryController', ['only' => ['index', 'store', 'destroy', 'update']]);
Route::get('price-product-report-items', 'Api\ReportController@productPriceReportItemsData');
Route::get('price-product-report/{price}', 'Api\ReportController@productPriceReportData');