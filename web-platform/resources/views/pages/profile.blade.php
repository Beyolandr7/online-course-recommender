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
        <div class="space-y-4">

            <section class="rounded-[24px] border border-slate-100 bg-white p-6 shadow-sm" x-data="{ editing: {{ $errors->any() ? 'true' : 'false' }} }">

                {{-- Header --}}
                <div class="flex items-center justify-between gap-4">
                    <h1 class="font-[Sora,sans-serif] text-3xl font-extrabold tracking-tight text-slate-950 md:text-4xl">
                        Your Profile
                    </h1>

                    {{-- Edit button (tampil saat tidak editing) --}}
                    <button
                        x-show="!editing"
                        @click="editing = true"
                        type="button"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-black text-slate-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828A2 2 0 0110 16H8v-2a2 2 0 01.586-1.414z"/>
                        </svg>
                        Edit Profile
                    </button>

                    {{-- Cancel button (tampil saat editing) --}}
                    <button
                        x-show="editing"
                        @click="editing = false"
                        type="button"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-black text-slate-500 transition hover:bg-slate-100">
                        Cancel
                    </button>
                </div>

                {{-- Read-only view --}}
                <div x-show="!editing" class="mt-6 space-y-4">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Name</p>
                        <p class="mt-1 text-base font-bold text-slate-800">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-widest text-slate-400">Email</p>
                        <p class="mt-1 text-base font-bold text-slate-800">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                {{-- Edit form --}}
                <form
                    x-show="editing"
                    action="{{ route('profile.update') }}"
                    method="POST"
                    class="mt-6">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2">Name</label>
                        <input id="name" name="name"
                            value="{{ old('name', auth()->user()->name) }}"
                            class="input-modern" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mt-4">
                        <label for="email" class="block text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2">Email</label>
                        <input id="email" name="email" type="email"
                            value="{{ old('email', auth()->user()->email) }}"
                            class="input-modern" required>
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Divider --}}
                    <div class="my-6 border-t border-slate-100"></div>

                    {{-- Change Password --}}
                    <h2 class="font-[Sora,sans-serif] text-lg font-extrabold tracking-tight text-slate-950">
                        Change Password
                    </h2>
                    <p class="mt-1 text-sm font-medium text-slate-400">
                        Leave blank if you don't want to change your password.
                    </p>

                    {{-- Current Password --}}
                    <div class="mt-4">
                        <label for="current_password" class="block text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2">Current Password</label>
                        <input id="current_password" name="current_password" type="password"
                            class="input-modern" autocomplete="current-password">
                        @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mt-4">
                        <label for="password" class="block text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2">New Password</label>
                        <input id="password" name="password" type="password"
                            class="input-modern" autocomplete="new-password">
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mt-4">
                        <label for="password_confirmation" class="block text-[11px] font-black uppercase tracking-widest text-slate-400 mb-2">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="input-modern" autocomplete="new-password">
                    </div>

                    {{-- Submit --}}
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-6 py-3 text-sm font-black text-white transition hover:bg-indigo-700">
                            Save Changes
                        </button>
                    </div>

                </form>
            </section>

        </div>

        {{-- Aside --}}
        <aside class="space-y-4">
            <div class="rounded-[24px] border border-slate-100 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-black text-slate-950">Learning Summary</h3>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-indigo-50 p-3 text-center">
                        <p class="font-[Sora,sans-serif] text-xl font-black text-indigo-700">{{ $learningCount ?? 0 }}</p>
                        <p class="mt-1 text-[11px] font-bold text-slate-500">Input Form</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-3 text-center">
                        <p class="font-[Sora,sans-serif] text-xl font-black text-emerald-700">{{ $completedCount ?? 0 }}</p>
                        <p class="mt-1 text-[11px] font-bold text-slate-500">Completed</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[24px] border border-slate-100 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h3 class="text-sm font-black text-slate-950">In Progress Skills</h3>
                    <a href="{{ route('my-learning', ['tab' => 'In Progress']) }}"
                        class="text-xs font-black text-indigo-600 hover:underline">View all</a>
                </div>

                @if(count($skills) > 0)
                    <div class="space-y-3">
                        @foreach($skills as $skill)
                            <a href="{{ route('roadmap.show', $skill['id']) }}"
                                class="block rounded-2xl border border-slate-100 p-3 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="line-clamp-2 text-sm font-black text-slate-800">{{ $skill['name'] }}</p>
                                    <span class="text-xs font-black text-slate-400">{{ $skill['percent'] }}%</span>
                                </div>
                                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-indigo-600" style="width: {{ $skill['percent'] }}%"></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center">
                        <p class="text-sm font-bold text-slate-500">No skills in progress.</p>
                        <a href="{{ route('preferences.create') }}"
                            class="mt-3 inline-flex text-sm font-black text-indigo-600 hover:underline">
                            Fill in the Preference Form to get started
                        </a>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection