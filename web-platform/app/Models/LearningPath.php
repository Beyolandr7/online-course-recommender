<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'major',
        'initial_level',
        'target_level',
        'interest',
        'status',
        'progress',
        'recommended_courses',
    ];

    protected function casts(): array
    {
        return [
            'recommended_courses' => 'array',
            'progress' => 'integer',
        ];
    }

    public function updateProgressAndStatus(): void
    {
        $courses = $this->recommended_courses ?? [];
        if (empty($courses)) {
            return;
        }

        $completedCount = 0;
        foreach ($courses as $c) {
            if (($c['status'] ?? 'Not Started') === 'Completed') {
                $completedCount++;
            }
        }

        $totalCount = count($courses);
        $newProgress = $totalCount > 0 ? (int)round(($completedCount / $totalCount) * 100) : 0;

        $this->progress = $newProgress;
        $this->status = ($newProgress === 100) ? 'completed' : 'in_progress';
        $this->save();
    }

    public function startCourse(int $courseId): void
    {
        $courses = $this->recommended_courses ?? [];
        $updated = false;

        foreach ($courses as &$c) {
            $cId = $c['course_id'] ?? $c['id'] ?? null;
            if ($cId == $courseId) {
                if (($c['status'] ?? 'Not Started') === 'Not Started') {
                    $c['status'] = 'In Progress';
                    $updated = true;
                }
            }
        }

        if ($updated) {
            $this->recommended_courses = $courses;
            $this->updateProgressAndStatus();
        }
    }

    public function completeCourse(int $courseId): void
    {
        $courses = $this->recommended_courses ?? [];
        $updated = false;

        foreach ($courses as &$c) {
            $cId = $c['course_id'] ?? $c['id'] ?? null;
            if ($cId == $courseId) {
                if (($c['status'] ?? 'Not Started') !== 'Completed') {
                    $c['status'] = 'Completed';
                    $updated = true;
                }
            }
        }

        if ($updated) {
            $this->recommended_courses = $courses;
            $this->updateProgressAndStatus();
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
