<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/test', function () {
    // return UserResource::make(User::find(1));
});


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Post all in one 
    Route::resource('posts', PostController::class)->only(['create', 'store']);

    // Comments
    // Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    // Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    // Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    //all in one 
    Route::resource('posts.comments', CommentController::class)->shallow()->only(['store', 'update', 'destroy']);
});

// Posts
// Route::get('posts', [PostController::class, 'index'])->name('posts.index');
// Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show'); // after use showroute with slug we can delete optionaly {slug?}'
Route::resource('posts', PostController::class)->only(['index']); //remove show from this array and mae individual route to use slug for SEO