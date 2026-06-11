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

                <button class="primary-btn w-full py-4 text-base">
                    Generate Recommendation
                </button>
            </form>

        </div>
    </section>
</div>
@endsection