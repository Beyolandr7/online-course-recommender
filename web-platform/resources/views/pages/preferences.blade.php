@extends('layouts.dashboard')

@section('title', 'Preference Form | CourseRecommend')

@section('page-content')
<div class="mx-auto max-w-5xl pt-16 lg:pt-0">
    <section class="soft-card relative overflow-hidden p-7 md:p-10">

        <img src="{{ asset('assets/backgrounds/bg-doodle-stars.svg') }}"
             alt=""
             aria-hidden="true"
             class="absolute right-0 top-0 w-96 opacity-35">

        <img src="{{ asset('assets/backgrounds/bg-yellow-blob.svg') }}"
             alt=""
             aria-hidden="true"
             class="absolute -bottom-16 -left-12 w-80 opacity-50">

        <div class="relative z-10">

            <div class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-4 py-2 text-sm font-black text-indigo-700">
                <x-icon name="target" class="h-4 w-4" />
                Personalized input
            </div>

            <h1 class="mt-5 max-w-2xl text-4xl font-black leading-tight text-slate-950 md:text-5xl">
                Build your personalized learning path.
            </h1>

            <p class="mt-4 max-w-2xl text-base font-medium leading-relaxed text-slate-500">
                Fill in the following details so the system can recommend courses based on your major, initial skill level, interests, and goals.
            </p>

            <form action="{{ route('preferences.store') }}" method="POST" class="mt-9 space-y-6" novalidate>
                @csrf

                <div>
                    <label for="major" class="form-label">Jurusan</label>
                    <input id="major" name="major" type="text" value="{{ old('major') }}" placeholder="Contoh: Teknik Informatika" class="input-modern mt-2" autocomplete="organization-title">
                    @error('major') <p class="form-error">{{ $message }}</p> @enderror
                </div>

<div class="grid gap-6 md:grid-cols-2">

    {{-- Jenjang Kemahiran Awal --}}
    <div x-data="levelSelect('initial_level', '{{ old('initial_level') }}')" class="relative">
        <label class="form-label">Jenjang Kemahiran Awal</label>
        <input type="hidden" name="initial_level" :value="selected">

        <button type="button" @click="open = !open" @keydown.escape="open = false"
            :class="open ? 'ring-2 ring-indigo-400 border-indigo-400' : ''"
            class="input-modern mt-2 flex w-full items-center justify-between text-left">
            <span :class="selected ? 'text-slate-900' : 'text-slate-400'">
                <span x-text="selected || 'Pilih level awal'"></span>
            </span>
            <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-transition @click.outside="open = false"
            class="absolute z-20 mt-1 w-full overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <template x-for="opt in options" :key="opt.value">
                <button type="button" @click="select(opt.value)" :class="selected === opt.value ? 'bg-indigo-50' : 'hover:bg-slate-50'"
                    class="flex w-full items-center gap-3 border-b border-slate-100 px-4 py-3 text-left last:border-0">
                    <span :class="opt.badge" class="rounded-full px-2.5 py-0.5 text-xs font-semibold" x-text="opt.value"></span>
                    <span class="text-sm text-slate-500" x-text="opt.desc"></span>
                </button>
            </template>
        </div>

        @error('initial_level') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    {{-- Target Kemahiran --}}
    <div x-data="levelSelect('target_level', '{{ old('target_level') }}')" class="relative">
        <label class="form-label">Target Kemahiran</label>
        <input type="hidden" name="target_level" :value="selected">

        <button type="button" @click="open = !open" @keydown.escape="open = false"
            :class="open ? 'ring-2 ring-indigo-400 border-indigo-400' : ''"
            class="input-modern mt-2 flex w-full items-center justify-between text-left">
            <span :class="selected ? 'text-slate-900' : 'text-slate-400'">
                <span x-text="selected || 'Pilih target level'"></span>
            </span>
            <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-transition @click.outside="open = false"
            class="absolute z-20 mt-1 w-full overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <template x-for="opt in options" :key="opt.value">
                <button type="button" @click="select(opt.value)" :class="selected === opt.value ? 'bg-indigo-50' : 'hover:bg-slate-50'"
                    class="flex w-full items-center gap-3 border-b border-slate-100 px-4 py-3 text-left last:border-0">
                    <span class="text-sm text-slate-800" x-text="opt.value"></span>
                </button>
            </template>
        </div>

        @error('target_level') <p class="form-error">{{ $message }}</p> @enderror
    </div>

</div>

{{-- Script Alpine untuk dropdown --}}
<script>
function levelSelect(name, oldValue) {
    return {
        open: false,
        selected: oldValue || '',
        options: [
            { value: 'Beginner' },
            { value: 'Intermediate' },
            { value: 'Advanced' },
        ],
        select(val) {
            this.selected = val;
            this.open = false;
        }
    }
}
</script>

                <div>
                    <label for="interest" class="form-label">Minat</label>
                    <textarea id="interest" name="interest" rows="5" placeholder="Contoh: AI, Machine Learning, Data Science" class="input-modern mt-2 resize-none">{{ old('interest') }}</textarea>
                    @error('interest') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <button id="submit-btn" type="submit" class="primary-btn w-full py-4 text-base">
                    Generate Recommendation
                </button>
            </form>

        </div>
    </section>
</div>

{{-- ── Full-screen AI Loading Overlay ────────────────────────────────────── --}}
<div id="loading-overlay"
     class="fixed inset-0 z-[999] hidden items-center justify-center bg-white/60 backdrop-blur-md"
     aria-live="polite" aria-label="Generating recommendations">

    <div class="flex flex-col items-center gap-6 px-6 text-center">

        {{-- Animated ring + icon --}}
        <div class="relative flex h-28 w-28 items-center justify-center">
            {{-- Outer spinning ring --}}
            <svg class="absolute inset-0 h-full w-full animate-spin" viewBox="0 0 100 100" fill="none">
                <circle cx="50" cy="50" r="44" stroke="#e0e7ff" stroke-width="8"/>
                <circle cx="50" cy="50" r="44"
                        stroke="url(#ring-grad)" stroke-width="8"
                        stroke-linecap="round"
                        stroke-dasharray="138 138"
                        stroke-dashoffset="104"/>
                <defs>
                    <linearGradient id="ring-grad" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#6366f1"/>
                        <stop offset="100%" stop-color="#a78bfa"/>
                    </linearGradient>
                </defs>
            </svg>
            {{-- Centre sparkle --}}
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-600 shadow-lg shadow-indigo-300">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"/>
                </svg>
            </div>
        </div>

        {{-- Headline --}}
        <div>
            <h2 class="text-2xl font-black text-slate-900">Generating your recommendations…</h2>
            <p class="mt-1.5 text-sm font-medium text-slate-500">Our AI is finding the best courses for you</p>
        </div>

        {{-- Animated status messages --}}
        <p id="loading-status" class="min-h-[1.5rem] text-sm font-semibold text-indigo-600 transition-opacity duration-500"></p>

        {{-- Bouncing dots --}}
        <div class="flex items-center gap-2">
            <span class="h-2.5 w-2.5 animate-bounce rounded-full bg-indigo-400 [animation-delay:0ms]"></span>
            <span class="h-2.5 w-2.5 animate-bounce rounded-full bg-indigo-500 [animation-delay:150ms]"></span>
            <span class="h-2.5 w-2.5 animate-bounce rounded-full bg-indigo-600 [animation-delay:300ms]"></span>
        </div>

    </div>
</div>

<script>
(function () {
    const form    = document.querySelector('form[action*="preferences"]');
    const overlay = document.getElementById('loading-overlay');
    const statusEl= document.getElementById('loading-status');
    const btn     = document.getElementById('submit-btn');

    const messages = [
        'Analysing your interests…',
        'Searching across 60,000+ courses…',
        'Matching your skill level…',
        'Ranking top recommendations…',
        'Almost there…',
        'Putting the finishing touches…',
    ];

    let msgIndex   = 0;
    let msgInterval;

    function showNextMessage() {
        // Fade out → update text → fade in
        statusEl.style.opacity = '0';
        setTimeout(() => {
            statusEl.textContent = messages[msgIndex % messages.length];
            statusEl.style.opacity = '1';
            msgIndex++;
        }, 300);
    }

    function showOverlay() {
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        btn.disabled = true;
        btn.classList.add('opacity-60', 'cursor-not-allowed');

        // Show first message immediately, then cycle
        showNextMessage();
        msgInterval = setInterval(showNextMessage, 2800);
    }

    if (form && overlay) {
        form.addEventListener('submit', function (e) {
            // Only show overlay if basic browser validation passes
            if (!form.checkValidity()) return;
            showOverlay();
        });
    }

    // Safety: hide overlay when browser navigates back to this page (bfcache)
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            if (btn) { btn.disabled = false; btn.classList.remove('opacity-60', 'cursor-not-allowed'); }
            clearInterval(msgInterval);
        }
    });
})();
</script>

@endsection