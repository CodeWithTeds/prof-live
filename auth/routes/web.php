<?php

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Optional: ensure only admins go to dashboard
        if (!Auth::user()->is_admin) {
            return redirect('/')
                ->with('error', 'You do not have admin access');
        }

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->name('login.post');

Route::view('/register', 'auth.register')->name('register');

// Add /dashboard route for admin dashboard
Route::get('/dashboard', [AdminController::class, 'dashboard'])
    ->middleware(['auth', AdminMiddleware::class])
    ->name('dashboard');

Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

if (app()->isLocal()) {
    Route::view('/admin/preview', 'admin.dashboard')->name('admin.preview');
}
