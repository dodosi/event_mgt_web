<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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


   Route::get('login', function(){
    return view('login');
   });
  
   Route::get('logout', array('uses' => 'App\Http\Controllers\LoginController@doLogout'));
// route to show the login form
Route::get('mlogin', array('uses' => 'App\Http\Controllers\LoginController@showLogin'));
Route::get('register', function(){
   return view('register');
  });
// route to process the form
Route::post('mlogin', array('uses' => 'App\Http\Controllers\LoginController@doLogin')); 
Route::group(['middleware'=>['AuthCheck']],function(){

   Route::get('home/{page}', function ($page) {
      return view('app', ['page'=>$page,'age'=>3]);
  });
  Route:: get ('/', function () {
      return view('app', ['page'=>'dashboard','age'=>3]);
   });
   Route::get('page', function(){
      return view('page');
     });
   Route::get('home', function(){
      return view('home');
     });

   Route::get('iframe', function(){
      return view('app', ['page'=>'iframe','age'=>3]);
     });
   
   Route::get('index', function(){
      return view('app', ['page'=>'dashboard','age'=>3]);
     });
});