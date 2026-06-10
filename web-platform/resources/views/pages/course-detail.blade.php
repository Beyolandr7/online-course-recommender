@extends('layouts.dashboard')

@section('title', $course['title'].' | CourseRecommend')

@section('page-content')
<div class="pt-16 lg:pt-0">
    <a href="{{ url()->previous() }}" class="mb-5 inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-700">
    <x-icon name="arrow" class="h-4 w-4 rotate-180" /> Back
    </a>

    <section class="relative overflow-hidden rounded-[34px] bg-slate-950 p-7 text-white shadow-xl shadow-indigo-100 md:p-8">
        <img src="{{ asset('assets/backgrounds/bg-stars.svg') }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-55">
        <div class="relative z-10 grid items-center gap-6 lg:grid-cols-[160px_1fr_auto]">
            <img src="{{ asset($course['thumbnail']) }}" alt="{{ $course['title'] }} thumbnail" class="h-36 w-36 rounded-[28px] bg-white object-cover p-1">

            <div>
                <h1 class="mt-4 max-w-3xl text-3xl font-black leading-tight md:text-4xl">{{ $course['title'] }}</h1>
                <p class="mt-3 font-medium text-white/70">
                    {{ $course['platform'] }}
                    @if(!empty($course['level']))
                        • {{ $course['level'] }}
                    @endif
                </p>
                <div class="mt-4 flex flex-wrap gap-4 text-sm font-bold text-white/90">
                    <span>{{ $course['match'] }}% match for you</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if(!empty($course['url']))
                    <a href="{{ route('course.go', ['id' => $course['id'], 'path_id' => $learningPathId ?? null]) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-7 py-4 font-black text-white shadow-lg shadow-indigo-900/30 transition hover:bg-indigo-500">Go to Course</a>
                @else
                    <button class="rounded-2xl bg-indigo-600 px-7 py-4 font-black text-white shadow-lg shadow-indigo-900/30 transition hover:bg-indigo-500">Enroll Now</button>
                @endif

                @if(!empty($learningPathId))
                    @if($isCompleted)
                        <span class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-7 py-4 font-black text-white shadow-lg shadow-emerald-900/20">
                            <x-icon name="check" class="h-5 w-5"/>
                            Completed
                        </span>
                    @else
                        <form action="{{ route('roadmap.complete', ['learningPath' => $learningPathId, 'courseId' => $course['id']]) }}" method="POST" class="inline-flex">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl border border-white bg-transparent px-7 py-4 font-black text-white transition hover:bg-white/10 cursor-pointer">
                                Mark as Complete
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </section>
@php
    $skillItems = collect(explode(',', $course['skills'] ?? ''))
        ->map(fn($skill) => trim($skill))
        ->filter()
        ->unique()
        ->take(8)
        ->values();

    $platform = $course['platform'] ?? $course['provider'] ?? 'Online Platform';
    $level = $course['level'] ?? null;
@endphp

<div class="mt-7 grid gap-6 lg:grid-cols-3">
    <section class="soft-card p-7 lg:col-span-2">
        <h2 class="text-xl font-black text-slate-950">Skills you’ll gain</h2>

        @if($skillItems->isNotEmpty())
            <div class="mt-5 grid gap-4 text-sm font-semibold text-slate-600 md:grid-cols-2">
                @foreach($skillItems as $skill)
                    <div class="flex gap-3">
                        <x-icon name="check" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" />
                        <span>{{ $skill }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="mt-4 text-sm font-medium text-slate-500">
                Skill information is not available for this course.
            </p>
        @endif
    </section>

    <aside class="soft-card p-7">
        <h2 class="text-xl font-black text-slate-950">Course Information</h2>

        <ul class="mt-5 space-y-4 text-sm font-semibold text-slate-600">
            <li class="flex gap-3">
                <x-icon name="book" class="h-5 w-5 text-indigo-600" />
                Online course material
            </li>

            <li class="flex gap-3">
                <x-icon name="sparkle" class="h-5 w-5 text-indigo-600" />
                Skill-based learning
            </li>

            @if(!empty($level))
            <li class="flex gap-3">
                <x-icon name="brain" class="h-5 w-5 text-indigo-600" />
                {{ $level }} level
            </li>
            @endif

            <li class="flex gap-3">
                <x-icon name="certificate" class="h-5 w-5 text-indigo-600" />
                Available on {{ $platform }}
            </li>
        </ul>
    </aside>
</div>

   <section class="soft-card mt-6 p-7">
    <h2 class="text-xl font-black text-slate-950">About this course</h2>

    <p class="mt-3 w-full text-sm font-medium leading-7 text-slate-500 text-justify">
        {{ $course['description'] ?: 'No description available for this course.' }}
    </p>
</section>
</div>
@endsection
