<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
use App\Models\Friend;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

     Route::get('/userInformation', [AuthController::class, 'userInformation']);

     Route::post('/logout', [AuthController::class, 'logout']);
     Route::get('/posts', [PostController::class, 'post']);
     // comments
     Route::get('/comments', [PostController::class, 'comments']);
     Route::post('/posts/store', [PostController::class, 'store']);
     Route::get('/posts/edit/{id}', [PostController::class, 'postEdit']);
     Route::patch('/posts/update/{id}', [PostController::class, 'postUpdate']); //uncompleated
     Route::delete('/posts/delete/{id}', [PostController::class, 'postDelete']); 
     Route::post('/comments/store', [PostController::class, 'commentsStore']);
     Route::post('/comments/reply/store', [PostController::class, 'commentReplyStore']);
     // friend
     Route::get('/suggestFriend', [FriendController::class, 'suggestFriend']);  //uncompleated
     Route::post('/friendRequest', [FriendController::class, 'FriendRequest']);
     Route::get('/allFriend', [FriendController::class, 'AllFriend']);
     Route::patch('/confirmRequest/{id}', [FriendController::class, 'confirmRequest']);
     Route::get('/showRequest', [FriendController::class, 'ShowRequest']);
     // notification
     Route::get('/notifications', [NotificationController::class, 'notifications']);
     Route::patch('/notificationSeen/{id}', [NotificationController::class, 'notificationSeen']);
     // message
     Route::get('/messages', [MessageController::class, 'messages']);
     Route::get('/message/get/{id}', [MessageController::class, 'messagesById']);
     Route::post('/messageStore', [MessageController::class, 'messageStore']);
     Route::patch('/messageSeen/{id}', [MessageController::class, 'messageSeen']);

     // test
     Route::get('/test', [FriendController::class, 'test']);
});
