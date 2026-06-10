@extends('layouts.dashboard')

@section('title', 'Profile | CourseRecommend')

@section('page-content')
@php
    $profile = $profile ?? [];
    $skills = $skills ?? [];
@endphp

<div class="px-1 pb-8 pt-14 font-[DM_Sans,sans-serif] lg:pt-0">
    @if(session('success'))
        <div class="mb-4 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 xl:grid-cols-[1fr_330px]">
        <section class="rounded-[24px] border border-slate-100 bg-white p-6 shadow-sm">


            <h1 class="font-[Sora,sans-serif] text-3xl font-extrabold tracking-tight text-slate-950 md:text-4xl">
            Your Profile
            </h1>


            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <br></br>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="name" class="block text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Name
                        </label>
                        <input
                            id="name"
                            name="name"
                            value="{{ old('name', $profile['name'] ?? auth()->user()->name) }}"
                            class="input-modern"
                            required
                        >
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Email
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $profile['email'] ?? auth()->user()->email) }}"
                            class="input-modern"
                            required
                        >
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-6 py-3 text-sm font-black text-white transition hover:bg-indigo-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </section>

        <aside class="space-y-4">
            <div class="rounded-[24px] border border-slate-100 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-black text-slate-950">Learning Summary</h3>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-indigo-50 p-3 text-center">
                        <p class="font-[Sora,sans-serif] text-xl font-black text-indigo-700">
                            {{ $learningCount ?? 0 }}
                        </p>
                        <p class="mt-1 text-[11px] font-bold text-slate-500">
                            Input Form
                        </p>
                    </div>

                    <div class="rounded-2xl bg-emerald-50 p-3 text-center">
                        <p class="font-[Sora,sans-serif] text-xl font-black text-emerald-700">
                            {{ $completedCount ?? 0 }}
                        </p>
                        <p class="mt-1 text-[11px] font-bold text-slate-500">
                            Completed
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-[24px] border border-slate-100 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h3 class="text-sm font-black text-slate-950">In Progress Skills</h3>

                    <a
                        href="{{ route('my-learning', ['tab' => 'In Progress']) }}"
                        class="text-xs font-black text-indigo-600 hover:underline"
                    >
                        View all
                    </a>
                </div>

                @if(count($skills) > 0)
                    <div class="space-y-3">
                        @foreach($skills as $skill)
                            <a
                                href="{{ route('roadmap.show', $skill['id']) }}"
                                class="block rounded-2xl border border-slate-100 p-3 transition hover:border-indigo-200 hover:bg-indigo-50/40"
                            >
                                <div class="flex items-center justify-between gap-3">
                                    <p class="line-clamp-2 text-sm font-black text-slate-800">
                                        {{ $skill['name'] }}
                                    </p>

                                    <span class="text-xs font-black text-slate-400">
                                        {{ $skill['percent'] }}%
                                    </span>
                                </div>

                                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                    <div
                                        class="h-full rounded-full bg-indigo-600"
                                        style="width: {{ $skill['percent'] }}%"
                                    ></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center">
                        <p class="text-sm font-bold text-slate-500">
                            Belum ada skill berjalan.
                        </p>

                        <a
                            href="{{ route('preferences.create') }}"
                            class="mt-3 inline-flex text-sm font-black text-indigo-600 hover:underline"
                        >
                            Isi Preference Form
                        </a>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection