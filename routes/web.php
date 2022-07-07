<?php

use Illuminate\Support\Facades\Route;
use App\Models\Site;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ViewsController;

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
Route::get('/dashboard/settings/{site}', [ViewsController::class, 'siteSettings'])->middleware(['auth'])->name('site.settings');
Route::post('/update/{site}', [ViewsController::class, 'siteUpdate'])->middleware(['auth'])->name('site.update');

require __DIR__.'/auth.php';

Route::get('/{site}', [SiteController::class, 'view'])->name('site.view');
