<?php

namespace App\Http\Controllers;

use App\Models\LearningPath;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

        $title = sprintf(
            '%s Journey: %s to %s',
            $validated['interest'],
            $validated['initial_level'],
            $validated['target_level']
        );

        $recommendations = $this->fetchRecommendations($validated['interest']);

        // Initialize status: first course is 'In Progress', others are 'Not Started'
        foreach ($recommendations as $index => &$rec) {
            $rec['status'] = ($index === 0) ? 'In Progress' : 'Not Started';
        }
        unset($rec);

        $learningPath = LearningPath::create([
            'user_id' => $request->user()->id,
            'title' => $title,
            'major' => $validated['major'],
            'initial_level' => $validated['initial_level'],
            'target_level' => $validated['target_level'],
            'interest' => $validated['interest'],
            'status' => 'in_progress',
            'progress' => 0,
            'recommended_courses' => $recommendations,
        ]);

        return redirect()->route('roadmap.show', $learningPath);
    }

    public function result(): RedirectResponse
    {
        return redirect()->route('my-learning');
    }

    private function fetchRecommendations(string $interest): array
    {
        try {
            $apiUrl = env('AI_SERVICE_URL', 'http://127.0.0.1:8000');

            $response = Http::timeout(15)->post("{$apiUrl}/recommend", [
                'user_id' => (string) auth()->id(),
                'interest' => $interest,
            ]);

            if ($response->successful()) {
                return array_slice($response->json('recommendations') ?? [], 0, 6);
            }

            Log::error('AI service recommend call failed: ' . $response->body());
        } catch (\Throwable $e) {
            Log::error('Failed to connect to AI service: ' . $e->getMessage());
        }

        return [];
    }
}

