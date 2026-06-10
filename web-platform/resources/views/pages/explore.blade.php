@extends('layouts.dashboard')
@section('title', 'Explore Courses | CourseRecommend')
@section('page-content')
<div class="pt-16 lg:pt-0">

    {{-- Hero Section --}}
    <section class="soft-card relative overflow-hidden p-7 md:p-9">
        <img src="{{ asset('assets/backgrounds/bg-cloud.svg') }}" alt="" aria-hidden="true"
            class="absolute right-0 top-0 w-96 opacity-55">
        <div class="relative z-10 max-w-4xl">
            <a href="{{ route('home') }}"
                class="mb-5 inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-700">
                <x-icon name="arrow" class="h-4 w-4 rotate-180" /> Back to Home
            </a>
            <h1 class="text-4xl font-black tracking-tight text-slate-950 md:text-5xl">Explore Courses</h1>
            <p class="mt-2 font-medium text-slate-500">Temukan kursus terbaik di berbagai kategori, level, dan durasi.</p>

            <form action="{{ route('explore') }}" method="GET"
                class="mt-6 flex max-w-3xl overflow-hidden rounded-[24px] border border-indigo-100 bg-white shadow-sm focus-within:ring-4 focus-within:ring-indigo-100">
               @if($selectedLevel)
                    <input type="hidden" name="level" value="{{ $selectedLevel }}">
                @endif

                @if($selectedSkill)
                    <input type="hidden" name="skill" value="{{ $selectedSkill }}">
                @endif

                @if($selectedPlatform)
                    <input type="hidden" name="platform" value="{{ $selectedPlatform }}">
                @endif  

                @if($isRecommended)
                    <input type="hidden" name="sort" value="recommended">
                @endif
                <label class="sr-only" for="explore-search">Search courses</label>
                <input id="explore-search" name="q" value="{{ $searchQuery }}"
                    class="min-w-0 flex-1 px-5 py-4 text-sm outline-none"
                    placeholder="Search courses or topics...">
                <button type="submit" class="grid w-14 place-items-center text-indigo-700 hover:bg-indigo-50 transition"
                    aria-label="Search">
                    <x-icon name="search" class="h-5 w-5" />
                </button>
            </form>
        </div>
    </section>

    {{-- Filter Row --}}
    <div class="my-6 space-y-3">

        {{-- Row 1: Action chips --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('explore', array_merge(request()->except('sort'), $isRecommended ? [] : ['sort' => 'recommended'])) }}"
            class="filter-chip {{ $isRecommended ? 'bg-indigo-600 !text-white border-indigo-600' : '' }}">
                Sort: Recommended
            </a>

            @if($searchQuery || $selectedSkill || $selectedLevel || $selectedPlatform || $isRecommended)
                <a href="{{ route('explore') }}" class="filter-chip !border-red-200 !text-red-500 hover:!bg-red-50">
                    Reset
                </a>
            @endif
        </div>

        {{-- Row 2: Platform filter --}}
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-bold text-slate-500">Platform:</span>

            <a href="{{ route('explore', request()->except('platform')) }}"
            class="filter-chip {{ empty($selectedPlatform) ? 'bg-indigo-600 !text-white border-indigo-600' : '' }}">
                All
            </a>

            <a href="{{ route('explore', array_merge(request()->except('platform'), ['platform' => 'Coursera'])) }}"
            class="filter-chip {{ $selectedPlatform === 'Coursera' ? 'bg-indigo-600 !text-white border-indigo-600' : '' }}">
                Coursera
            </a>

            <a href="{{ route('explore', array_merge(request()->except('platform'), ['platform' => 'edX'])) }}"
            class="filter-chip {{ $selectedPlatform === 'edX' ? 'bg-indigo-600 !text-white border-indigo-600' : '' }}">
                edX
            </a>
        </div>


    </div>

    {{-- Result count --}}
    <p class="mb-4 text-sm font-medium text-slate-500">
        Menampilkan <span class="font-bold text-slate-800">{{ count($courses) }}</span> kursus
        @if($searchQuery) untuk "<span class="text-indigo-700">{{ $searchQuery }}</span>"@endif
        @if($selectedSkill) · <span class="text-indigo-700">{{ $selectedSkill }}</span>@endif
        @if($selectedLevel) · <span class="text-indigo-700">{{ $selectedLevel }}</span>@endif
    </p>

    {{-- Course Grid --}}
    @if(count($courses) > 0)
        <div id="course-list" class="scroll-mt-24 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($courses as $course)
                <x-course-card :course="$course" :show-match="$isRecommended" />
            @endforeach
        </div>
    @else
        <div id="course-list" class="scroll-mt-24 flex min-h-[520px] w-full items-center justify-center">
            <div class="flex w-full max-w-md flex-col items-center justify-center text-center">
                <div class="rounded-full bg-white/70 p-6 shadow-sm">
                    <x-icon name="search" class="h-14 w-14 text-slate-300" />
                </div>

                <p class="mt-6 text-lg font-bold text-slate-400">
                    Tidak ada kursus ditemukan
                </p>

                <p class="mt-1 text-sm text-slate-400">
                    Coba kata kunci lain atau reset filter.
                </p>

                <a href="{{ route('explore') }}" class="btn-secondary mt-6 inline-flex">
                    Reset
                </a>
            </div>
        </div>
    @endif

</div>

@if($isRecommended)
<script>    
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        const target = document.getElementById('course-list');
        if (!target) return;
        const offset = target.getBoundingClientRect().top + window.scrollY - 96;
        window.scrollTo({ top: offset, behavior: 'smooth' });
    }, 150);
});
</script>
@endif

@endsection