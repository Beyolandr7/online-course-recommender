<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('AI_SERVICE_URL', 'http://127.0.0.1:8000');
    }

    /**
     * Get course list from FastAPI AI Service.
     */
    public function getCourses(string $query = ''): array
    {
        try {
            $params = [];
            if (!empty($query)) {
                $params['q'] = $query;
            }

            $response = Http::timeout(10)->get("{$this->apiUrl}/courses", $params);

            if ($response->successful()) {
                $data = $response->json();
                $courses = $data['courses'] ?? [];
                return array_map(fn($c) => $this->mapCourse($c), $courses);
            }

            Log::error("AI service courses call failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Failed to connect to AI service courses: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Get course details from FastAPI AI Service.
     */
    public function getCourseDetail(int $courseId): ?array
    {
        try {
            $response = Http::timeout(5)->get("{$this->apiUrl}/courses/{$courseId}");

            if ($response->successful()) {
                $course = $response->json();
                return $this->mapCourse($course);
            }

            Log::error("AI service get course call failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Failed to connect to AI service: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Get course recommendations from FastAPI AI Service.
     */
    public function getRecommendations(string $interest, int $limit = 6): array
    {
        try {
            $response = Http::timeout(15)->post("{$this->apiUrl}/recommend", [
                'user_id' => (string) auth()->id(),
                'interest' => $interest,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $recs = $data['recommendations'] ?? [];
                
                $formatted = [];
                foreach ($recs as $rec) {
                    $formatted[] = $this->mapCourse($rec);
                }

                return array_slice($formatted, 0, $limit);
            }

            Log::error("AI service recommend call failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Failed to connect to AI service: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Map FastAPI API data structure to Laravel platform structure.
     */
    public function mapCourse(array $course): array
    {
        $id = $course['course_id'] ?? $course['id'] ?? null;
        $title = $course['title'] ?? 'Untitled Course';
        $category = $this->determineCategory($course);

        $rawLevel = $course['level'] ?? null;
        $level = ($rawLevel !== null && $rawLevel !== '') ? ucwords(strtolower($rawLevel)) : null;

        $matchScore = isset($course['score']) ? (int)round($course['score'] * 100) : null;
        if ($matchScore === null && session()->has('user_preferences')) {
            $matchScore = 85;
        }

        return [
            'id' => (int) $id,
            'title' => $title,
            'platform' => $course['platform'] ?? 'Online Course',
            'level' => $level,
            'match' => $matchScore ?? 0,
            'category' => $category,
            'thumbnail' => $this->determineThumbnail($category),
            'description' => $course['description'] ?? '',
            'url' => $course['url'] ?? '',
            'skills' => $course['skills'] ?? '',
        ];
    }

    /**
     * Determine category of a course based on its details.
     */
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

    /**
     * Determine thumbnail path for a category.
     */
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
}
