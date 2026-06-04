@extends('layouts.dashboard')

@section('title', $course['title'].' | CourseRecommend')

@section('page-content')
<div class="pt-16 lg:pt-0">
    <a href="{{ route('explore') }}" class="mb-5 inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-700"><x-icon name="arrow" class="h-4 w-4 rotate-180" /> Back to Explore</a>

    <section class="relative overflow-hidden rounded-[34px] bg-slate-950 p-7 text-white shadow-xl shadow-indigo-100 md:p-8">
        <img src="{{ asset('assets/backgrounds/bg-stars.svg') }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-55">
        <div class="relative z-10 grid items-center gap-6 lg:grid-cols-[160px_1fr_auto]">
            <img src="{{ asset($course['thumbnail']) }}" alt="{{ $course['title'] }} thumbnail" class="h-36 w-36 rounded-[28px] bg-white object-cover p-1">

            <div>
                <h1 class="mt-4 max-w-3xl text-3xl font-black leading-tight md:text-4xl">{{ $course['title'] }}</h1>
                <p class="mt-3 font-medium text-white/70">{{ $course['provider'] }} • {{ $course['level'] }} • {{ $course['duration'] }} • Updated Apr 2025</p>
                <div class="mt-4 flex flex-wrap gap-4 text-sm font-bold text-white/90">
                    <span class="inline-flex items-center gap-1"><x-icon name="star" class="h-4 w-4 text-amber-300" /> {{ $course['rating'] }} rating</span>
                    <span>{{ $course['students'] }} reviews</span>
                    <span>{{ $course['match'] }}% match for you</span>
                </div>
            </div>

            <button class="rounded-2xl bg-indigo-600 px-7 py-4 font-black text-white shadow-lg shadow-indigo-900/30 transition hover:bg-indigo-500">Enroll Now</button>
        </div>
    </section>

    <div class="mt-7 grid gap-6 lg:grid-cols-3">
        <section class="soft-card p-7 lg:col-span-2">
            <h2 class="text-xl font-black text-slate-950">What you’ll learn</h2>
            <div class="mt-5 grid gap-4 text-sm font-semibold text-slate-600 md:grid-cols-2">
                @foreach(['Python programming fundamentals','Data manipulation with Pandas & NumPy','Data visualization with Matplotlib','Machine learning with Scikit-learn','Build real-world projects'] as $learn)
                    <div class="flex gap-3"><x-icon name="check" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" /> {{ $learn }}</div>
                @endforeach
            </div>
        </section>

        <aside class="soft-card p-7">
            <h2 class="text-xl font-black text-slate-950">Course Includes</h2>
            <ul class="mt-5 space-y-4 text-sm font-semibold text-slate-600">
                <li class="flex gap-3"><x-icon name="play" class="h-5 w-5 text-indigo-600" /> {{ $course['duration'] }} on-demand video</li>
                <li class="flex gap-3"><x-icon name="brain" class="h-5 w-5 text-indigo-600" /> Assignments & quizzes</li>
                <li class="flex gap-3"><x-icon name="certificate" class="h-5 w-5 text-indigo-600" /> Certificate of completion</li>
                <li class="flex gap-3"><x-icon name="book" class="h-5 w-5 text-indigo-600" /> Lifetime access</li>
            </ul>
        </aside>
    </div>

    <section class="soft-card mt-6 p-7">
        <h2 class="text-xl font-black text-slate-950">About this course</h2>
        <p class="mt-3 max-w-4xl text-sm font-medium leading-7 text-slate-500">{{ $course['description'] }} The learning flow is designed to move you from basic understanding into applied practice with clear milestones and project-based outcomes.</p>
    </section>
</div>
@endsection
