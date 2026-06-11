<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/explore', [PageController::class, 'explore'])->name('explore');
Route::get('/course/{id}', [PageController::class, 'courseDetail'])->name('course.detail');
Route::get('/course/{id}/go', [PageController::class, 'goToCourse'])->name('course.go');

Route::get('/preferences', [RecommendationController::class, 'create'])->name('preferences.create');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    Route::put('/profile', [PageController::class, 'updateProfile'])->name('profile.update');

    Route::get('/roadmap', [PageController::class, 'roadmap'])->name('roadmap');
    Route::get('/roadmap/{learningPath}', [PageController::class, 'roadmap'])->name('roadmap.show');
    Route::post('/roadmap/{learningPath}/complete/{courseId}', [PageController::class, 'completeCourse'])->name('roadmap.complete');

    Route::get('/my-learning', [PageController::class, 'myLearning'])->name('my-learning');

    Route::post('/preferences', [RecommendationController::class, 'store'])->name('preferences.store');
});

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }

    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
});

Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }

    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan sign in.');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->name('logout');