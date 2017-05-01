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

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

//Route::get('/admin/add_product', function () {
//    return view('admin.add_product');
//})->name('home');

Route::get('/admin', function () {
    return view('admin.action');
});


Route::get('/admin/add_product', [
    'uses' => 'ProductController@index',
    'as' => 'add_product',
    'middleware' => 'auth'
]);

Route::post('/admin/save_product', [
    'uses' => 'ProductController@saveProduct',
    'as' => 'product.save',
    'middleware' => 'auth'
]);

Route::get('/admin/add_category', [
    'uses' => 'CategoryController@index',
    'as' => 'add_category',
    'middleware' => 'auth'
]);

Route::post('/admin/save_category', [
    'uses' => 'CategoryController@saveCategory',
    'as' => 'category.save',
    'middleware' => 'auth'
]);

Route::get('/admin/category/delete/{category_id}', [
    'uses' => 'CategoryController@deleteCategory',
    'as' => 'category.delete',
    'middleware' => 'auth'
]);
