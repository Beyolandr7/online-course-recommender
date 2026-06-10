@extends('layouts.dashboard')

@section('title', 'Learning Roadmap | CourseRecommend')

@section('page-content')
@php
    $steps = $steps ?? [];
    $learningPath = $learningPath ?? null;
@endphp

<div class="pt-16 lg:pt-0">
    <section class="soft-card relative overflow-hidden p-7 md:p-10">

        {{-- Background doodle --}}
        <img
            src="{{ asset('assets/backgrounds/bg-doodle-stars.svg') }}"
            alt=""
            aria-hidden="true"
            class="pointer-events-none absolute right-0 top-0 w-96 opacity-35"
        >

        <div class="relative z-10">

            {{-- ── HEADER ── --}}
            <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                <div>
                    <a href="{{ route('my-learning') }}"
                    class="mb-5 inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-700">
                        <x-icon name="arrow" class="h-4 w-4 rotate-180" />
                        Back to My Learning
                    </a>
                    <h1 class="text-4xl font-black tracking-tight text-slate-950">
                        {{ $learningPath?->title ?? 'Your Learning Roadmap' }}
                    </h1>

                    <p class="mt-2 font-medium text-slate-500">
                        @if($learningPath) {{ $learningPath->initial_level }} to {{ $learningPath->target_level }} • {{ $learningPath->interest }} @else Personalized path will appear after you submit Preference Form. @endif
                    </p>
                </div>

                {{-- Flag --}}
                <div class="pointer-events-none shrink-0 self-start">
                    <svg width="64" height="78" viewBox="0 0 78 92" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="24" y1="10" x2="24" y2="78" stroke="#475569" stroke-width="3" stroke-linecap="round"/>
                        <ellipse cx="24" cy="82" rx="11" ry="4.5" fill="#CBD5E1"/>
                        <path d="M24 14 L58 24 L24 40 Z" fill="#22C55E"/>
                        <path d="M24 14 L58 24 L24 40 Z" fill="none" stroke="#16A34A" stroke-width="1.2"/>
                    </svg>
                </div>
            </div>

            {{-- ── PROGRESS SUMMARY ── --}}
            @php
                $totalSteps      = count($steps);
                $completedCount  = collect($steps)->where('status', 'Completed')->count();
                $inProgressCount = collect($steps)->where('status', 'In Progress')->count();
                $progressPct     = $totalSteps > 0 ? round(($completedCount / $totalSteps) * 100) : 0;
            @endphp

            <div class="mb-8 flex flex-wrap items-stretch gap-3">

                {{-- Progress bar --}}
                <div class="flex min-w-[200px] flex-1 items-center gap-4 rounded-2xl border border-indigo-100 bg-white px-5 py-4 shadow-sm">
                    <div class="flex-1">
                        <p class="mb-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                            Overall Progress
                        </p>

                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="h-full rounded-full bg-indigo-600 transition-all duration-700"
                                style="width: {{ $progressPct }}%">
                            </div>
                        </div>
                    </div>

                    <span class="text-2xl font-black text-slate-950">
                        {{ $progressPct }}%
                    </span>
                </div>

                {{-- Completed --}}
                <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-emerald-500 text-white">
                        <x-icon name="check" class="h-4 w-4"/>
                    </span>

                    <div>
                        <p class="text-xs font-bold text-slate-500">Completed</p>
                        <p class="text-xl font-black text-slate-950">
                            {{ $completedCount }}/{{ $totalSteps }}
                        </p>
                    </div>
                </div>

                {{-- In Progress --}}
                <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-amber-400 text-white">
                        <x-icon name="player-play" class="h-4 w-4"/>
                    </span>

                    <div>
                        <p class="text-xs font-bold text-slate-500">In Progress</p>
                        <p class="text-xl font-black text-slate-950">
                            {{ $inProgressCount }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Jika steps kosong --}}
            @if($totalSteps === 0)
                <div class="rounded-[28px] border border-indigo-100 bg-white px-6 py-10 text-center shadow-sm">
                    <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-indigo-50 text-indigo-600">
                        <x-icon name="sparkle" class="h-6 w-6" />
                    </div>

                    <h2 class="mt-4 text-xl font-black text-slate-950">
                        Roadmap belum tersedia
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm font-medium leading-relaxed text-slate-500">
                        Belum ada roadmap. Isi Preference Form dulu, lalu sistem akan menyimpan input kamu ke database dan membuat learning path dari rekomendasi FastAPI.
                    </p>
                </div>
            @else

                {{-- ── TIMELINE ── --}}
                <div class="space-y-0">
                    @foreach($steps as $index => $item)
                        @php
                            $status     = $item['status'] ?? 'Not Started';
                            $completed  = $status === 'Completed';
                            $active     = $status === 'In Progress';
                            $notStarted = $status === 'Not Started';
                            $isLast     = $index === count($steps) - 1;
                        @endphp

                        {{-- Row: [circle] + [line + card] --}}
                        <div class="flex items-stretch gap-0">

                            {{-- Left column: circle + vertical line segment --}}
                            <div class="flex w-11 shrink-0 flex-col items-center">

                                {{-- Circle --}}
                                <div class="z-10 mt-5 grid h-11 w-11 shrink-0 place-items-center rounded-full text-sm font-black ring-2 ring-white
                                    {{ $completed  ? 'bg-emerald-500 text-white' : '' }}
                                    {{ $active     ? 'bg-indigo-600 text-white' : '' }}
                                    {{ $notStarted ? 'bg-slate-200 text-slate-500' : '' }}">

                                    @if($completed)
                                        <x-icon name="check" class="h-5 w-5"/>
                                    @else
                                        {{ $item['step'] ?? $index + 1 }}
                                    @endif
                                </div>

                                {{-- Line segment below circle --}}
                                @if(!$isLast)
                                    <div class="mt-1 w-0.5 flex-1 rounded-full bg-indigo-100"></div>
                                @endif
                            </div>

                            {{-- Right column: card --}}
                            <div class="mb-3 ml-4 flex-1">
                                <article class="rounded-[28px] border border-indigo-100 bg-white px-6 py-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md hover:shadow-indigo-100/60">

                                    {{-- Course info --}}
                                    <div class="mb-4">
                                        <h2 class="font-black text-slate-950">
                                            {{ $item['title'] ?? 'Untitled Course' }}
                                        </h2>

                                        <p class="mt-1 text-sm font-medium text-slate-500">
                                            {{ $item['description'] ?? 'No description available.' }}
                                        </p>

                                        <p class="mt-2 text-xs font-bold uppercase tracking-widest text-slate-400">
                                            {{ $item['category'] ?? 'General' }}

                                            @isset($item['duration'])
                                                &bull; {{ $item['duration'] }}
                                            @endisset

                                            @isset($item['platform'])
                                                &bull; {{ $item['platform'] }}
                                            @endisset
                                        </p>
                                    </div>

                                    {{-- Status + action row --}}
                                    <div class="flex flex-wrap items-center gap-3">

                                        {{-- Status badge --}}
                                        <span class="inline-flex h-8 shrink-0 items-center rounded-full px-3 text-xs font-black
                                            {{ $completed  ? 'bg-emerald-50 text-emerald-700' : '' }}
                                            {{ $active     ? 'bg-amber-50 text-amber-700' : '' }}
                                            {{ $notStarted ? 'bg-slate-100 text-slate-500' : '' }}">
                                            {{ $status }}
                                        </span>

                                        {{-- Action button --}}
                                        @if($completed)
                                            <a href="{{ route('course.detail', ['id' => $item['id'], 'path_id' => $learningPath->id]) }}"
                                               class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-5 text-sm font-black text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-600 active:scale-95">
                                                <x-icon name="check" class="h-4 w-4"/>
                                                Review
                                            </a>
                                        @elseif($active)
                                            <a href="{{ route('course.detail', ['id' => $item['id'], 'path_id' => $learningPath->id]) }}"
                                            class="inline-flex h-10 flex-1 items-center justify-center rounded-2xl border-2 border-indigo-600 bg-white px-5 text-sm font-black text-indigo-700 transition hover:bg-indigo-50 active:scale-95">
                                                Continue
                                            </a>
                                        @else
                                            <a href="{{ route('course.detail', ['id' => $item['id'], 'path_id' => $learningPath->id]) }}"
                                               class="inline-flex h-10 flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 text-sm font-black text-slate-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 active:scale-95">
                                                Start
                                            </a>
                                        @endif
                                    </div>

                                </article>
                            </div>
                        </div>
                    @endforeach

                    {{-- ── Finish marker ── --}}
                    <div class="flex items-center gap-4">
                        <div class="flex w-11 shrink-0 justify-center">
                            <div class="grid h-11 w-11 place-items-center rounded-full border-2 border-dashed border-indigo-300 bg-white text-lg">
                                🏁
                            </div>
                        </div>

                        <div class="ml-4 py-3">
                            <p class="text-sm font-black text-indigo-600">{{ $learningPath?->target_level ?? 'Target Level' }}</p>
                            <p class="text-xs font-medium text-slate-400">Complete all steps to reach your goal</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>
</div>
@endsection