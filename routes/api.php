<?php

use App\Http\Controllers\Loan\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();



});

Route::middleware('auth')->group(function () {
    // Fetch all notifications for the authenticated user
    Route::get('/api/notifications', function () {
        return auth()->user()->notifications;
    });

    // Mark a specific notification as read
    Route::post('/api/notifications/{notification}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read.']);
    });
});
