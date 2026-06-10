<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LearningPath;

class PageController extends Controller
{

public function home(): View
{
    $courses = $this->getCoursesFromApi();

    $skillCategories = [
        'AI & Data',
        'IT & Cybersecurity',
        'Business & Management',
        'Health & Medical',
        'Design & Engineering',
        'Sustainability & Environment',
        'Creative & Media',
        'General Learning',
    ];

    return view('pages.home', [
        'courses' => array_slice($courses, 0, 5),
        'skillCategories' => $skillCategories,
    ]);
}

private function getCoursesFromApi(string $query = '', string $level = ''): array
{
    try {
        $apiUrl = env('AI_SERVICE_URL', 'http://127.0.0.1:8000');
        $params = [];
        if (!empty($query)) $params['q'] = $query;
        $response = Http::timeout(10)->get("{$apiUrl}/courses", $params);
        if ($response->successful()) {
            $data = $response->json();
            $courses = $data['courses'] ?? [];
            return array_map(fn($c) => $this->mapCourseFromApi($c), $courses);
        }
        Log::error("AI service courses call failed: " . $response->body());
    } catch (\Exception $e) {
        Log::error("Failed to connect to AI service courses: " . $e->getMessage());
    }
    return [];
}

public function explore(): View
{
    $isRecommended = request('sort') === 'recommended';
    $q = request('q', '');        // default empty string
    $skill = request('skill', '');
    $level = request('level', '');
    $platform = request('platform');

    if ($isRecommended) {
        $interest = '';

        if (Auth::check()) {
            $interest = Auth::user()->learningPaths()->latest()->value('interest') ?? '';
        }

        if (!$interest && session()->has('user_preferences')) {
            $preferences = session('user_preferences');
            $interest = $preferences['interest'] ?? '';
        }

        $recs = $interest ? $this->getRecommendations($interest, 50) : [];
        $courses = collect(!empty($recs) ? $recs : $this->getCoursesFromApi($q));
    } else {
        // Kirim $q langsung ke API supaya filtering dilakukan di sumber data
        $courses = collect($this->getCoursesFromApi($q));
    }

    // Filter platform
    if (!empty($platform)) {
        $courses = $courses->filter(function ($course) use ($platform) {
            return strtolower($course['platform'] ?? '') === strtolower($platform);
        });
    }

    if (!empty($skill)) {
    $courses = $courses->filter(function ($course) use ($skill) {
        return ($course['category'] ?? 'General Learning') === $skill;
    });
    }
    // fallback 
    if (!empty($q)) {
        $courses = $courses->filter(function ($course) use ($q) {
            $keyword = strtolower($q);
            $text = strtolower(
                ($course['title'] ?? '') . ' ' .
                ($course['description'] ?? '') . ' ' .
                ($course['skills'] ?? '') . ' ' .
                ($course['platform'] ?? '') . ' ' .
                ($course['level'] ?? '')
            );
            return str_contains($text, $keyword);
        });
    }

    if ($isRecommended) {
        $courses = $courses->sortByDesc('match');
    }

    return view('pages.explore', [
        'courses'       => $courses->values()->toArray(),
        'isRecommended' => $isRecommended,
        'searchQuery'   => $q,
        'selectedSkill' => $skill,
        'selectedLevel' => $level,
        'selectedPlatform' => $platform,
    ]);
}


   public function courseDetail(int $id, Request $request): View|RedirectResponse
{
    $course = $this->getCourseDetailFromApi($id);

    if (!$course) {
        return redirect()->route('explore')->with('error', 'Course not found.');
    }

    $user = Auth::user();
    $pathId = $request->query('path_id');
    $learningPath = null;

    if ($user) {
        $learningPath = $pathId 
            ? $user->learningPaths()->find($pathId)
            : $user->learningPaths()->latest()->first();

        if ($learningPath) {
            $learningPath->startCourse($id);
        }
    }

    // Determine if course is completed in the current path
    $isCompleted = false;
    if ($learningPath) {
        $courses = $learningPath->recommended_courses ?? [];
        foreach ($courses as $c) {
            $cId = $c['course_id'] ?? $c['id'] ?? null;
            if ($cId == $id && ($c['status'] ?? '') === 'Completed') {
                $isCompleted = true;
            }
        }
    }

    if ($request->has('match')) {
        $course['match'] = (int) $request->query('match');
    }

    return view('pages.course-detail', [
        'course' => $course,
        'isCompleted' => $isCompleted,
        'learningPathId' => $learningPath?->id ?? null
    ]);
}

    public function profile(): View
    {
        $user = Auth::user();

        $learningPaths = $user->learningPaths()
            ->latest()
            ->get();

        $latestPath = $learningPaths->first();

        $profile = [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => 'assets/illustrations/avatar-user.svg',
            'joined' => optional($user->created_at)->format('d M Y'),
            'latest_major' => $latestPath?->major,
            'latest_interest' => $latestPath?->interest,
            'latest_target' => $latestPath?->target_level,
        ];

        $learningTitles = $learningPaths
            ->where('status', 'in_progress')
            ->map(fn (LearningPath $path) => [
                'id' => $path->id,
                'name' => $path->title,
                'percent' => $path->progress,
            ])
            ->values()
            ->toArray();

        return view('pages.profile', [
            'profile' => $profile,
            'skills' => $learningTitles,
            'learningCount' => $learningPaths->count(),
            'completedCount' => $learningPaths->where('status', 'completed')->count(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        $request->user()->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    public function roadmap(?LearningPath $learningPath = null): View|RedirectResponse
    {
        $user = Auth::user();

        if ($learningPath && $learningPath->user_id !== $user->id) {
            abort(403);
        }

        $learningPath ??= $user->learningPaths()->latest()->first();

        if (!$learningPath) {
            return view('pages.roadmap', [
                'steps' => [],
                'learningPath' => null,
            ]);
        }

        $steps = [];
        $courses = $learningPath->recommended_courses ?? [];

        if (empty($courses)) {
            $courses = $this->getRecommendations($learningPath->interest, 6);
            
            // Initialize status: first course is 'In Progress', others are 'Not Started'
            foreach ($courses as $index => &$rec) {
                $rec['status'] = ($index === 0) ? 'In Progress' : 'Not Started';
            }
            unset($rec);

            $learningPath->update(['recommended_courses' => $courses]);
        }

        foreach ($courses as $index => $course) {
            $mapped = $this->mapCourseFromApiSafe($course);

            // Read the status from the database, or fallback
            $status = $course['status'] ?? (($index === 0) ? 'In Progress' : 'Not Started');

            $steps[] = [
                'id' => $mapped['id'],
                'step' => $index + 1,
                'title' => $mapped['title'],
                'description' => $mapped['description'] ?: 'Recommended course based on your preference input.',
                'category' => $mapped['category'],
                'platform' => $mapped['platform'],
                'status' => $status,
            ];
        }

        return view('pages.roadmap', compact('steps', 'learningPath'));
    }

    public function myLearning(): View
    {
        $tabs = ['All', 'In Progress', 'Completed'];

        $paths = Auth::user()
            ->learningPaths()
            ->latest()
            ->get();

        $learningPaths = $paths->map(fn (LearningPath $path) => [
            'id' => $path->id,
            'title' => $path->title,
            'major' => $path->major,
            'interest' => $path->interest,
            'initial_level' => $path->initial_level,
            'target_level' => $path->target_level,
            'status_key' => $path->status,
            'progress' => $path->progress,
            'created_at' => optional($path->created_at)->format('d M Y, H:i'),
        ])->toArray();

        $summary = [
            'total' => $paths->count(),
            'in_progress' => $paths->where('status', 'in_progress')->count(),
            'completed' => $paths->where('status', 'completed')->count(),
        ];

        return view('pages.my-learning', compact('tabs', 'learningPaths', 'summary'));
    }

    private function mapCourseFromApiSafe(array $course): array
    {
        if (isset($course['course_id'])) {
            return $this->mapCourseFromApi($course);
        }

        $category = $this->determineCategory($course);

        $rawLevel = $course['level'] ?? null;
        $level = ($rawLevel !== null && $rawLevel !== '') ? ucwords(strtolower($rawLevel)) : null;

        return [
            'id' => (int) ($course['id'] ?? 1),
            'title' => $course['title'] ?? 'Untitled Course',
            'platform' => $course['platform'] ?? 'Online Course',
            'level' => $level,
            'match' => (int) ($course['match'] ?? 0),
            'category' => $category,
            'thumbnail' => $this->determineThumbnail($category),
            'description' => $course['description'] ?? '',
            'url' => $course['url'] ?? '',
            'skills' => $course['skills'] ?? '',
        ];
    }

    private function getRecommendations(string $interest, int $limit = 6): array
    {
        try {
            $apiUrl = env('AI_SERVICE_URL', 'http://127.0.0.1:8000');
            $response = Http::timeout(15)->post("{$apiUrl}/recommend", [
                'user_id' => '1',
                'interest' => $interest,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $recs = $data['recommendations'] ?? [];
                
                $formatted = [];
                foreach ($recs as $rec) {
                    $formatted[] = $this->mapCourseFromApi($rec);
                }
                return array_slice($formatted, 0, $limit);
            }
            
            Log::error("AI service recommend call failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Failed to connect to AI service: " . $e->getMessage());
        }

        return [];
    }

    private function getCourseDetailFromApi(int $courseId): ?array
    {
        try {
            $apiUrl = env('AI_SERVICE_URL', 'http://127.0.0.1:8000');
            $response = Http::timeout(5)->get("{$apiUrl}/courses/{$courseId}");

            if ($response->successful()) {
                $course = $response->json();
                return $this->mapCourseFromApi($course);
            }
            Log::error("AI service get course call failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Failed to connect to AI service: " . $e->getMessage());
        }

        return null;
    }

    private function mapCourseFromApi(array $apiCourse): array
    {
        $id = (int)$apiCourse['course_id'];
        $title = $apiCourse['title'] ?? 'Untitled Course';
        $category = $this->determineCategory($apiCourse);
        
        $matchScore = isset($apiCourse['score']) ? (int)round($apiCourse['score'] * 100) : null;
        if ($matchScore === null && session()->has('user_preferences')) {
            $matchScore = 85; 
        }

        $rawLevel = $apiCourse['level'] ?? null;
        $level = ($rawLevel !== null && $rawLevel !== '') ? ucwords(strtolower($rawLevel)) : null;

        return [
            'id' => $id,
            'title' => $title,
            'platform' => $apiCourse['platform'] ?? 'Online Course',
            'level' => $level,
            'match' => $matchScore ?? 0,
            'category' => $category,
            'thumbnail' => $this->determineThumbnail($category),
            'description' => $apiCourse['description'] ?? '',
            'url' => $apiCourse['url'] ?? '',
            'skills' => $apiCourse['skills'] ?? '',
        ];
    }

private function determineCategory(array $course): string
{
    $text = strtolower(
        ($course['title'] ?? '') . ' ' .
        ($course['description'] ?? '') . ' ' .
        ($course['skills'] ?? '') . ' ' .
        ($course['platform'] ?? '') . ' ' .
        ($course['level'] ?? '')
    );

    $categoryKeywords = [
        'Health & Medical' => [
            'health', 'medical', 'medicine', 'anatomy', 'physiology',
            'cardiology', 'clinical', 'healthcare', 'physical therapy',
            'orthopedics', 'diagnostic', 'chronic diseases', 'muscle',
            'bones', 'skeletal'
        ],

        'AI & Data' => [
            'data', 'data science', 'machine learning', 'deep learning',
            'artificial intelligence', 'python', 'statistics', 'analytics',
            'ai', 'ml'
        ],

        'IT & Cybersecurity' => [
            'cybersecurity', 'cyber security', 'network security',
            'computer networking', 'firewall', 'tcp/ip', 'cloud computing',
            'information technology', 'database', 'software security'
        ],

        'Business & Management' => [
            'business', 'management', 'marketing', 'finance',
            'entrepreneurship', 'strategy', 'leadership',
            'project management', 'supply chain', 'operations'
        ],

        'Design & Engineering' => [
            'design', 'engineering', 'cad', '3d cad', 'architecture',
            'mechanical', 'civil', 'manufacturing', 'autocad'
        ],

        'Sustainability & Environment' => [
            'sustainability', 'environment', 'environmental', 'energy',
            'energy transition', 'climate', 'transportation',
            'renewable', 'green policy'
        ],

        'Creative & Media' => [
            'creative', 'media', 'video', 'photography', 'music',
            'animation', 'vr', '360 video', 'film', 'game design'
        ],
    ];

    foreach ($categoryKeywords as $category => $keywords) {
        foreach ($keywords as $keyword) {
            if (str_contains($text, strtolower($keyword))) {
                return $category;
            }
        }
    }

    return 'General Learning';
}


private function determineThumbnail(string $category): string
{
    $basePath = 'assets/course-thumbnails/';

    $fileName = str_replace(' & ', '-', $category) . '.svg';

    $thumbnail = $basePath . $fileName;

    if (file_exists(public_path($thumbnail))) {
        return $thumbnail;
    }

    return $basePath . 'General-Learning.svg';
}

    public function goToCourse(int $id, Request $request)
    {
        $course = $this->getCourseDetailFromApi($id);
        if (!$course) {
            return redirect()->route('explore')->with('error', 'Course not found.');
        }

        $user = Auth::user();
        if ($user) {
            $pathId = $request->query('path_id');
            $learningPath = $pathId 
                ? $user->learningPaths()->find($pathId)
                : $user->learningPaths()->latest()->first();

            if ($learningPath) {
                $learningPath->startCourse($id); // ganti completeCourse → startCourse
            }
        }

        $url = $course['url'] ?? 'https://www.coursera.org';
        return redirect()->away($url);
    }

   public function completeCourse(LearningPath $learningPath, int $courseId): RedirectResponse
{
    if ($learningPath->user_id !== Auth::id()) {
        abort(403);
    }

    $learningPath->completeCourse($courseId);

    return redirect()->route('roadmap.show', $learningPath->id)
        ->with('success', 'Course marked as completed.');
}
}
