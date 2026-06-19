<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CapsuleController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\NewsfeedController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCapsuleController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () { return redirect()->route('login'); });

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //Posts & Newsfeed
    Route::get('/newsfeed', [PostController::class, 'index'])->name('newsfeed');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/reactions', [PostController::class, 'toggleReaction'])->name('posts.reactions.toggle');
    Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])->name('posts.comments.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::patch('/posts/{post}/visibility', [PostController::class, 'updateVisibility'])->name('posts.visibility');

    // Capsules
    Route::get('/capsules', [CapsuleController::class, 'index'])->name('capsules.index');
    Route::get('/capsules/create', [CapsuleController::class, 'create'])->name('capsules.create');
    Route::post('/capsules', [CapsuleController::class, 'store'])->name('capsules.store');
    Route::get('/capsules/{capsule}', [CapsuleController::class, 'show'])->name('capsules.show');
    Route::get('/capsules/{capsule}/edit', [CapsuleController::class, 'edit'])->name('capsules.edit');
    Route::put('/capsules/{capsule}', [CapsuleController::class, 'update'])->name('capsules.update');
    Route::delete('/capsules/{capsule}', [CapsuleController::class, 'destroy'])->name('capsules.destroy');
    Route::post('/capsules/{capsule}/submit', [CapsuleController::class, 'submitToNewsfeed'])->name('capsules.submit');

    // Friends
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::get('/friends/search', [FriendController::class, 'search'])->name('friends.search');
    Route::post('/friends/{user}/request', [FriendController::class, 'sendRequest'])->name('friends.request');
    Route::delete('/friends/{user}/cancel', [FriendController::class, 'cancelRequest'])->name('friends.cancel');
    Route::post('/friends/{friendship}/accept', [FriendController::class, 'acceptRequest'])->name('friends.accept');
    Route::post('/friends/{friendship}/decline', [FriendController::class, 'declineRequest'])->name('friends.decline');
    Route::delete('/friends/{user}/unfriend', [FriendController::class, 'unfriend'])->name('friends.unfriend');
   Route::get('/friends/{friendId}/capsules', [FriendController::class, 'showCapsules'])
    ->name('friends.capsules');

    // Reactions
    Route::post('/capsules/{capsule}/react', [ReactionController::class, 'toggle'])->name('reactions.toggle');

    // Comments
    Route::post('/capsules/{capsule}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/font', [ProfileController::class, 'updateFont'])->name('profile.font');
});

// User logout
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


// Admin auth
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin protected
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/capsules', [AdminCapsuleController::class, 'index'])->name('capsules.index');
    Route::get('/review', [AdminCapsuleController::class, 'reviewQueue'])->name('review');
    Route::post('/capsules/{capsule}/approve', [AdminCapsuleController::class, 'approve'])->name('capsules.approve');
    Route::post('/capsules/{capsule}/reject', [AdminCapsuleController::class, 'reject'])->name('capsules.reject');
    Route::delete('/capsules/{capsule}', [AdminCapsuleController::class, 'destroy'])->name('capsules.destroy');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-role', [AdminUserController::class, 'toggleRole'])->name('users.toggleRole');
});

require __DIR__.'/auth.php';

