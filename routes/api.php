<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MosqueController;
use App\Http\Controllers\ShaffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\NewUser;
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
Route::post('/user_register',[AuthController::class,'user_register']);
Route::post('/user_login',[AuthController::class,'user_login']);
Route::post('/admin_login',[AuthController::class,'admin_login']);
Route::get('/test-cors', function () {
    return response()->json(['message' => 'CORS works!']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix'=>'admin','middleware'=>['auth:admin-api','scopes:admin']],function (){
    Route::post('mosques', [MosqueController::class, 'store']);
    Route::post('mosques/{id}', [MosqueController::class, 'update']);
    Route::get('mosques', [MosqueController::class, 'index']);
    Route::post('searchByCategory', [MosqueController::class, 'searchByCategory']);
    Route::post('searchByName', [MosqueController::class, 'searchByName']);
    Route::delete('mosques/{id}', [MosqueController::class, 'destroy']);
    Route::get('ViewDelate', [MosqueController::class, 'ViewDelate']);
    Route::post('addMosqueManager', [ShaffController::class, 'addMosqueManager']);
    Route::post('updateMosqueManager/{id}', [ShaffController::class, 'updateMosqueManager']);
    Route::delete('/mosque-managers/{id}', [ShaffController::class, 'archiveMosqueManager']);
    Route::get('index1', [ShaffController::class, 'index1']);
    Route::post('restoreMosqueManager/{id}', [ShaffController::class, 'restoreMosqueManager']);
    Route::get('archivedMosqueManagers', [ShaffController::class, 'archivedMosqueManagers']);
    Route::post('getMosqueStaff/{mosqueId}', [ShaffController::class, 'getMosqueStaff']);
    Route::post('/issue-decision', [ExportController::class, 'issueDecision']);
    Route::get('/admin/requests', [RequestController::class, 'index']);
    Route::post('/admin/requests/{id}/status', [RequestController::class, 'updateStatus']);
    Route::post('/admin/requests/{id}/progress', [RequestController::class, 'updateProgress']);
    Route::get('/veiwComplaint', [RequestController::class, 'veiwComplaint']);
    Route::post('/complaints/{id}', [RequestController::class, 'updateStatusComplaint']);

});
Route::prefix('admin')->group(function () {
    Route::post('password/email', [AuthController::class, 'ForgetPassword']);
    Route::post('password/code/check', [AuthController::class, 'CodeCheck']);
    Route::post('password/reset', [AuthController::class, 'ResetPassword']);
});
Route::get('/export-users', [ExportController::class, 'exportUsers']);
Route::group(['prefix'=>'user','middleware'=>['auth:user-api','scopes:user']],function (){
Route::get('/latest-decision', [ExportController::class, 'getLatestDecision']);
Route::post('/requests', [RequestController::class, 'store']);
Route::get('/my-requests', [RequestController::class, 'myRequests']);
Route::post('/addcomplaint', [RequestController::class, 'addcomplaint']);
Route::get('/my-complaints', [RequestController::class, 'myComplaints']);

});


// Route::get('/test-notify', function () {
//     $user = User::first(); // أو أي مستخدم تريده
//     $user->notify(new NewUser($user));

//     return response()->json(['message' => 'تم إرسال الإشعار']);
// });
// Route::get('/my-notifications', function () {
//     $user = User::first();
//     return $user->notifications;
// });
