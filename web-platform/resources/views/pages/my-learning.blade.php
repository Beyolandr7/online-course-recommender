@extends('layouts.dashboard')

@section('title', 'My Learning | CourseRecommend')

@section('page-content')
@php
    $tabs             = $tabs ?? [];
    $courses          = $courses ?? [];
    $headerStats      = $headerStats ?? [];
    $achievements     = $achievements ?? [];
    $recommendedCourse = $recommendedCourse ?? null;

    $statusColors = [
        'in_progress' => ['bg' => 'bg-indigo-50',  'text' => 'text-indigo-600',  'label' => 'In Progress'],
        'completed'   => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'label' => 'Completed'],
        'not_started' => ['bg' => 'bg-slate-100',  'text' => 'text-slate-500',   'label' => 'Not Started'],
    ];
@endphp


<div class="px-1 pb-10 font-[DM_Sans,sans-serif]"
     x-data="{
         activeTab: (new URLSearchParams(window.location.search)).get('tab') || 'All',
         wishlist:[],
         courses: @js($courses),
         toggleWishlist(id) {
             if (this.wishlist.includes(id)) {
                 this.wishlist = this.wishlist.filter(item => item !== id);
             } else {
                 this.wishlist.push(id);
             }
         },
         isWishlist(id) {
             return this.wishlist.includes(id);
         },
         countTabCourses(tab) {
             if (tab === 'All') return this.courses.length;
             if (tab === 'In Progress') return this.courses.filter(c => c.status_key === 'in_progress').length;
             if (tab === 'Completed') return this.courses.filter(c => c.status_key === 'completed').length;
             if (tab === 'Wishlist') return this.courses.filter(c => this.isWishlist(c.id)).length;
             return 0;
         }
     }">

    {{-- ── HEADING ── --}}
    <h1 class="font-[Sora,sans-serif] text-3xl font-extrabold tracking-tight text-slate-950 md:text-4xl">
        My Learning
    </h1>
    <p class="mt-1 mb-6 text-sm text-slate-500">
        Keep up the momentum — you're doing great!
    </p>

    {{-- ── STAT CARDS ── --}}
    <div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
        @foreach ($headerStats as $stat)
            <div class="flex items-center gap-3 rounded-xl border border-slate-100 bg-white px-4 py-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl
                    {{ $loop->first ? 'bg-indigo-50 text-indigo-500' : ($loop->index === 1 ? 'bg-amber-50 text-amber-500' : 'bg-emerald-50 text-emerald-600') }}">
                    <x-icon name="{{ $stat['icon'] }}" class="h-4.5 w-4.5" />
                </span>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">
                        {{ $stat['label'] }}
                    </p>
                    <p class="font-[Sora,sans-serif] text-base font-black text-slate-950 leading-none">
                        {{ $stat['value'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── MAIN GRID ── --}}
    <div class="grid gap-5 xl:grid-cols-[1fr_240px]">

        {{-- ── LEFT: Tabs + Course List ── --}}
        <section class="min-w-0">

            {{-- Pill Tabs --}}
            <div class="mb-5 flex w-fit gap-1 rounded-xl bg-slate-100 p-1" x-cloak>
                @foreach ($tabs as $index => $tab)
                    <button type="button"
                        @click="activeTab = '{{ $tab }}'"
                        :class="activeTab === '{{ $tab }}'
                            ? 'bg-white text-slate-950 shadow-sm ring-1 ring-slate-200'
                            : 'text-slate-500 hover:text-slate-700'"
                        class="rounded-lg px-4 py-1.5 text-xs font-semibold transition">
                        {{ $tab }}
                    </button>
                @endforeach
            </div>

            {{-- Course Cards --}}
            @if(count($courses) > 0)
                <div class="space-y-3">
                    @foreach ($courses as $course)
                    @php
                        $progress  = $course['progress'] ?? 0;
                        $statusKey = $course['status_key'] ?? ($progress > 0 ? 'in_progress' : 'not_started');
                        $status    = $course['status'] ?? '';
                        $s         = $statusColors[$statusKey] ?? $statusColors['not_started'];
                    @endphp

                    <a href="{{ route('course.detail', $course['id']) }}"class="block">
                        <article class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 transition
                            hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-50"
                            x-show="activeTab === 'All' ||
                                    (activeTab === 'In Progress' && '{{ $statusKey }}' === 'in_progress') ||
                                    (activeTab === 'Completed' && '{{ $statusKey }}' === 'completed') ||
                                    (activeTab === 'Wishlist' && isWishlist({{ $course['id'] }}))"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">

                            {{-- Left accent bar --}}
                            <span class="pointer-events-none absolute inset-y-0 left-0 w-[3px] rounded-full bg-indigo-500
                                opacity-0 transition group-hover:opacity-100"></span>

                            <div class="flex items-center gap-4">

                                {{-- Thumbnail --}}
                                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-indigo-50">
                                    <img src="{{ asset($course['thumbnail']) }}"
                                         alt="{{ $course['title'] }}"
                                         class="h-full w-full object-cover">
                                </div>

                                <div class="min-w-0 flex-1">
                                    {{-- Title row --}}
                                    <div class="flex items-start justify-between gap-3">
                                        <h2 class="line-clamp-1 font-[Sora,sans-serif] text-sm font-bold text-slate-950">
                                            {{ $course['title'] }}
                                        </h2>

                                        <button type="button"
                                            @click="toggleWishlist({{ $course['id'] }})"
                                            :class="isWishlist({{ $course['id'] }}) ? 'text-indigo-600' : 'text-indigo-300'"
                                            class="grid h-8 w-8 shrink-0 place-items-center rounded-xl transition hover:bg-indigo-50 hover:text-indigo-500"
                                            title="Bookmark Course">
                                            <x-icon name="bookmark" class="h-4 w-4" />
                                        </button>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mt-2 flex items-center gap-2">
                                        <div class="h-1 flex-1 overflow-hidden rounded-full bg-slate-100">
                                           <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500
                                                {{ $progress === 0 ? 'opacity-0' : '' }}"
                                                style="width: {{ $progress }}%">
                                            </div>
                                        </div>
                                        <span class="w-8 text-right text-[11px] font-semibold text-slate-500">
                                            {{ $progress }}%
                                        </span>
                                    </div>

                                    {{-- Meta row: status text + badge --}}
                                    <div class="mt-1.5 flex items-center justify-between">
                                    <p class="text-[11px] text-slate-400">
                                        {{ $status }}
                                    </p>
                                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-semibold
                                            {{ $s['bg'] }} {{ $s['text'] }}">
                                            {{ $s['label'] }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </article>
                    </a>
                    @endforeach

                    {{-- Empty States inside list --}}
                    <div x-show="countTabCourses(activeTab) === 0" class="rounded-2xl border border-slate-100 bg-white px-6 py-12 text-center" x-cloak>
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                            <x-icon name="school" class="h-6 w-6" />
                        </span>
                        <p class="mt-3 font-[Sora,sans-serif] text-base font-bold text-slate-950" x-text="'No courses in ' + activeTab"></p>
                        <p class="mt-1 text-xs text-slate-400">You don't have any courses under this filter right now.</p>
                        <div class="mt-5" x-show="activeTab === 'Wishlist'">
                            <a href="{{ route('explore') }}" class="btn-secondary py-2 px-4 text-xs font-semibold">
                                Browse Courses
                            </a>
                        </div>
                    </div>

                </div>
            @else
                <div class="rounded-2xl border border-slate-100 bg-white px-6 py-10 text-center">
                    <p class="font-[Sora,sans-serif] text-lg font-bold text-slate-950">No courses yet</p>
                    <p class="mt-1 text-sm text-slate-500">Your learning courses will appear here.</p>
                </div>
            @endif
        </section>

        {{-- ── RIGHT: Sidebar ── --}}
        <aside class="space-y-4">

            {{-- Recommended Courses --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-[Sora,sans-serif] text-xs font-black text-slate-950">
                        Recommended For You
                    </h3>
                    <a href="{{ route('preferences.create') }}"
                       class="text-[11px] font-bold text-indigo-600 hover:underline">
                        Update
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach ($recommendedCourses as $rec)
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 shrink-0 overflow-hidden rounded-xl bg-indigo-50">
                                <img src="{{ asset($rec['thumbnail']) }}"
                                     alt="{{ $rec['title'] }}"
                                     class="h-full w-full object-cover">
                            </div>

                            <div class="min-w-0 flex-1">
                                <h4 class="line-clamp-1 font-[Sora,sans-serif] text-xs font-bold text-slate-950">
                                    {{ $rec['title'] }}
                                </h4>
                                <div class="mt-0.5 flex items-center gap-1.5">
                                    <span class="rounded bg-indigo-50 px-1 py-0.5 text-[9px] font-bold text-indigo-600">
                                        {{ $rec['level'] }}
                                    </span>
                                    <span class="text-[10px] font-semibold text-amber-500">
                                        ★ {{ $rec['rating'] }}
                                        <span class="font-medium text-slate-400">({{ $rec['students'] }})</span>
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('course.detail', $rec['id']) }}"
                               class="grid h-7 w-7 shrink-0 place-items-center rounded-full
                                   bg-slate-50 text-slate-600 transition hover:bg-indigo-50 hover:text-indigo-600"
                               title="View Course Detail">
                                <x-icon name="arrow-right" class="h-3 w-3" />
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </aside>
    </div>
</div>
@endsection