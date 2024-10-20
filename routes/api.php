<?php

use App\Http\Controllers\FormController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/divisi', [FormController::class, 'divisi']);
Route::post('/store', [FormController::class, 'store']);
Route::get('/incident', [FormController::class, 'incident']);
Route::get('/incident-detail/{id}', [FormController::class, 'detail']);

// Get File API
Route::get('/storage/app/public/{directory}/{filename}', function ($directory, $filename) {
    $path = storage_path('app/public/' . $directory . '/' . $filename);

    if (!File::exists($path)) {
        return response()->json(['message' => 'File not found.'], 404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
