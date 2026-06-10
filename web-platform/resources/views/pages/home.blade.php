@extends('layouts.dashboard')

@section('title', 'Home | CourseRecommend')

@section('page-content')
<div class="pt-16 lg:pt-0">
    <section class="hero-card relative overflow-hidden">

            {{-- Auth Links inside hero card --}}
            <div class="relative z-40 mb-6 flex w-full justify-end">
                <div class="flex items-center gap-4 text-sm font-bold">
                    <a href="{{ route('login') }}" class="text-slate-500 transition hover:text-indigo-700">
                        Sign In
                    </a>
                    <span class="h-4 w-px bg-slate-200"></span>
                    <a href="{{ route('register') }}" class="text-indigo-700 transition hover:text-indigo-900">
                        Sign Up
                    </a>
                </div>
            </div>

            {{-- Background decorations --}}
            <img src="{{ asset('assets/backgrounds/bg-yellow-blob.svg') }}" 
                alt="" aria-hidden="true" 
                class="absolute -right-10 top-8 w-80 opacity-80 z-0">

            <img src="{{ asset('assets/backgrounds/bg-circle-ring.svg') }}" 
                alt="" aria-hidden="true" 
                class="absolute -left-20 top-24 w-72 opacity-70 z-0">

            <div class="relative z-10 grid items-center gap-8 lg:grid-cols-[1.05fr_.95fr]">
            <div>
                <a href="{{ route('preferences.create') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-white/80 px-4 py-2 text-sm font-bold text-indigo-700 shadow-sm transition hover:bg-indigo-50 hover:scale-105">

                    <x-icon name="sparkle" class="h-4 w-4" />

                    Personalized course discovery
    
                </a>    

                <h1 class="mt-6 max-w-3xl text-5xl font-black leading-[1.02] tracking-tight text-slate-950 md:text-6xl">
                    Level up your skills,<br>
                    Discover courses<br>
                    <span class="relative inline-block text-indigo-700">you’ll love.<span class="absolute -bottom-2 left-0 h-2 w-full rounded-full bg-indigo-200"></span></span>
                </h1>

                <p class="mt-6 max-w-xl text-base font-medium leading-relaxed text-slate-500 md:text-lg">
                    Find per    sonalized course recommendations based on your major, starting skill level, interests, and target level.
                </p>

                <form action="{{ route('explore') }}" method="GET" class="mt-7 flex max-w-2xl overflow-hidden rounded-[26px] border border-indigo-100 bg-white shadow-sm focus-within:ring-4 focus-within:ring-indigo-100">
                    <label class="sr-only" for="home-search">Search courses</label>
                    <input id="home-search" name="q" class="min-w-0 flex-1 px-5 py-4 text-sm outline-none" placeholder="Search for courses, skills, topics...">
                    <button class="grid w-16 place-items-center bg-indigo-600 text-white transition hover:bg-indigo-700" aria-label="Search">
                        <x-icon name="search" class="h-5 w-5" />
                    </button>
                </form>

               <div class="mt-5 flex flex-wrap gap-2.5">
                    @foreach($skillCategories as $cat)
                        <a href="{{ route('explore', ['skill' => $cat]) }}"
                        class="rounded-full border border-indigo-100 bg-white px-5 py-3 text-sm font-bold text-slate-600 shadow-sm transition hover:border-indigo-300 hover:text-indigo-700">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden justify-center lg:flex">
                <img src="{{ asset('assets/illustrations/student-hero.png') }}" alt="Student learning with laptop illustration" class="w-full max-w-[470px] drop-shadow-2xl">
            </div>
        </div>
    </section>

    <section class="mt-8">
        <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-950">Explore Courses to Skill Up ✨</h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Discover online courses across skills, platforms, and learning levels.</p>
            </div>
            <a href="{{ route('explore') }}" class="btn-secondary">View all</a>
        </div>

        <div class="grid auto-rows-fr gap-5 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5">
            @foreach($courses as $course)
               <x-course-card :course="$course" :show-match="false" />
            @endforeach 
        </div>
    </section>
</div>
@endsection 