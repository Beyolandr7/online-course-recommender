@extends('layouts.dashboard')

@section('title', 'My Learning | CourseRecommend')

@section('page-content')
@php
    $tabs = $tabs ?? ['All', 'In Progress', 'Completed'];
    $learningPaths = $learningPaths ?? [];
    $summary = $summary ?? ['total' => 0, 'in_progress' => 0, 'completed' => 0];

    $statusColors = [
        'in_progress' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'label' => 'In Progress'],
        'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'label' => 'Completed'],
    ];
@endphp

<div class="px-1 pb-10 pt-16 font-[DM_Sans,sans-serif] lg:pt-0"
     x-data="{
        activeTab: (new URLSearchParams(window.location.search)).get('tab') || 'All',
        paths: @js($learningPaths),
        countTab(tab) {
            if (tab === 'All') return this.paths.length;
            if (tab === 'In Progress') return this.paths.filter(path => path.status_key === 'in_progress').length;
            if (tab === 'Completed') return this.paths.filter(path => path.status_key === 'completed').length;
            return 0;
        }
     }">

    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-[Sora,sans-serif] text-3xl font-extrabold tracking-tight text-slate-950 md:text-4xl">
                My Learning
            </h1>
            <p class="mt-2 text-sm font-medium text-slate-500">
                Semua input Preference Form kamu tersimpan sebagai learning path di sini.
            </p>
        </div>

        <a href="{{ route('preferences.create') }}" class="primary-btn px-5 py-3 text-sm">
            New Preference
        </a>
    </div>

    <div class="mb-6 grid gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-widest text-slate-400">Total Input</p>
            <p class="mt-2 font-[Sora,sans-serif] text-3xl font-black text-slate-950">{{ $summary['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-widest text-slate-400">In Progress</p>
            <p class="mt-2 font-[Sora,sans-serif] text-3xl font-black text-indigo-700">{{ $summary['in_progress'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <p class="text-xs font-black uppercase tracking-widest text-slate-400">Completed</p>
            <p class="mt-2 font-[Sora,sans-serif] text-3xl font-black text-emerald-700">{{ $summary['completed'] }}</p>
        </div>
    </div>

    <div class="mb-5 flex w-fit gap-1 rounded-xl bg-slate-100 p-1" x-cloak>
        @foreach ($tabs as $tab)
            <button type="button"
                    @click="activeTab = '{{ $tab }}'"
                    :class="activeTab === '{{ $tab }}' ? 'bg-white text-slate-950 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
                    class="rounded-lg px-4 py-1.5 text-xs font-semibold transition">
                {{ $tab }}
            </button>
        @endforeach
    </div>

    @if(count($learningPaths) > 0)
        <div class="grid gap-4 lg:grid-cols-2">
            @foreach ($learningPaths as $path)
                @php
                    $statusKey = $path['status_key'] ?? 'in_progress';
                    $status = $statusColors[$statusKey] ?? $statusColors['in_progress'];
                @endphp

                <a href="{{ route('roadmap.show', $path['id']) }}"
                   class="group block"
                   x-show="activeTab === 'All' ||
                           (activeTab === 'In Progress' && '{{ $statusKey }}' === 'in_progress') ||
                           (activeTab === 'Completed' && '{{ $statusKey }}' === 'completed')"
                   x-transition>
                    <article class="h-full rounded-[28px] border border-slate-100 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-50">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-black {{ $status['bg'] }} {{ $status['text'] }}">
                                    {{ $status['label'] }}
                                </span>
                                <h2 class="mt-4 line-clamp-2 font-[Sora,sans-serif] text-lg font-black leading-snug text-slate-950">
                                    {{ $path['title'] }}
                                </h2>
                                <p class="mt-2 text-sm font-medium text-slate-500">
                                    {{ $path['major'] }} • {{ $path['initial_level'] }} → {{ $path['target_level'] }}
                                </p>
                            </div>

                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-indigo-50 text-indigo-600 transition group-hover:bg-indigo-600 group-hover:text-white">
                                <x-icon name="arrow-right" class="h-5 w-5" />
                            </span>
                        </div>

                        <div class="mt-5">
                            <div class="mb-2 flex items-center justify-between text-xs font-bold text-slate-400">
                                <span>Progress</span>
                                <span>{{ $path['progress'] }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-indigo-600" style="width: {{ $path['progress'] }}%"></div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                            <p class="text-xs font-bold text-slate-400">Created {{ $path['created_at'] }}</p>
                            <p class="text-xs font-black text-indigo-600">Open roadmap</p>
                        </div>
                    </article>
                </a>
            @endforeach
        </div>

        <div x-show="countTab(activeTab) === 0" class="rounded-2xl border border-slate-100 bg-white px-6 py-12 text-center" x-cloak>
            <p class="font-[Sora,sans-serif] text-base font-bold text-slate-950" x-text="'No learning path in ' + activeTab"></p>
            <p class="mt-1 text-xs text-slate-400">Belum ada data untuk filter ini.</p>
        </div>
    @else
        <div class="rounded-[28px] border border-indigo-100 bg-white px-6 py-12 text-center shadow-sm">
            <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-indigo-50 text-indigo-600">
                <x-icon name="target" class="h-6 w-6" />
            </div>
            <h2 class="mt-4 text-xl font-black text-slate-950">Belum ada learning path</h2>
            <p class="mx-auto mt-2 max-w-md text-sm font-medium leading-relaxed text-slate-500">
                Isi Preference Form terlebih dahulu. Setelah submit, datanya akan muncul sebagai card di halaman ini.
            </p>
            <a href="{{ route('preferences.create') }}" class="primary-btn mt-6 inline-flex px-5 py-3 text-sm">
                Fill Preference Form
            </a>
        </div>
    @endif
</div>
@endsection
