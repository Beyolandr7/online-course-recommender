<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    public function create(): View
    {
        return view('pages.preferences');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'major' => ['required', 'string', 'max:80'],
            'initial_level' => ['required', 'string', 'max:60'],
            'interest' => ['required', 'string', 'max:180'],
            'target_level' => ['required', 'string', 'max:60'],
        ]);

        session(['user_preferences' => $validated]);

        return redirect()->route('explore', [
            'sort' => 'recommended'
        ])->withFragment('course-list');
    }   

   public function result() 
{
    return redirect()->route('explore', [
        'sort' => 'recommended',
    ])->withFragment('course-list');
}
}
