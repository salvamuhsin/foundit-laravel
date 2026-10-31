<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MeetupController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FoundIt web routes
|--------------------------------------------------------------------------
| index ........ home page (resources/views/index.blade.php)
| role:user .... members who report, claim, message and arrange meetups
| role:admin ... admins who only watch users and what they do (no reporting/claiming)
*/

// ---------- Public ----------
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'send')->middleware('throttle:5,1')->name('contact.send');
});

Route::get('/browse', [ItemController::class, 'browse'])->name('browse');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// ---------- Auth ----------
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->middleware('throttle:10,1')->name('login.attempt');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Members (admins are redirected to the admin panel) ----------
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/report', [ItemController::class, 'create'])->name('items.create');
    Route::post('/report', [ItemController::class, 'store'])->name('items.store');

    Route::post('/items/{item}/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::post('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');
    Route::post('/claims/{claim}/decline', [ClaimController::class, 'decline'])->name('claims.decline');
    Route::post('/claims/{claim}/returned', [ClaimController::class, 'markReturned'])->name('claims.returned');

    Route::post('/meetups/{meetup}/confirm', [MeetupController::class, 'confirm'])->name('meetups.confirm');
    Route::post('/meetups/{meetup}/change', [MeetupController::class, 'change'])->name('meetups.change');

    Route::prefix('dashboard')->name('dashboard.')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/items', 'items')->name('items');
        Route::get('/claims', 'claims')->name('claims');
        Route::get('/received', 'received')->name('received');
        Route::get('/messages', 'messages')->name('messages');
        Route::post('/messages', 'sendMessage')->name('messages.send');
        Route::get('/meetups', 'meetups')->name('meetups');
    });
});

// ---------- Admin: navbar + sidebar, tracking only ----------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('index');

    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [Admin\UserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/restrict', [Admin\UserController::class, 'restrict'])->name('users.restrict');
    Route::post('/users/{user}/unrestrict', [Admin\UserController::class, 'unrestrict'])->name('users.unrestrict');

    Route::get('/claims', [Admin\ClaimController::class, 'index'])->name('claims.index');
    Route::get('/items', [Admin\ItemController::class, 'index'])->name('items.index');
    Route::get('/meetups', [Admin\MeetupController::class, 'index'])->name('meetups.index');
    Route::get('/messages', [Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('/activity', [Admin\ActivityController::class, 'index'])->name('activity.index');
    Route::get('/contact', [Admin\ContactMessageController::class, 'index'])->name('contact.index');
});
