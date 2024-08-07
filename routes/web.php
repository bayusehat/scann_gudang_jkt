<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\ItemController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/',[LoginController::class,'index'])->name('login');
Route::post('/dologin',[LoginController::class,'actionLogin']);
Route::get('/dologout',[LoginController::class,'actionLogout']);
Route::get('/create',[LoginController::class,'createUser']);
Route::middleware(['auth'])->group(function () {
    Route::get('/home',[HomeController::class,'index']);
    Route::get('/in',[ActionController::class,'indexIn']);
    Route::get('/out',[ActionController::class, 'indexOut']);
    Route::get('/in/load',[ActionController::class,'loadIn']);
    Route::get('/out/load',[ActionController::class,'loadOut']);
    Route::post('/autoadd',[ActionController::class,'auto_add']);


    Route::get('/item',[ItemController::class,'index']);
    Route::post('/item/add',[ItemController::class,'auto_add']);
    Route::get('/item/load',[ItemController::class,'loadItem']);
    Route::get('/item/scan',[ItemController::class,'item_scan']);
    Route::get('/item/sold/load',[ItemController::class,'loadSoldItem']);

    Route::post('item/insert',[ItemController::class, 'insert']);
    Route::get('item/edit/{id}',[ItemController::class, 'edit']);
    Route::post('item/update/{id}',[ItemController::class,'update']);
    Route::get('item/delete/{id}',[ItemController::class,'destroy']);

    Route::get('item/delete/{id}/sold',[ItemController::class, 'destroyItemScan']);
    Route::post('item/import',[ItemController::class,'importItem']);
    Route::get('item/stok',[ItemController::class, 'report_stok_view']);
    Route::get('item/stok/list',[ItemController::class, 'reportStok']);
});
