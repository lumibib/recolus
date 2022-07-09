<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\ViewsController;
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

Route::get('/', [ViewsController::class, 'welcome'])->name('welcome');

Route::get('/dashboard', [ViewsController::class, 'welcome'])->middleware(['auth'])->name('dashboard');
Route::get('/dashboard/create', [ViewsController::class, 'siteCreate'])->middleware(['auth'])->name('site.create');
Route::get('/dashboard/settings/{site}', [ViewsController::class, 'siteSettings'])->middleware(['auth'])->name('site.settings');
Route::post('/store', [ViewsController::class, 'siteStore'])->middleware(['auth'])->name('site.store');
Route::post('/update/{site}', [ViewsController::class, 'siteUpdate'])->middleware(['auth'])->name('site.update');
Route::post('/delete/{site}', [ViewsController::class, 'siteDelete'])->middleware(['auth'])->name('site.delete');

require __DIR__.'/auth.php';

Route::get('/{site}', [SiteController::class, 'view'])->name('site.view');
