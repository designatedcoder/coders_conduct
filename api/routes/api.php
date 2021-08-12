<?php

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

// Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {

//     // Email Verification...
//     if (Features::enabled(Features::emailVerification())) {
//         if ($enableViews) {
//             Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
//                 ->middleware(['auth'])
//                 ->name('verification.notice');
//         }

//         Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//             ->middleware(['auth', 'signed', 'throttle:6,1'])
//             ->name('verification.verify');

//         Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//             ->middleware(['auth', 'throttle:6,1'])
//             ->name('verification.send');
//     }
// });
