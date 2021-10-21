<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Device;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    $MAC = exec("/sbin/ip addr|/bin/grep link/ether | /bin/awk '{print $2}'");
    return view('dashboard', [
        "MAC" => $MAC
    ]);
})->middleware(['auth'])->name('dashboard');

Route::post("/device/store", [Device::class, 'store']);
Route::get("/dashboard/pending", [Device::class, 'afterRegist']);

Route::get('/admin', function () {
    return view('admin');
});

require __DIR__.'/auth.php';
