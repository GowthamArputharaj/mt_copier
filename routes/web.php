<?php

use App\Http\Controllers\CopierController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
  return view('license.layout');
})->name('main');

Route::get('copier_handle', [CopierController::class, 'CopierHandle']);

Route::middleware(['auth'])->group(function () {
  Route::get('home', [CopierController::class, 'licenses'])->name('licenses');
  Route::get('edit-license/', [CopierController::class, 'edit_license'])->name('edit_license');
  Route::get('new-license', [CopierController::class, 'new_license'])->name('new_license');
  Route::post('store-license/', [CopierController::class, 'store_license'])->name('store_license');
  Route::put('update-license/', [CopierController::class, 'update_license'])->name('update_license');
  Route::delete('delete-license/', [CopierController::class, 'delete_license'])->name('delete_license');
  // 
  Route::get('settings', [CopierController::class, 'settings'])->name('settings');
  Route::post('store-settings/', [CopierController::class, 'store_settings'])->name('store_settings');
})->middleware('auth');

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
