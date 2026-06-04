@extends('layouts.dashboard')

@section('title', 'Explore Courses | CourseRecommend')

@section('page-content')
<div class="pt-16 lg:pt-0">
    <section class="soft-card relative overflow-hidden p-7 md:p-9">
        <img src="{{ asset('assets/backgrounds/bg-cloud.svg') }}" alt="" aria-hidden="true" class="absolute right-0 top-0 w-96 opacity-55">
        <div class="relative z-10 max-w-4xl">
            <a href="{{ route('home') }}" class="mb-5 inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-700"><x-icon name="arrow" class="h-4 w-4 rotate-180" /> Back to Home</a>
            <h1 class="text-4xl font-black tracking-tight text-slate-950 md:text-5xl">Explore Courses</h1>
            <p class="mt-2 font-medium text-slate-500">Temukan kursus terbaik di berbagai kategori, level, dan durasi.</p>

            <div class="mt-6 flex max-w-3xl overflow-hidden rounded-[24px] border border-indigo-100 bg-white shadow-sm">
                <label class="sr-only" for="explore-search">Search courses</label>
                <input id="explore-search" class="min-w-0 flex-1 px-5 py-4 text-sm outline-none" placeholder="Search courses or topics...">
                <button class="grid w-14 place-items-center text-indigo-700" aria-label="Search"><x-icon name="search" class="h-5 w-5" /></button>
            </div>
        </div>
    </section>

 <div class="my-6 flex flex-wrap gap-3">
    <button class="filter-chip">AI Categories</button>
    <button class="filter-chip">All Levels</button>
    <button class="filter-chip">Duration</button>
    <a href="{{ route('explore', ['sort' => 'recommended']) }}"
       class="filter-chip {{ $isRecommended ? 'bg-indigo-600 text-white border-indigo-600' : '' }}">
        Sort: Recommended
    </a>
    <button class="filter-chip">Filter</button>
</div>

<div id="course-list" class="scroll-mt-24 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
    @foreach($courses as $course)
        <x-course-card :course="$course" :show-match="$isRecommended" />
    @endforeach
</div>

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

//     const target = document.getElementById('course-list');
//     const main   = document.getElementById('main-scroll');
//     if (!target || !main) return;
//     setTimeout(() => {   
//         const mainRect   = main.getBoundingClientRect();
//         const targetRect = target.getBoundingClientRect();
//         const offset     = main.scrollTop + (targetRect.top - mainRect.top) - 96;
//         main.scrollTo({ top: offset, behavior: 'smooth' });
//     }, 150);
</script>
@endif

@endsection

