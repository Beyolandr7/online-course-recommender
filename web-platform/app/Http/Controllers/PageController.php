<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    private function courses(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Python for Data Science, AI & Development',
                'provider' => 'IBM',
                'level' => 'Intermediate',
                'duration' => '24 Hours',
                'rating' => '4.7',
                'students' => '12.5k',
                'match' => 45,
                'category' => 'AI & Data',
                'thumbnail' => 'assets/course-thumbnails/python-data.svg',
                'description' => 'Master Python, data analysis, visualization, and real-world AI project workflows.',
            ],
            [
                'id' => 2,
                'title' => 'Data Visualization with Python',
                'provider' => 'Coursera',
                'level' => 'Intermediate',
                'duration' => '15 Hours',
                'rating' => '4.6',
                'students' => '8.1k',
                'match' => 40,
                'category' => 'Data Science',
                'thumbnail' => 'assets/course-thumbnails/data-viz.svg',
                'description' => 'Create clear dashboards and charts using Matplotlib, Seaborn concepts, and storytelling.',
            ],
            [
                'id' => 3,
                'title' => 'Machine Learning Basics',
                'provider' => 'Udemy',
                'level' => 'Beginner',
                'duration' => '25 Hours',
                'rating' => '4.8',
                'students' => '10.3k',
                'match' => 35,
                'category' => 'Machine Learning',
                'thumbnail' => 'assets/course-thumbnails/machine-learning.svg',
                'description' => 'Understand core machine learning concepts, model training, and evaluation basics.',
            ],
            [
                'id' => 4,
                'title' => 'Deep Learning Specialization',
                'provider' => 'IBM',
                'level' => 'Advanced',
                'duration' => '30 Hours',
                'rating' => '4.7',
                'students' => '9.8k',
                'match' => 32,
                'category' => 'AI & ML',
                'thumbnail' => 'assets/course-thumbnails/deep-learning.svg',
                'description' => 'Build neural networks and understand modern deep learning foundations.',
            ],
            [
                'id' => 5,
                'title' => 'SQL for Data Scientists',
                'provider' => 'Coursera',
                'level' => 'Intermediate',
                'duration' => '12 Hours',
                'rating' => '4.6',
                'students' => '6.6k',
                'match' => 28,
                'category' => 'Database',
                'thumbnail' => 'assets/course-thumbnails/sql.svg',
                'description' => 'Query, clean, and analyze data using SQL for practical analytics workflows.',
            ],
            [
                'id' => 6,
                'title' => 'Cloud Fundamentals for Developers',
                'provider' => 'Udemy',
                'level' => 'Beginner',
                'duration' => '18 Hours',
                'rating' => '4.5',
                'students' => '5.2k',
                'match' => 25,
                'category' => 'Cloud',
                'thumbnail' => 'assets/course-thumbnails/cloud.svg',
                'description' => 'Learn cloud architecture, deployment basics, and developer-friendly cloud services.',
            ],
            [
                'id' => 7,
                'title' => 'Cloud Fundamentals for Developers',
                'provider' => 'Udemy',
                'level' => 'Beginner',
                'duration' => '18 Hours',
                'rating' => '4.5',
                'students' => '5.2k',
                'match' => 25,
                'category' => 'Cloud',
                'thumbnail' => 'assets/course-thumbnails/cloud.svg',
                'description' => 'Learn cloud architecture, deployment basics, and developer-friendly cloud services.',
            ],
            [
                'id' => 8,
                'title' => 'Cloud Fundamentals for Developers',
                'provider' => 'Udemy',
                'level' => 'Beginner',
                'duration' => '18 Hours',
                'rating' => '4.5',
                'students' => '5.2k',
                'match' => 25,
                'category' => 'Cloud',
                'thumbnail' => 'assets/course-thumbnails/cloud.svg',
                'description' => 'Learn cloud architecture, deployment basics, and developer-friendly cloud services.',
            ],
            [
                'id' => 9,
                'title' => 'Cloud Fundamentals for Developers',
                'provider' => 'Udemy',
                'level' => 'Beginner',
                'duration' => '18 Hours',
                'rating' => '4.5',
                'students' => '5.2k',
                'match' => 25,
                'category' => 'Cloud',
                'thumbnail' => 'assets/course-thumbnails/cloud.svg',
                'description' => 'Learn cloud architecture, deployment basics, and developer-friendly cloud services.',
            ],
        ];
    }

    public function home(): View
    {
        return view('pages.home', ['courses' => array_slice($this->courses(), 0, 5)]);
    }

    public function explore()
    {
        $courses = collect($this->courses());

        if (request('sort') === 'recommended') {
            $courses = $courses->sortByDesc('match')->values();
        }

        return view('pages.explore', [
            'courses' => $courses,
            'isRecommended' => request('sort') === 'recommended',
        ]); 
    }

    public function courseDetail(int $id): View|RedirectResponse
    {
        $course = collect($this->courses())->firstWhere('id', $id);

        if (!$course) {
            return redirect()->route('explore');
        }

        return view('pages.course-detail', ['course' => $course]);
    }

    public function profile()
    {
    $user = [
        'name'   => 'User 1',
        'avatar' => 'assets/illustrations/avatar-user.svg',
        'degree' => 'Mahasiswa S1',
        'major'  => 'Teknik Informatika',
        'level'  => 'Intermediate',
        'details' => [
            [
                'icon'  => 'school',
                'label' => 'Jurusan',
                'value' => 'Teknik Informatika',
            ],
            [
                'icon'  => 'id-badge',
                'label' => 'Jenjang',
                'value' => 'Mahasiswa S1',
            ],
            [
                'icon'  => 'shield-check',
                'label' => 'Kemahiran Asal',
                'value' => 'Intermediate',
            ],
            [
                'icon'  => 'heart',
                'label' => 'Minat',
                'value' => 'Python, Data Science, Machine Learning',
            ],
            [
                'icon'  => 'target',
                'label' => 'Target',
                'value' => 'Advanced',
            ],
        ],
    ];

    $stats = [
        [
            'value' => '3',
            'label' => 'Courses Enrolled',
        ],
        [
            'value' => '24h',
            'label' => 'Total Learning Time',
        ],
        [
            'value' => '7',
            'label' => 'Skills in Progress',
        ],
    ];

    $skills = [
        [
            'name'    => 'Python',
            'percent' => 75,
        ],
        [
            'name'    => 'Data Science',
            'percent' => 60,
        ],
        [
            'name'    => 'Machine Learning',
            'percent' => 45,
        ],
        [
            'name'    => 'SQL',
            'percent' => 40,
        ],
        [
            'name'    => 'Data Visualization',
            'percent' => 35,
        ],
    ];

    $quickActions = [
        [
            'icon'  => 'pencil',
            'label' => 'Edit Interests',
        ],
        [
            'icon'  => 'target',
            'label' => 'Update Goal',
        ],
        [
            'icon'  => 'settings',
            'label' => 'Profile Settings',
        ],
    ];

    return view('pages.profile', compact(
        'user',
        'stats',
        'skills',
        'quickActions'
    ));
}

    public function roadmap(): View
    {
                $steps = [
            [
                'id' => 1,
                'step' => 1,
                'title' => 'Python for Data Science, AI & Development',
                'description' => 'Start by learning Python fundamentals, variables, functions, loops, and basic data structures.',
                'category' => 'Programming Foundation',
                'duration' => '6 Weeks',
                'provider' => 'Coursera',
                'status' => 'Completed',
            ],
            [
                'id' => 2,
                'step' => 2,
                'title' => 'Data Visualization with Python',
                'description' => 'Learn how to present data using charts, graphs, dashboards, and visual storytelling.',
                'category' => 'Data Analytics',
                'duration' => '4 Weeks',
                'provider' => 'Coursera',
                'status' => 'In Progress',
            ],
            [
                'id' => 3,
                'step' => 3,
                'title' => 'Machine Learning Basics',
                'description' => 'Understand basic machine learning concepts such as supervised learning, classification, and regression.',
                'category' => 'Machine Learning',
                'duration' => '5 Weeks',
                'provider' => 'Google',
                'status' => 'Not Started',
            ],
            [
                'id' => 4,
                'step' => 4,
                'title' => 'Deep Learning Specialization',
                'description' => 'Explore neural networks, deep learning architecture, and practical AI model development.',
                'category' => 'Artificial Intelligence',
                'duration' => '8 Weeks',
                'provider' => 'DeepLearning.AI',
                'status' => 'Not Started',
            ],
            [
                'id' => 5,
                'step' => 5,
                'title' => 'SQL for Data Scientists',
                'description' => 'Learn how to query, filter, join, and analyze structured data from relational databases.',
                'category' => 'Database',
                'duration' => '3 Weeks',
                'provider' => 'Udemy',
                'status' => 'Not Started',
            ],
        ];

        return view('pages.roadmap', compact('steps'));
    }
    

   public function myLearning()
{
    $tabs = ['All', 'In Progress', 'Completed', 'Wishlist'];

    $courses = [
        [
            'id'         => 1,
            'title'      => 'Python for Data Science, AI & Development',
            'status'     => 'Last learned 2 days ago',
            'status_key' => 'in_progress',
            'progress'   => 45,
            'thumbnail'  => 'assets/course-thumbnails/python-data.svg',
        ],
        [
            'id'         => 2,
            'title'      => 'Data Visualization with Python',
            'status'     => 'Last learned 1 week ago',
            'status_key' => 'in_progress',
            'progress'   => 30,
            'thumbnail'  => 'assets/course-thumbnails/data-viz.svg',
        ],
        [
            'id'         => 3,
            'title'      => 'Machine Learning Basics',
            'status'     => 'Not started yet',
            'status_key' => 'not_started',
            'progress'   => 0,
            'thumbnail'  => 'assets/course-thumbnails/machine-learning.svg',
        ],
    ];

    $headerStats = [
        [
            'icon'  => 'clock',
            'label' => 'Total Learning Time',
            'value' => '24h 35m',
        ],
        [
            'icon'  => 'book',
            'label' => 'Courses Enrolled',
            'value' => '3',
        ],
        [
            'icon'  => 'certificate',
            'label' => 'Completed',
            'value' => '0',
        ],
    ];

    $allCourses = $this->courses();
    $recommendedCourses = [
        $allCourses[3], // ID 4: Deep Learning Specialization
        $allCourses[4], // ID 5: SQL for Data Scientists
        $allCourses[5], // ID 6: Cloud Fundamentals for Developers
    ];

    return view('pages.my-learning', compact(
        'tabs',
        'courses',
        'headerStats',
        'recommendedCourses'
    ));
}
}
