    <?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfilePictureController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

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

// User API //
Route::apiResource('users', UserController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [UserController::class, 'show']);
    Route::put('/update/{id}', [UserController::class, 'update']);
    Route::post('/upload-profile-picture', [ProfilePictureController::class, 'upload']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Product API //
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);
});

// Cart API //
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart/user', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addItem']);
    Route::put('/cart/update/{cartLineId}', [CartController::class, 'updateItem']);
    Route::delete('/cart/delete/{cartLineId}', [CartController::class, 'deleteItem']);
});

// Review Api //
Route::get('/reviews', [ReviewController::class, 'index']); // Get all reviews
Route::get('/reviews/{id}', [ReviewController::class, 'show']); // Get a single review
Route::post('/reviews', [ReviewController::class, 'store']); // Create a review
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']); // Delete a review


// Message Api //
Route::get('/messages', [MessageController::class, 'index']);          // Get all messages
Route::get('/messages/{id}', [MessageController::class, 'show']);     // Get a specific message
Route::post('/messages', [MessageController::class, 'store']);        // Create a new message
Route::put('/messages/{id}', [MessageController::class, 'update']);   // Update a message
Route::delete('/messages/{id}', [MessageController::class, 'destroy']); // Delete a message
Route::get('/messages/{id}/replies', [MessageController::class, 'getReplies']); // Get replies for a message
