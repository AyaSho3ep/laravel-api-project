<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatGptController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\CheckCodeController;
use App\Http\Controllers\PostsDocumentsController;
use App\Http\Controllers\PostEditRequestController;
use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\GetPostController;
use App\Http\Controllers\AddingPostRequestsController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\PostClassificationController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;

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

// public routes
Route::get('/test', function () {
    $redis = Redis::connection();
    $redis->publish('test-channel', json_encode(['foo' => 'bar']));

    return 'Message sent!';
});
// auth
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('forgot-password',[ForgotPasswordController::class, 'forgotPassword']);
Route::post('password/verify-code',[CheckCodeController::class, 'verifyCode']);
Route::post('rest-password',[ResetPasswordController::class, 'resetPassword']);

// public
Route::get('/posts',[GetPostController::class, 'index']);
Route::get('/viewed-posts',[GetPostController::class, 'viewPosts']);
Route::get('/post/{id}',[GetPostController::class, 'getPostById']);
Route::get('/category-posts/{id}',[GetPostController::class, 'getPostByCategory']);
Route::get('/category-posts/{id}',[GetPostController::class, 'getPostByClassification']);
Route::get('/trend-posts',[GetPostController::class, 'getPostByTrending']);
Route::get('/search-posts/{search}',[GetPostController::class, 'searchPost']);
Route::post('/post-request',[AddingPostRequestsController::class, 'store']);
Route::post('/post-docs',[PostsDocumentsController::class, 'store']);

Route::get('/comments',[CommentController::class, 'index']);

Route::post('/contact-us',[FrontendContactController::class, 'store']);

Route::get('/elmokhber-teamWork',[UserController::class, 'teamWork']);

Route::get('/settings',[SettingController::class, 'index']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{search}', [CategoryController::class, 'search']);

Route::get('/post-classifications', [PostClassificationController::class, 'index']);

Route::post('/ask_elmokhber', [ChatGptController::class, 'generateResponse']);



// protected routes
Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post('/logout',[AuthController::class,'logout']);

    Route::get('/my-profile',[ProfileController::class,'user']);
    Route::post('/change-password',[ProfileController::class,'changePassword']);
    Route::post('/update-profile',[ProfileController::class,'updateProfile']);

    Route::post('/add-comment',[CommentController::class, 'store']);
    Route::put('/edit-comment/{id}',[CommentController::class, 'edit']);
    Route::post('/update-comment/{id}',[CommentController::class, 'update']);
    Route::delete('/delete-comment/{id}',[CommentController::class, 'delete']);

    Route::post('/add-post-edit-request',[PostEditRequestController::class, 'store']);

    Route::get('/training-course/units',[UnitController::class, 'index']);
    Route::get('/training-course/lessons',[LessonController::class, 'index']);

    Route::prefix('/dashboard')->group(function(){

        // users - Privileges control
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/create-supervisor/{id}', [UserController::class, 'userToSupervisor']);
        Route::delete('/remove-supervisor/{id}', [UserController::class, 'DeleteSupervisorPrivilege']);
        Route::post('/create-admin/{id}', [UserController::class, 'userToAdmin']);
        Route::delete('/remove-admin/{id}', [UserController::class, 'DeleteAdminPrivilege']);

        // categories
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{search}', [CategoryController::class, 'search']);
        Route::post('/add-category', [CategoryController::class, 'store']);
        Route::put('/edit-category/{id}', [CategoryController::class, 'edit']);
        Route::post('/update-category/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete-category/{id}', [CategoryController::class, 'delete']);

        // posts classifications
        Route::get('/post-classifications', [PostClassificationController::class, 'index']);
        Route::post('/add-post-classification', [PostClassificationController::class, 'store']);
        Route::put('/edit-post-classification/{id}', [PostClassificationController::class, 'edit']);
        Route::post('/update-post-classification/{id}', [PostClassificationController::class, 'update']);
        Route::delete('/delete-post-classification/{id}', [PostClassificationController::class, 'delete']);

        // posts
        Route::get('/posts',[PostController::class, 'index']);
        Route::post('/add-post',[PostController::class, 'store']);
        Route::put('/edit-post/{id}',[PostController::class, 'edit']);
        Route::post('/update-post/{id}',[PostController::class, 'update']);
        Route::delete('/delete-post/{id}',[PostController::class, 'delete']);
        Route::get('/posts/{search}',[PostController::class, 'search']);
        Route::get('/post-requests',[AddingPostRequestsController::class, 'index']);
        Route::delete('/delete-post-request/{id}',[AddingPostRequestsController::class, 'delete']);
        Route::get('/post-docs',[PostsDocumentsController::class, 'index']);
        Route::delete('/delete-post-docs/{id}',[PostsDocumentsController::class, 'delete']);
        Route::get('/post-edit-request',[PostEditRequestController::class, 'index']);
        Route::delete('/post-edit-request/{id}',[PostEditRequestController::class, 'index']);

        //comments
        Route::get('/comments',[AdminCommentController::class, 'index']);
        Route::delete('/delete-comment/{id}',[AdminCommentController::class, 'delete']);

        // contacts
        Route::get('/contacts',[ContactController::class, 'getContacts']);
        Route::delete('/delete-contact/{id}',[ContactController::class, 'delete']);

        // settings routes
        Route::post('/add-home-settings',[SettingController::class, 'store']);
        Route::post('/update-home-media/{id}',[SettingController::class, 'updateHomeMedia']);
        Route::put('/edit-social-links/{id}',[SettingController::class, 'editSocialLinks']);
        Route::post('/update-social-links/{id}',[SettingController::class, 'updateSocialLinks']);

        // training course
        Route::prefix('/training-course')->group(function(){
            Route::get('/units',[UnitController::class, 'index']);
            Route::post('/add-unit',[UnitController::class, 'store']);
            Route::put('/edit-unit/{id}',[UnitController::class, 'edit']);
            Route::post('/update-unit/{id}',[UnitController::class, 'update']);
            Route::delete('/delete-unit/{id}',[UnitController::class, 'delete']);

            Route::get('/lessons',[LessonController::class, 'index']);
            Route::post('/add-lesson',[LessonController::class, 'store']);
            Route::put('/edit-lesson/{id}',[LessonController::class, 'edit']);
            Route::post('/update-lesson/{id}',[LessonController::class, 'update']);
            Route::delete('/delete-lesson/{id}',[LessonController::class, 'delete']);
        });

    });
});

